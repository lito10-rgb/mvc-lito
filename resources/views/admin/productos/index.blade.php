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
        <input type="text" name="buscar" class="form-control form-control-sm" placeholder="Buscar producto..." value="{{ request('buscar') }}">
    </div>
    <div class="col-md-2">
        <label class="form-label small mb-1">Categoría</label>
        <select name="categoria_id" id="filtro-categoria" class="form-select form-select-sm" onchange="this.form.submit();">
            <option value="">Todas</option>
            @foreach($categorias as $cat)
                <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label small mb-1">Subcategoría</label>
        <select name="subcategoria_id" id="filtro-subcategoria" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">Todas</option>
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
        <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search me-1"></i> Filtrar</button>
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
            <th>Marca</th>
            <th>Entrega</th>
            <th>Costo Envío</th>
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

                <td class="td-precio" data-field="precio" data-id="{{ $producto->id }}" data-value="{{ $producto->precio }}">
                    S/. {{ number_format($producto->precio, 2) }}
                </td>

                <td class="td-marca" data-field="marca_id" data-id="{{ $producto->id }}" data-value="{{ $producto->marca_id ?? '' }}">
                    {{ $producto->marca->nombre ?? '—' }}
                </td>

                <td class="td-entrega" data-field="entrega" data-id="{{ $producto->id }}" data-value="{{ $producto->entrega ?? '' }}">
                    {{ $producto->entrega !== null ? $producto->entrega . ' días' : '—' }}
                </td>

                <td class="td-costo-envio" data-field="costo_envio" data-id="{{ $producto->id }}" data-value="{{ $producto->costo_envio ?? '' }}">
                    {{ $producto->costo_envio !== null ? 'S/. ' . number_format($producto->costo_envio, 2) : '—' }}
                </td>

                <td class="td-categoria">{{ $producto->categoria->nombre ?? $producto->categoria->categoria ?? '-' }}</td>

                <td class="td-subcategoria">{{ $producto->subcategoria->subcategoria ?? $producto->subcategoria->nombre ?? '-' }}</td>

                <td>
                    @foreach($producto->negocios as $neg)
                        <span class="badge bg-info">{{ $neg->nombre }}</span>
                    @endforeach
                </td>

                <td class="td-estado" data-field="estado" data-id="{{ $producto->id }}" data-value="{{ $producto->estado }}">
                    <span class="badge bg-{{ $producto->estado ? 'success' : 'secondary' }}">
                        {{ $producto->estado ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>

                <td>
                    <button type="button" class="btn btn-sm btn-info text-white btn-quick-edit"
                            data-id="{{ $producto->id }}"
                            data-titulo="{{ $producto->titulo }}"
                            data-titular="{{ html_entity_decode($producto->titular, ENT_QUOTES, 'UTF-8') }}"
                            data-precio="{{ $producto->precio }}"
                            data-categoria="{{ $producto->categoria_id }}"
                            data-subcategoria="{{ $producto->subcategoria_id }}"
                            data-portada="{{ $producto->portada ? asset('storage/' . $producto->portada) : '' }}"
                            data-ruta="{{ $producto->ruta }}"
                            data-palabras="{{ $producto->palabras_claves ?? '' }}"
                            data-descripcion="{{ $producto->descripcion }}"
                            data-detalles="{{ $producto->detalles }}"
                            data-multimedia="{{ $producto->multimedia }}"
                            data-stock="{{ $producto->stock ?? 0 }}"
                            data-entrega="{{ $producto->entrega ?? 2 }}"
                            data-costo-envio="{{ $producto->costo_envio ?? '' }}"
                            title="Edición rápida">
                        <i class="bi bi-lightning-fill"></i>
                    </button>

                    <a href="{{ route('admin.productos.edit', $producto->id) }}"
                       class="btn btn-sm btn-warning" title="Editar completo">
                        <i class="bi bi-pencil-square"></i>
                    </a>

                    <a href="{{ route('admin.productos.duplicar', $producto->id) }}"
                       class="btn btn-sm btn-success" title="Duplicar producto">
                        <i class="bi bi-copy"></i>
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
                <td colspan="12" class="text-center text-muted">No hay productos registrados.</td>
            </tr>
        @endforelse
    </tbody>
</table>
            </div>

        <button id="btnEliminarSeleccionados" class="btn btn-danger" disabled>
            Eliminar seleccionados
        </button>
        <button id="btnBulkPrecio" class="btn btn-warning" disabled>
            <i class="bi bi-currency-dollar"></i> Cambiar Precio
        </button>
        <button id="btnBulkCostoEnvio" class="btn btn-info text-white" disabled>
            <i class="bi bi-truck"></i> Cambiar Costo Envío
        </button>
        <button id="btnBulkEntrega" class="btn btn-secondary" disabled>
            <i class="bi bi-calendar-check"></i> Cambiar Entrega
        </button>
        <button id="btnBulkMarca" class="btn btn-success" disabled>
            <i class="bi bi-tag"></i> Cambiar Marca
        </button>

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="route-eliminar" content="{{ route('admin.productos.eliminarMultiple') }}">
        <meta name="route-inline-update" content="{{ route('admin.productos.inlineUpdate', 0) }}">
        <meta name="route-bulk-precio" content="{{ route('admin.productos.bulkUpdatePrecio') }}">
        <meta name="route-bulk-costo-envio" content="{{ route('admin.productos.bulkUpdateCostoEnvio') }}">
        <meta name="route-bulk-entrega" content="{{ route('admin.productos.bulkUpdateEntrega') }}">
        <meta name="route-bulk-marca" content="{{ route('admin.productos.bulkUpdateMarca') }}">

            <div class="d-flex justify-content-center mt-3">
                {{ $productos->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Modal Bulk Precio --}}
<div class="modal fade" id="bulkPrecioModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="bi bi-currency-dollar me-2"></i>Cambiar Precio Individual por Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="bulkPrecioForm">
                <div class="modal-body">
                    <p class="small text-muted"><strong id="bulkPrecioCount">0</strong> producto(s) seleccionado(s).</p>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Precio único para todos</span>
                        <input type="number" step="0.01" id="bulkPrecioUnico" class="form-control" min="0" placeholder="Ingrese precio">
                        <button type="button" id="btnPrecioUnico" class="btn btn-outline-warning">Aplicar a todos</button>
                    </div>
                    <p class="small text-muted">O edite cada precio individualmente:</p>
                    <div class="table-responsive mb-3" style="max-height:300px;overflow-y:auto;">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-light"><tr><th>Producto</th><th>Precio actual</th><th>Nuevo precio</th></tr></thead>
                            <tbody id="bulkPrecioList"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Actualizar todos</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Bulk Costo Envío --}}
