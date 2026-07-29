<section class="bg-light py-4">
    <div class="container">
        <h2 class="mb-4 text-center">Busca tu Producto</h2>

        <!-- Fila 1: Búsqueda general -->
        <form action="{{ route('productos.buscar') }}" method="GET" class="mb-3">
            <div class="row g-3 justify-content-center align-items-center">

                <!-- Campo de búsqueda con autocomplete -->
                <div class="col-md-5 col-12">
                    <div class="position-relative">
                        <input type="text" name="q" class="form-control select-dorado"
                               placeholder="Buscar por nombre o descripción"
                               value="{{ request('q') }}"
                               id="search-input"
                               autocomplete="off">
                        <div id="search-results" class="dropdown-menu w-100" style="display:none; max-height:400px; overflow-y:auto; position:absolute; top:100%; left:0; z-index:1000;"></div>
                    </div>
                </div>

                <!-- Switch / checkbox "Más vistos" -->
                <div class="col-md-2 col-6 d-flex justify-content-center">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="masVistos" name="mas_vistos"
                               {{ request()->has('mas_vistos') ? 'checked' : '' }}>
                        <label class="form-check-label" for="masVistos">Más vistos</label>
                    </div>
                </div>

                <!-- Botón de búsqueda -->
                <div class="col-md-auto col-6 text-center">
                    <button type="submit" class="btn btn-theme-accent w-100">
                        <i class="fas fa-search me-2"></i>Buscar
                    </button>
                </div>

                <!-- Botón Limpiar -->
                <div class="col-md-auto col-6 text-center">
                    <a href="{{ route('productos.buscar') }}" class="btn btn-outline-theme-accent w-100">
                        <i class="fas fa-times me-2"></i>Limpiar
                    </a>
                </div>

            </div>
        </form>


        <!-- Fila 2: Filtros detallados -->
        <form action="{{ route('productos.buscar') }}" method="GET" id="filtros-form">
            <div class="row g-3 align-items-center justify-content-center">
                <div class="col-md-2 col-6">
                    <select name="categoria" id="filtro-categoria" class="form-select select-dorado" onchange="filtrarSubcategorias(this.value); this.form.submit();">
                        <option value="">Categoría</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->nombre }}" {{ request('categoria') == $cat->nombre ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <select name="subcategoria" id="filtro-subcategoria" class="form-select select-dorado" onchange="this.form.submit()">
                        <option value="">Subcategoría</option>
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <select name="marca" class="form-select select-dorado" onchange="this.form.submit()">
                        <option value="">Marca</option>
                        @foreach($marcas as $m)
                            <option value="{{ $m->nombre }}" {{ request('marca') == $m->nombre ? 'selected' : '' }}>{{ $m->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 col-6">
                    <input type="number" name="precio_min" class="form-control select-dorado" placeholder="Mín" value="{{ request('precio_min') }}">
                </div>
                <div class="col-md-1 col-6">
                    <input type="number" name="precio_max" class="form-control select-dorado" placeholder="Máx" value="{{ request('precio_max') }}">
                </div>
                <div class="col-md-2 col-6">
                    <button type="submit" class="btn btn-theme-accent w-100">
                        <i class="fas fa-filter me-2"></i>Filtrar
                    </button>
                </div>
                <div class="col-md-2 col-6">
                    <a href="{{ route('productos.buscar') }}" class="btn btn-outline-theme-accent w-100">
                        <i class="fas fa-undo me-2"></i>Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>
</section>

@push('styles')
<style>
    .select-dorado {
        background-color: color-mix(in srgb, var(--theme-accent, #c5a200) 85%, white) !important;
        border-color: var(--theme-accent, #c5a200) !important;
    }
    .select-dorado:focus {
        border-color: var(--theme-accent, #c5a200) !important;
        box-shadow: 0 0 0 0.25rem color-mix(in srgb, var(--theme-accent, #c5a200) 40%, transparent) !important;
    }
</style>
@endpush

@push('scripts')
<script>
/* ── Autocomplete ── */
(function() {
    var input = document.getElementById('search-input');
    var results = document.getElementById('search-results');
    if (!input || !results) return;

    var timer = null;
    var base = (document.querySelector('meta[name="app-base"]')?.getAttribute('content') || '').replace(/\/+$/, '');

    input.addEventListener('input', function() {
        var q = this.value.trim();
        results.style.display = 'none';
        clearTimeout(timer);

        if (q.length < 2) return;

        timer = setTimeout(function() {
            fetch(base + '/productos/autocomplete?q=' + encodeURIComponent(q), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                results.innerHTML = '';
                if (!data.length) {
                    results.innerHTML = '<div class="dropdown-item text-muted">Sin resultados</div>';
                } else {
                    data.forEach(function(p) {
                        var a = document.createElement('a');
                        a.className = 'dropdown-item d-flex align-items-center gap-2';
                        a.href = base + '/producto/' + (p.ruta || p.slug || p.id);
                        var img = p.portada
                            ? '<img src="' + base + '/storage/' + p.portada + '" style="width:40px;height:40px;object-fit:cover;border-radius:4px;" alt="">'
                            : '<div style="width:40px;height:40px;background:#eee;border-radius:4px;flex-shrink:0;"></div>';
                        var price = (p.tipo === 'servicio' && Number(p.precio) === 0)
                            ? 'Consultar precio'
                            : 'S/ ' + Number(p.precio).toFixed(2);
                        a.innerHTML = img + '<div class="flex-grow-1"><div class="small fw-bold">' + p.titulo + '</div><small class="text-muted">' + price + '</small></div>';
                        results.appendChild(a);
                    });
                }
                results.style.display = 'block';
            })
            .catch(function() { results.style.display = 'none'; });
        }, 300);
    });

    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !results.contains(e.target)) {
            results.style.display = 'none';
        }
    });
})();

/* ── Subcategorías dependientes de categoría ── */
var catsData = @json($categorias->map(function($c) { return ['id' => $c->id, 'nombre' => $c->nombre]; }));
var subsData = @json($subcategorias->map(function($s) { return ['id' => $s->id, 'id_categoria' => $s->id_categoria, 'subcategoria' => $s->subcategoria]; }));
function filtrarSubcategorias(catNombre) {
    var select = document.getElementById('filtro-subcategoria');
    if (!select) return;
    var selectedVal = '{{ request('subcategoria') }}';
    select.innerHTML = '<option value="">Subcategoría</option>';
    var cat = catsData.find(function(c) { return c.nombre === catNombre; });
    var catId = cat ? cat.id : null;
    subsData.filter(function(s) { return !catId || s.id_categoria === catId; }).forEach(function(s) {
        var opt = document.createElement('option');
        opt.value = s.subcategoria;
        opt.textContent = s.subcategoria;
        if (s.subcategoria === selectedVal) opt.selected = true;
        select.appendChild(opt);
    });
}
document.addEventListener('DOMContentLoaded', function() {
    var catSelect = document.getElementById('filtro-categoria');
    if (catSelect) filtrarSubcategorias(catSelect.value);
});
</script>
@endpush