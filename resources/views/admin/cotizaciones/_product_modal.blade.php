<div class="modal fade" id="productoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Seleccionar Producto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2 mb-3">
                    <div class="col-md-4">
                        <input type="text" id="buscarProducto" class="form-control" placeholder="Buscar producto...">
                    </div>
                    <div class="col-md-3">
                        <select id="filtroCategoria" class="form-select">
                            <option value="">Todas las categorías</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="filtroSubcategoria" class="form-select">
                            <option value="">Todas las subcategorías</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <span class="badge bg-secondary fs-6 p-2" id="productoCount">{{ count($productos) }} productos</span>
                    </div>
                </div>

                <div class="table-responsive" style="max-height:400px;overflow-y:auto;">
                    <table class="table table-hover table-sm mb-0" id="tablaProductosModal">
                        <thead class="table-dark sticky-top">
                            <tr>
                                <th style="width:50px;">ID</th>
                                <th style="width:60px;">Foto</th>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Subcategoría</th>
                                <th style="width:120px;">Precio</th>
                                <th style="width:80px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productos as $p)
                             <tr data-id="{{ $p->id }}"
                                 data-titulo="{{ $p->titulo }}"
                                 data-precio="{{ $p->precio }}"
                                 data-descripcion="{{ $p->descripcion }}"
                                 data-categoria="{{ $p->categoria_id }}"
                                 data-subcategoria="{{ $p->subcategoria_id }}">
                                <td>{{ $p->id }}</td>
                                <td>
                                    @if($p->portada)
                                        <img src="{{ asset('storage/' . $p->portada) }}" alt="" style="width:45px;height:45px;object-fit:cover;border-radius:4px;">
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $p->titulo }}</td>
                                <td>{{ $p->categoria->nombre ?? '—' }}</td>
                                <td>{{ $p->subcategoria->subcategoria ?? '—' }}</td>
                                <td>S/ {{ number_format($p->precio, 2) }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm seleccionar-producto">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    const modal = document.getElementById('productoModal');
    const buscar = document.getElementById('buscarProducto');
    const filtroCat = document.getElementById('filtroCategoria');
    const filtroSub = document.getElementById('filtroSubcategoria');
    const tabla = document.getElementById('tablaProductosModal');
    const rows = tabla.querySelectorAll('tbody tr');
    const contador = document.getElementById('productoCount');

    // subcategorias por categoria
    const subcats = @json(\App\Models\Subcategoria::orderBy('subcategoria')->get(['id', 'id_categoria', 'subcategoria']));

    function filtrar() {
        const texto = buscar.value.toLowerCase();
        const catId = filtroCat.value;
        const subId = filtroSub.value;
        let visibles = 0;

        rows.forEach(function(row) {
            const titulo = row.dataset.titulo.toLowerCase();
            const rowCat = row.dataset.categoria;
            const rowSub = row.dataset.subcategoria;

            const matchTexto = !texto || titulo.includes(texto);
            const matchCat = !catId || rowCat === catId;
            const matchSub = !subId || rowSub === subId;

            const visible = matchTexto && matchCat && matchSub;
            row.style.display = visible ? '' : 'none';
            if (visible) visibles++;
        });

        contador.textContent = visibles + ' productos';
    }

    function actualizarSubcategorias() {
        const catId = filtroCat.value;
        filtroSub.innerHTML = '<option value="">Todas las subcategorías</option>';
        subcats.filter(function(s) { return !catId || String(s.id_categoria) === catId; })
               .forEach(function(s) {
                   const opt = document.createElement('option');
                   opt.value = s.id;
                   opt.textContent = s.subcategoria;
                   filtroSub.appendChild(opt);
               });
        filtrar();
    }

    buscar.addEventListener('input', filtrar);
    filtroCat.addEventListener('change', actualizarSubcategorias);
    filtroSub.addEventListener('change', filtrar);

    function seleccionarProducto(row) {
        const target = window._productoInput;
        if (target) {
            target.value = row.dataset.titulo;
            const tr = target.closest('tr');
            const pu = tr.querySelector('.precio-unitario');
            if (pu) pu.value = row.dataset.precio;
            const pid = tr.querySelector('.producto-id');
            if (pid) pid.value = row.dataset.id;
            const desc = tr.querySelector('textarea');
            if (desc && row.dataset.descripcion) desc.value = row.dataset.descripcion;
            // show thumbnail
            const thumb = tr.querySelector('.producto-thumb');
            const img = row.querySelector('img');
            if (thumb && img) {
                thumb.src = img.src;
                thumb.style.display = '';
            } else if (thumb) {
                thumb.style.display = 'none';
            }
            if (typeof calcularFila === 'function') calcularFila(tr);
        } else {
            const mainInput = document.querySelector('[name="producto"]');
            if (mainInput) mainInput.value = row.dataset.titulo;
            const precioInput = document.querySelector('[name="precio_unitario"]');
            if (precioInput) precioInput.value = row.dataset.precio;
        }
        bootstrap.Modal.getInstance(modal).hide();
    }

    modal.addEventListener('click', function(e) {
        const btn = e.target.closest('.seleccionar-producto');
        if (btn) seleccionarProducto(btn.closest('tr'));
    });
})();
</script>
@endpush