<div class="modal fade" id="bulkCostoEnvioModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-truck me-2"></i>Cambiar Costo Envío Individual por Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="bulkCostoEnvioForm">
                <div class="modal-body">
                    <p class="small text-muted"><strong id="bulkCostoEnvioCount">0</strong> producto(s) seleccionado(s).</p>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Costo único para todos</span>
                        <input type="number" step="0.01" id="bulkCostoEnvioUnico" class="form-control" min="0" placeholder="Vacío = tarifario">
                        <button type="button" id="btnCostoEnvioUnico" class="btn btn-outline-info text-info">Aplicar a todos</button>
                    </div>
                    <p class="small text-muted">O edite cada costo individualmente:</p>
                    <div class="table-responsive mb-3" style="max-height:300px;overflow-y:auto;">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-light"><tr><th>Producto</th><th>Costo envío actual</th><th>Nuevo costo</th></tr></thead>
                            <tbody id="bulkCostoEnvioList"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info text-white">Actualizar todos</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Bulk Entrega --}}
<div class="modal fade" id="bulkEntregaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title"><i class="bi bi-calendar-check me-2"></i>Cambiar Entrega Individual por Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="bulkEntregaForm">
                <div class="modal-body">
                    <p class="small text-muted"><strong id="bulkEntregaCount">0</strong> producto(s) seleccionado(s).</p>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Entrega única para todos (días)</span>
                        <input type="number" id="bulkEntregaUnico" class="form-control" min="0" placeholder="Días">
                        <button type="button" id="btnEntregaUnico" class="btn btn-outline-secondary">Aplicar a todos</button>
                    </div>
                    <p class="small text-muted">O edite cada entrega individualmente:</p>
                    <div class="table-responsive mb-3" style="max-height:300px;overflow-y:auto;">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-light"><tr><th>Producto</th><th>Entrega actual</th><th>Nueva entrega (días)</th></tr></thead>
                            <tbody id="bulkEntregaList"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-secondary">Actualizar todos</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Bulk Marca --}}
