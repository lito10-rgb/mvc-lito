@extends('layouts.volt')
@section('title', 'Listado de Productos')
@push('styles')
<style>
    .table img { max-width: 60px; height: auto; }
    .modal-img-preview { max-width: 150px; max-height: 150px; object-fit: cover; border-radius: 6px; }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="card border-0 shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Productos</h5>
            <a href="{{ route('admin.productos.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo Producto
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

                        <form method="GET" class="row g-2 mb-3 align-items-end">
    <div class="col-md-3">
        <label class="form-label small mb-1">Buscar</label>
        <input type="text" name="buscar" class="form-control form-control-sm" placeholder="Título, descripción, detalles..." value="{{ request('buscar') }}">
    </div>
    <div class="col-md-2">
        <label class="form-label small mb-1">Categoría</label>
        <select name="categoria_id" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">Todas</option>
            @foreach($categorias as $cat)
                <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label small mb-1">Subcategoría</label>
        <select name="subcategoria_id" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">Todas</option>
            @foreach($subcategorias as $sub)
                <option value="{{ $sub->id }}" {{ request('subcategoria_id') == $sub->id ? 'selected' : '' }}>{{ $sub->subcategoria }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label small mb-1">Negocio</label>
        <select name="negocio_id" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">Todos</option>
            @foreach($negocios as $neg)
                <option value="{{ $neg->id }}" {{ request('negocio_id', 1) == $neg->id ? 'selected' : '' }}>{{ $neg->nombre }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-1">
        <label class="form-label small mb-1">Orden</label>
        <select name="orden" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="reciente" {{ request('orden', 'reciente') == 'reciente' ? 'selected' : '' }}>Más reciente</option>
            <option value="vistas" {{ request('orden') == 'vistas' ? 'selected' : '' }}>Más vistos</option>
            <option value="ventas" {{ request('orden') == 'ventas' ? 'selected' : '' }}>Más vendidos</option>
        </select>
    </div>
    <div class="col-md-2 d-grid">
        <label class="form-label small mb-1">&nbsp;</label>
        <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search me-1"></i>Filtrar</button>
    </div>
</form>
                        <div class="table-responsive">
<table class="table table-hover align-middle text-sm">
    <thead class="table-dark text-center">
        <tr>
            <th><input type="checkbox" id="checkAll"></th>
            <th>ID</th>
            <th>Imagen</th>
            <th>Título</th>
            <th>Precio</th>
            <th>Categoría</th>
            <th>Subcategoría</th>
            <th>Negocios</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody class="text-center">
        @forelse ($productos as $producto)
            <tr id="producto-row-{{ $producto->id }}">
                <td>
                    <input type="checkbox" name="ids[]" class="checkItem" value="{{ $producto->id }}">
                </td>

                <td>{{ $producto->id }}</td>

                <td style="width:80px;">
                    @if($producto->portada)
                        <img src="{{ asset('storage/' . $producto->portada) }}"
                             alt="" class="img-fluid rounded" style="max-height:60px;">
                    @else
                        <span class="text-muted">Sin imagen</span>
                    @endif
                </td>

                <td class="td-titulo">{{ $producto->titulo }}</td>

                <td class="td-precio">S/. {{ number_format($producto->precio, 2) }}</td>

                <td class="td-categoria">{{ $producto->categoria->nombre ?? $producto->categoria->categoria ?? '-' }}</td>

                <td class="td-subcategoria">{{ $producto->subcategoria->subcategoria ?? $producto->subcategoria->nombre ?? '-' }}</td>

                <td>
                    @foreach($producto->negocios as $neg)
                        <span class="badge bg-info">{{ $neg->nombre }}</span>
                    @endforeach
                </td>

                <td>
                    <span class="badge bg-{{ $producto->estado ? 'success' : 'secondary' }}">
                        {{ $producto->estado ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>

                <td>
                    <button type="button" class="btn btn-sm btn-info text-white btn-quick-edit"
                            data-id="{{ $producto->id }}"
                            data-titulo="{{ $producto->titulo }}"
                            data-titular="{{ $producto->titular }}"
                            data-precio="{{ $producto->precio }}"
                            data-categoria="{{ $producto->categoria_id }}"
                            data-subcategoria="{{ $producto->subcategoria_id }}"
                            data-portada="{{ $producto->portada ? asset('storage/' . $producto->portada) : '' }}"
                            data-ruta="{{ $producto->ruta }}"
                            data-palabras="{{ $producto->palabras_claves ?? '' }}"
                            data-descripcion="{{ $producto->descripcion }}"
                            data-detalles="{{ $producto->detalles }}"
                            data-multimedia="{{ $producto->multimedia }}"
                            title="Edición rápida">
                        <i class="bi bi-lightning-fill"></i>
                    </button>

                    <a href="{{ route('admin.productos.edit', $producto->id) }}"
                       class="btn btn-sm btn-warning" title="Editar completo">
                        <i class="bi bi-pencil-square"></i>
                    </a>

                    <form action="{{ route('admin.productos.destroy', $producto->id) }}"
                          method="POST" class="d-inline"
                          onsubmit="return confirm('¿Eliminar este producto?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="10" class="text-center text-muted">No hay productos registrados.</td>
            </tr>
        @endforelse
    </tbody>
</table>
            </div>

        <button id="btnEliminarSeleccionados" class="btn btn-danger" disabled>
            Eliminar seleccionados
        </button>

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="route-eliminar" content="{{ route('admin.productos.eliminarMultiple') }}">

            <div class="d-flex justify-content-center mt-3">
                {{ $productos->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Modal Edición Rápida --}}
<div class="modal fade" id="quickEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="quickEditForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title"><i class="bi bi-lightning-fill me-2"></i>Edición Rápida</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <img id="qe-portada-preview" src="" alt="" class="modal-img-preview">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Portada</label>
                        <input type="file" name="portada" class="form-control" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Imágenes del producto</label>
                        <input type="file" name="multimedia[]" class="form-control" accept="image/*" multiple>
                        <div id="qe-multimedia-preview" class="row mt-2 g-2"></div>
                        <input type="hidden" name="imagenes_actuales" id="qe-imagenes-actuales" value="[]">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Título</label>
                        <input type="text" name="titulo" id="qe-titulo" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Precio (S/)</label>
                        <input type="number" step="0.01" name="precio" id="qe-precio" class="form-control" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Categoría</label>
                        <select name="categoria_id" id="qe-categoria" class="form-select" required>
                            <option value="">Seleccionar</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}" data-id="{{ $cat->id }}">{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Subcategoría</label>
                        <select name="subcategoria_id" id="qe-subcategoria" class="form-select" required>
                            <option value="">Seleccionar</option>
                            @foreach($subcategorias as $sub)
                                <option value="{{ $sub->id }}" data-cat="{{ $sub->id_categoria }}">{{ $sub->subcategoria }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ruta (slug)</label>
                        <input type="text" name="ruta" id="qe-ruta" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Titular</label>
                        <input type="text" name="titular" id="qe-titular" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Palabras Clave</label>
                        <input type="text" name="palabras_claves" id="qe-palabras" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" id="qe-descripcion" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Detalles</label>
                        <textarea name="detalles" id="qe-detalles" class="form-control" rows="3"></textarea>
                    </div>

                    <div id="qe-error" class="alert alert-danger d-none"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success" id="qe-submit">
                        <i class="bi bi-check-lg me-1"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    const modal = document.getElementById('quickEditModal');
    const form = document.getElementById('quickEditForm');
    const inputTitulo = document.getElementById('qe-titulo');
    const inputPrecio = document.getElementById('qe-precio');
    const inputRuta = document.getElementById('qe-ruta');
    const inputTitular = document.getElementById('qe-titular');
    const inputPalabras = document.getElementById('qe-palabras');
    const inputDescripcion = document.getElementById('qe-descripcion');
    const inputDetalles = document.getElementById('qe-detalles');
    const selectCat = document.getElementById('qe-categoria');
    const selectSub = document.getElementById('qe-subcategoria');
    const imgPreview = document.getElementById('qe-portada-preview');
    const multimediaPreview = document.getElementById('qe-multimedia-preview');
    const inputImagenesActuales = document.getElementById('qe-imagenes-actuales');
    const inputMultimedia = document.querySelector('[name="multimedia[]"]');
    const errorDiv = document.getElementById('qe-error');
    const submitBtn = document.getElementById('qe-submit');

    // subcategorias data
    const subcategorias = @json($subcategorias->map(function($s) { return ['id' => $s->id, 'id_categoria' => $s->id_categoria, 'subcategoria' => $s->subcategoria]; }));

    let currentProductId = null;

    // filtrar subcategorias por categoria
    function filtrarSubcats(catId) {
        selectSub.innerHTML = '<option value="">Seleccionar</option>';
        subcategorias.filter(function(s) { return String(s.id_categoria) === String(catId); })
            .forEach(function(s) {
                var opt = document.createElement('option');
                opt.value = s.id;
                opt.textContent = s.subcategoria;
                selectSub.appendChild(opt);
            });
    }

    selectCat.addEventListener('change', function() {
        filtrarSubcats(this.value);
    });

    // eliminar imagen de multimedia (marca para no enviarla)
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-qe-img')) {
            var imgPath = e.target.dataset.imagen;
            var actuales = JSON.parse(inputImagenesActuales.value || '[]');
            inputImagenesActuales.value = JSON.stringify(actuales.filter(function(p) { return p !== imgPath; }));
            e.target.closest('.col-4, .col-md-3').remove();
        }
    });

    // abrir modal
    document.querySelectorAll('.btn-quick-edit').forEach(function(btn) {
        btn.addEventListener('click', function() {
            errorDiv.classList.add('d-none');
            currentProductId = this.dataset.id;
            inputTitulo.value = this.dataset.titulo;
            inputPrecio.value = this.dataset.precio;
            if (inputRuta) inputRuta.value = this.dataset.ruta || '';
            if (inputTitular) inputTitular.value = this.dataset.titular || '';
            if (inputPalabras) inputPalabras.value = this.dataset.palabras || '';
            if (inputDescripcion) inputDescripcion.value = this.dataset.descripcion || '';
            if (inputDetalles) inputDetalles.value = this.dataset.detalles || '';

            // categoria
            var catId = this.dataset.categoria;
            selectCat.value = catId || '';
            filtrarSubcats(catId);
            selectSub.value = this.dataset.subcategoria || '';

            // portada preview
            var portadaUrl = this.dataset.portada;
            if (portadaUrl) {
                imgPreview.src = portadaUrl;
                imgPreview.style.display = 'inline';
            } else {
                imgPreview.src = '';
                imgPreview.style.display = 'none';
            }

            // multimedia preview
            multimediaPreview.innerHTML = '';
            var multimediaRaw = this.dataset.multimedia;
            var imagenes = [];
            try { imagenes = JSON.parse(multimediaRaw) || []; } catch(e) {}
            // normalizar: [{"foto":"path"}] → ["path"]
            imagenes = imagenes.map(function(i) { return typeof i === 'object' ? (i.foto || '') : i; }).filter(function(i) { return i; });
            inputImagenesActuales.value = JSON.stringify(imagenes);
            imagenes.forEach(function(img) {
                var col = document.createElement('div');
                col.className = 'col-4 col-md-3';
                col.innerHTML = '<div class="position-relative"><img src="{{ asset("storage") }}/' + img + '" class="img-fluid rounded border"><button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 btn-remove-qe-img" data-imagen="' + img + '" style="font-size:10px;line-height:1;padding:2px 5px;">&times;</button></div>';
                multimediaPreview.appendChild(col);
            });

            // actualizar action del form
            form.action = '{{ url("admin/productos/quick-update") }}/' + currentProductId;

            var modalBootstrap = new bootstrap.Modal(modal);
            modalBootstrap.show();
        });
    });

    // submit via AJAX
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        errorDiv.classList.add('d-none');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Guardando...';

        var formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(function(r) { return r.json().then(function(d) { return { status: r.status, data: d }; }); })
        .then(function(res) {
            if (res.status !== 200) {
                errorDiv.textContent = res.data.message || 'Error al guardar.';
                errorDiv.classList.remove('d-none');
                return;
            }

            // actualizar fila en la tabla
            var row = document.getElementById('producto-row-' + currentProductId);
            if (row) {
                row.querySelector('.td-titulo').textContent = inputTitulo.value;
                row.querySelector('.td-precio').textContent = 'S/. ' + parseFloat(inputPrecio.value).toFixed(2);
                var catText = selectCat.options[selectCat.selectedIndex]?.text || '';
                var subText = selectSub.options[selectSub.selectedIndex]?.text || '';
                row.querySelector('.td-categoria').textContent = catText;
                row.querySelector('.td-subcategoria').textContent = subText;

                // actualizar portada si se subio nueva imagen
                var btn = row.querySelector('.btn-quick-edit');
                if (res.data.portada_url) {
                    var imgCell = row.querySelectorAll('td')[2];
                    if (imgCell) imgCell.innerHTML = '<img src="' + res.data.portada_url + '" alt="" class="img-fluid rounded" style="max-height:60px;">';
                    if (btn) btn.dataset.portada = res.data.portada_url;
                } else if (btn && !btn.dataset.portada) {
                    var imgCell = row.querySelectorAll('td')[2];
                    if (imgCell) imgCell.innerHTML = '<span class="text-muted">Sin imagen</span>';
                }
            }

            // cerrar modal
            var modalInstance = bootstrap.Modal.getInstance(modal);
            if (modalInstance) modalInstance.hide();

            // feedback
            var alerta = document.createElement('div');
            alerta.className = 'alert alert-success alert-dismissible fade show';
            alerta.innerHTML = 'Producto actualizado. <button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
            document.querySelector('.card-body').insertBefore(alerta, document.querySelector('.card-body').firstChild);

            setTimeout(function() { alerta.remove(); }, 3000);
        })
        .catch(function(err) {
            errorDiv.textContent = 'Error de conexión.';
            errorDiv.classList.remove('d-none');
        })
        .finally(function() {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Guardar';
        });
    });
})();
</script>
@endpush