<div class="modal fade" id="bulkMarcaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-tag me-2"></i>Cambiar Marca Individual por Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="bulkMarcaForm">
                <div class="modal-body">
                    <p class="small text-muted"><strong id="bulkMarcaCount">0</strong> producto(s) seleccionado(s).</p>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Marca única para todos</span>
                        <select id="bulkMarcaUnico" class="form-select">
                            <option value="">— Sin marca —</option>
                            @foreach($marcas as $m)
                                <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                            @endforeach
                        </select>
                        <button type="button" id="btnMarcaUnico" class="btn btn-outline-success">Aplicar a todos</button>
                    </div>
                    <p class="small text-muted">O seleccione marca individualmente:</p>
                    <div class="table-responsive mb-3" style="max-height:300px;overflow-y:auto;">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-light"><tr><th>Producto</th><th>Marca actual</th><th>Nueva marca</th></tr></thead>
                            <tbody id="bulkMarcaList"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Actualizar todos</button>
                </div>
            </form>
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
                        <div class="input-group">
                            <input type="text" name="titular" id="qe-titular" class="form-control" required>
                            <button type="button" class="btn btn-outline-warning" id="qe-generar-titular" title="Generar desde título">
                                <i class="fas fa-magic"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Palabras Clave</label>
                        <div class="input-group">
                            <input type="text" name="palabras_claves" id="qe-palabras" class="form-control">
                            <button type="button" class="btn btn-outline-warning" id="qe-generar-palabras" title="Generar desde título">
                                <i class="fas fa-magic"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" id="qe-descripcion" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Detalles</label>
                        <textarea name="detalles" id="qe-detalles" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Entrega (días)</label>
                            <input type="number" name="entrega" id="qe-entrega" class="form-control" min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Stock (0 = por encargo)</label>
                            <input type="number" name="stock" id="qe-stock" class="form-control" min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Costo envío (opcional)</label>
                            <input type="number" step="0.01" name="costo_envio" id="qe-costo-envio" class="form-control" min="0" placeholder="Vacío = tarifario">
                        </div>
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
    const inputStock = document.getElementById('qe-stock');
    const inputEntrega = document.getElementById('qe-entrega');
    const inputCostoEnvio = document.getElementById('qe-costo-envio');
    const selectCat = document.getElementById('qe-categoria');
    const selectSub = document.getElementById('qe-subcategoria');
    const imgPreview = document.getElementById('qe-portada-preview');
    const multimediaPreview = document.getElementById('qe-multimedia-preview');
    const inputImagenesActuales = document.getElementById('qe-imagenes-actuales');
    const inputMultimedia = document.querySelector('[name="multimedia[]"]');
    const errorDiv = document.getElementById('qe-error');
    const submitBtn = document.getElementById('qe-submit');

    // subcategorias data (con todas sus categorías, propias y compartidas)
    @php
        $mapaSubcatCategorias = \Illuminate\Support\Facades\DB::table('categoria_subcategoria')
            ->get()
            ->groupBy('subcategoria_id')
            ->map(fn ($filas) => $filas->pluck('categoria_id')->all())
            ->all();
    @endphp
    const subcategorias = @json($subcategorias->map(function($s) use ($mapaSubcatCategorias) { return ['id' => $s->id, 'cats' => $mapaSubcatCategorias[$s->id] ?? [(int) $s->id_categoria], 'subcategoria' => $s->subcategoria]; }));

    let currentProductId = null;

    // filtrar subcategorias por categoria
    function filtrarSubcats(catId) {
        selectSub.innerHTML = '<option value="">Seleccionar</option>';
        subcategorias.filter(function(s) { return !catId || s.cats.map(String).indexOf(String(catId)) !== -1; })
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

    // generar campos desde titulo en edicion rapida
    document.getElementById('qe-generar-palabras')?.addEventListener('click', function() {
        var title = inputTitulo.value.trim();
        if (!title) { alert('Primero escribe un titulo.'); return; }
        if (!inputPalabras.value.trim()) inputPalabras.value = title;
    });
    document.getElementById('qe-generar-titular')?.addEventListener('click', function() {
        var title = inputTitulo.value.trim();
        if (!title) { alert('Primero escribe un titulo.'); return; }
        if (!inputTitular.value.trim()) inputTitular.value = title;
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

            if (inputStock) inputStock.value = this.dataset.stock || '0';
            if (inputEntrega) inputEntrega.value = this.dataset.entrega || '2';
            if (inputCostoEnvio) inputCostoEnvio.value = this.dataset.costoEnvio || '';

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
        // remove empty file inputs
        if (formData.has('portada') && formData.get('portada') instanceof File && formData.get('portada').size === 0) {
            formData.delete('portada');
        }
        if (formData.has('multimedia[]')) {
            var multimediaEntries = formData.getAll('multimedia[]').filter(function(f) { return f instanceof File && f.size > 0; });
            formData.delete('multimedia[]');
            multimediaEntries.forEach(function(f) { formData.append('multimedia[]', f); });
        }

        fetch(form.action, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(function(r) { return r.json().then(function(d) { return { status: r.status, data: d }; }); })
        .then(function(res) {
            if (res.status !== 200) {
                var msgs = [];
                if (res.data.errors) {
                    for (var field in res.data.errors) {
                        msgs.push(res.data.errors[field].join(', '));
                    }
                }
                errorDiv.innerHTML = (msgs.length ? msgs.join('<br>') : (res.data.message || 'Error al guardar.'));
                errorDiv.classList.remove('d-none');
                return;
            }

            // actualizar fila en la tabla
            var row = document.getElementById('producto-row-' + currentProductId);
            if (row) {
                row.querySelector('.td-titulo').textContent = inputTitulo.value;
                var precioEl = row.querySelector('.td-precio');
                precioEl.textContent = 'S/. ' + parseFloat(inputPrecio.value).toFixed(2);
                precioEl.dataset.value = inputPrecio.value;
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

/* ── Inline editing: Precio y Costo Envío ── */
(function() {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    var baseUrl = document.querySelector('meta[name="route-inline-update"]').content.replace('/0', '');

    document.querySelectorAll('.td-precio, .td-costo-envio, .td-entrega').forEach(function(cell) {
        cell.style.cursor = 'pointer';
        cell.title = 'Click para editar';
        cell.addEventListener('click', function() {
            if (cell.querySelector('input')) return;
            var field = cell.dataset.field;
            var currentVal = cell.dataset.value;
            var input = document.createElement('input');
            input.type = 'number';
            input.step = '0.01';
            input.min = '0';
            input.className = 'form-control form-control-sm d-inline-block';
            input.style.width = '120px';
            input.value = currentVal;

            var originalHTML = cell.innerHTML;

            function save() {
                var newVal = input.value;
                cell.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
                fetch(baseUrl + '/' + cell.dataset.id, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ field: field, value: newVal })
                })
                .then(function(r) { return r.json(); })
                .then(function(res) {
                    if (res.success) {
                        cell.innerHTML = res.display;
                        cell.dataset.value = res.value === null ? '' : res.value;
                    } else {
                        cell.innerHTML = originalHTML;
                    }
                })
                .catch(function() {
                    cell.innerHTML = originalHTML;
                });
            }

            function cancel() {
                cell.innerHTML = originalHTML;
            }

            input.addEventListener('blur', save);
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') { input.removeEventListener('blur', save); save(); }
                if (e.key === 'Escape') { input.removeEventListener('blur', save); cancel(); }
            });

            cell.innerHTML = '';
            cell.appendChild(input);
            input.focus();
            input.select();
        });
    });
})();

/* ── Inline toggle: Estado ── */
(function() {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    var baseUrl = document.querySelector('meta[name="route-inline-update"]').content.replace('/0', '');

    document.querySelectorAll('.td-estado').forEach(function(cell) {
        cell.style.cursor = 'pointer';
        cell.title = 'Click para cambiar estado';
        cell.addEventListener('click', function() {
            var newVal = cell.dataset.value === '1' ? '0' : '1';
            var originalHTML = cell.innerHTML;
            cell.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            fetch(baseUrl + '/' + cell.dataset.id, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ field: 'estado', value: newVal })
            })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res.success) {
                    cell.innerHTML = res.display;
                    cell.dataset.value = res.value;
                } else {
                    cell.innerHTML = originalHTML;
                }
            })
            .catch(function() {
                cell.innerHTML = originalHTML;
            });
        });
    });
})();

/* ── Bulk actions ── */
(function() {
    var checkAll = document.getElementById('checkAll');
    var checkItems = document.querySelectorAll('.checkItem');
    var btnEliminar = document.getElementById('btnEliminarSeleccionados');
    var btnPrecio = document.getElementById('btnBulkPrecio');
    var btnCostoEnvio = document.getElementById('btnBulkCostoEnvio');
    var btnEntrega = document.getElementById('btnBulkEntrega');
    var btnMarca = document.getElementById('btnBulkMarca');
    var bulkPrecioModal = document.getElementById('bulkPrecioModal');
    var bulkCostoEnvioModal = document.getElementById('bulkCostoEnvioModal');
    var bulkEntregaModal = document.getElementById('bulkEntregaModal');
    var bulkMarcaModal = document.getElementById('bulkMarcaModal');
    var bulkPrecioCount = document.getElementById('bulkPrecioCount');
    var bulkCostoEnvioCount = document.getElementById('bulkCostoEnvioCount');
    var bulkEntregaCount = document.getElementById('bulkEntregaCount');
    var bulkPrecioList = document.getElementById('bulkPrecioList');
    var bulkCostoEnvioList = document.getElementById('bulkCostoEnvioList');
    var bulkEntregaList = document.getElementById('bulkEntregaList');
    var bulkMarcaCount = document.getElementById('bulkMarcaCount');
    var bulkMarcaList = document.getElementById('bulkMarcaList');

    function getSelectedIds() {
        return Array.from(document.querySelectorAll('.checkItem:checked')).map(function(cb) { return cb.value; });
    }

    function getSelectedRows() {
        return Array.from(document.querySelectorAll('.checkItem:checked')).map(function(cb) {
            return document.getElementById('producto-row-' + cb.value);
        }).filter(function(r) { return r; });
    }

    function updateButtons() {
        var count = getSelectedIds().length;
        btnEliminar.disabled = count === 0;
        btnPrecio.disabled = count === 0;
        btnCostoEnvio.disabled = count === 0;
        btnEntrega.disabled = count === 0;
        btnMarca.disabled = count === 0;
    }

    checkAll.addEventListener('change', function() {
        checkItems.forEach(function(cb) { cb.checked = checkAll.checked; });
        updateButtons();
    });
    checkItems.forEach(function(cb) {
        cb.addEventListener('change', updateButtons);
    });

    btnPrecio.addEventListener('click', function() {
        var rows = getSelectedRows();
        bulkPrecioCount.textContent = rows.length;
        bulkPrecioList.innerHTML = rows.map(function(r) {
            var id = r.querySelector('.checkItem').value;
            var title = r.querySelector('.td-titulo').textContent.trim();
            var precio = r.querySelector('.td-precio').dataset.value || '0';
            return '<tr><td>' + title + '</td><td>S/. ' + parseFloat(precio).toFixed(2) + '</td><td><input type="number" step="0.01" name="precios[' + id + ']" class="form-control form-control-sm" value="' + precio + '" min="0" style="width:120px"></td></tr>';
        }).join('');
        new bootstrap.Modal(bulkPrecioModal).show();
    });

    btnCostoEnvio.addEventListener('click', function() {
        var rows = getSelectedRows();
        bulkCostoEnvioCount.textContent = rows.length;
        bulkCostoEnvioList.innerHTML = rows.map(function(r) {
            var id = r.querySelector('.checkItem').value;
            var title = r.querySelector('.td-titulo').textContent.trim();
            var envio = r.querySelector('.td-costo-envio').dataset.value;
            var display = envio !== '' ? 'S/. ' + parseFloat(envio).toFixed(2) : '— (tarifario)';
            return '<tr><td>' + title + '</td><td>' + display + '</td><td><input type="number" step="0.01" name="costos_envio[' + id + ']" class="form-control form-control-sm" value="' + envio + '" min="0" placeholder="Vacío = tarifario" style="width:120px"></td></tr>';
        }).join('');
        new bootstrap.Modal(bulkCostoEnvioModal).show();
    });

    document.getElementById('btnPrecioUnico').addEventListener('click', function() {
        var val = document.getElementById('bulkPrecioUnico').value;
        if (val === '') return;
        bulkPrecioList.querySelectorAll('[name^="precios["]').forEach(function(inp) { inp.value = val; });
    });

    document.getElementById('btnCostoEnvioUnico').addEventListener('click', function() {
        var val = document.getElementById('bulkCostoEnvioUnico').value;
        bulkCostoEnvioList.querySelectorAll('[name^="costos_envio["]').forEach(function(inp) { inp.value = val; });
    });

    btnEntrega.addEventListener('click', function() {
        var rows = getSelectedRows();
        bulkEntregaCount.textContent = rows.length;
        bulkEntregaList.innerHTML = rows.map(function(r) {
            var id = r.querySelector('.checkItem').value;
            var title = r.querySelector('.td-titulo').textContent.trim();
            var entrega = r.querySelector('.td-entrega').dataset.value;
            var display = entrega !== '' ? entrega + ' días' : '—';
            return '<tr><td>' + title + '</td><td>' + display + '</td><td><input type="number" name="entregas[' + id + ']" class="form-control form-control-sm" value="' + entrega + '" min="0" style="width:100px"></td></tr>';
        }).join('');
        new bootstrap.Modal(bulkEntregaModal).show();
    });

    document.getElementById('btnEntregaUnico').addEventListener('click', function() {
        var val = document.getElementById('bulkEntregaUnico').value;
        if (val === '') return;
        bulkEntregaList.querySelectorAll('[name^="entregas["]').forEach(function(inp) { inp.value = val; });
    });

    var marcasData = @json($marcas->map(fn($m) => ['id' => $m->id, 'nombre' => $m->nombre]));

    function marcaSelectHtml(id, currentVal) {
        var opts = '<option value="">— Sin marca —</option>';
        marcasData.forEach(function(m) {
            opts += '<option value="' + m.id + '"' + (String(m.id) === String(currentVal) ? ' selected' : '') + '>' + m.nombre + '</option>';
        });
        return '<select name="marcas[' + id + ']" class="form-select form-select-sm">' + opts + '</select>';
    }

    btnMarca.addEventListener('click', function() {
        var rows = getSelectedRows();
        bulkMarcaCount.textContent = rows.length;
        bulkMarcaList.innerHTML = rows.map(function(r) {
            var id = r.querySelector('.checkItem').value;
            var title = r.querySelector('.td-titulo').textContent.trim();
            var marcaId = r.querySelector('.td-marca').dataset.value || '';
            var marcaNombre = r.querySelector('.td-marca').textContent.trim();
            return '<tr><td>' + title + '</td><td>' + marcaNombre + '</td><td>' + marcaSelectHtml(id, marcaId) + '</td></tr>';
        }).join('');
        new bootstrap.Modal(bulkMarcaModal).show();
    });

    document.getElementById('btnMarcaUnico').addEventListener('click', function() {
        var val = document.getElementById('bulkMarcaUnico').value;
        if (val === '') return;
        bulkMarcaList.querySelectorAll('[name^="marcas["]').forEach(function(sel) { sel.value = val; });
    });

    function valoresPorId(form, prefix) {
        var inputs = form.querySelectorAll('[name^="' + prefix + '"]');
        var map = {};
        inputs.forEach(function(inp) {
            var match = inp.name.match(/\d+/);
            if (match) map[match[0]] = inp.value;
        });
        return map;
    }

    document.getElementById('bulkPrecioForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var precios = valoresPorId(this, 'precios');
        var ids = Object.keys(precios);
        if (!ids.length) return;
        var btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        fetch(document.querySelector('meta[name="route-bulk-precio"]').content, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
            body: JSON.stringify({ precios: precios })
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.success) {
                location.reload();
            } else {
                alert('Error: ' + (res.message || ' desconocido'));
            }
        })
        .catch(function() { alert('Error de conexión.'); })
        .finally(function() { btn.disabled = false; btn.textContent = 'Actualizar todos'; });
    });

    document.getElementById('bulkCostoEnvioForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var costos = valoresPorId(this, 'costos_envio');
        var ids = Object.keys(costos);
        if (!ids.length) return;
        var btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        fetch(document.querySelector('meta[name="route-bulk-costo-envio"]').content, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
            body: JSON.stringify({ costos_envio: costos })
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.success) {
                location.reload();
            } else {
                alert('Error: ' + (res.message || ' desconocido'));
            }
        })
        .catch(function() { alert('Error de conexión.'); })
        .finally(function() { btn.disabled = false; btn.textContent = 'Actualizar todos'; });
    });

    document.getElementById('bulkEntregaForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var entregas = valoresPorId(this, 'entregas');
        var ids = Object.keys(entregas);
        if (!ids.length) return;
        var btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        fetch(document.querySelector('meta[name="route-bulk-entrega"]').content, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
            body: JSON.stringify({ entregas: entregas })
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.success) {
                location.reload();
            } else {
                alert('Error: ' + (res.message || ' desconocido'));
            }
        })
        .catch(function() { alert('Error de conexión.'); })
        .finally(function() { btn.disabled = false; btn.textContent = 'Actualizar todos'; });
    });

    document.getElementById('bulkMarcaForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var marcas = valoresPorId(this, 'marcas');
        var ids = Object.keys(marcas);
        if (!ids.length) return;
        var btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        fetch(document.querySelector('meta[name="route-bulk-marca"]').content, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
            body: JSON.stringify({ marcas: marcas })
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.success) {
                location.reload();
            } else {
                alert('Error: ' + (res.message || ' desconocido'));
            }
        })
        .catch(function() { alert('Error de conexión.'); })
        .finally(function() { btn.disabled = false; btn.textContent = 'Actualizar todos'; });
    });
})();

/* ── Filtro de subcategorías dependiente de categoría ── */
var subsData = @json($subcategorias->map(function($s) use ($mapaSubcatCategorias) { return ['id' => $s->id, 'cats' => $mapaSubcatCategorias[$s->id] ?? [(int) $s->id_categoria], 'subcategoria' => $s->subcategoria]; }));
function filtrarSubcategorias(catId) {
    var select = document.getElementById('filtro-subcategoria');
    if (!select) return;
    var selectedVal = '{{ request('subcategoria_id') }}';
    select.innerHTML = '<option value="">Todas</option>';
    subsData.filter(function(s) { return !catId || s.cats.map(String).indexOf(String(catId)) !== -1; }).forEach(function(s) {
        var opt = document.createElement('option');
        opt.value = s.id;
        opt.textContent = s.subcategoria;
        if (String(s.id) === String(selectedVal)) opt.selected = true;
        select.appendChild(opt);
    });
}
document.addEventListener('DOMContentLoaded', function() {
    var catSelect = document.getElementById('filtro-categoria');
    if (catSelect) filtrarSubcategorias(catSelect.value);
});
</script>
@endpush
