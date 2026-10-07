@extends('layouts.admin')

@section('title', 'Tareas / Captura')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-list-check me-2 text-purple"></i> Tareas / Captura</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3">
        @if($puede['proveedores'])
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-envelope-open-text fa-2x me-3" style="color:#0d6efd;"></i>
                        <h5 class="fw-bold mb-0">Proveedores que escribieron</h5>
                    </div>
                    <p class="text-muted small flex-grow-1">Escanea tu Outlook (Inbox + correos con banderita 🔖) y lista los remitentes. Desde ahí inscribe el proveedor y captura sus catálogos.</p>
                    <a href="{{ route('admin.tareas.outlook') }}" class="btn btn-primary">
                        <i class="fas fa-envelope me-1"></i> Escanear Outlook
                    </a>
                </div>
            </div>
        </div>
        @endif

        @if($puede['productos'])
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-copy fa-2x me-3" style="color:#198754;"></i>
                        <h5 class="fw-bold mb-0">Productos hermanos</h5>
                    </div>
                    <p class="text-muted small flex-grow-1">Agrupa todos los productos que has cotizado y crea productos "hermanos" a partir de ellos.</p>
                    <a href="{{ route('admin.tareas.productosCotizados') }}" class="btn btn-success">
                        <i class="fas fa-copy me-1"></i> Ver productos cotizados
                    </a>
                </div>
            </div>
        </div>
        @endif

        @if($puede['proveedores'] && $puede['productos'])
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-globe fa-2x me-3" style="color:#fd7e14;"></i>
                        <h5 class="fw-bold mb-0">Proveedores para un producto</h5>
                    </div>
                    <p class="text-muted small flex-grow-1">Elige un producto y busca proveedores web nacionales/internacionales, o mira candidatos según el último producto cotizado.</p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalBuscarProveedor">
                            <i class="fas fa-search me-1"></i> Buscar proveedor de producto
                        </button>
                        <a href="{{ route('admin.tareas.ultimoCotizado') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-clock me-1"></i> Último producto cotizado
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($puede['cotizaciones'])
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-user-tie fa-2x me-3" style="color:#0dcaf0;"></i>
                        <h5 class="fw-bold mb-0">Buscar clientes</h5>
                    </div>
                    <p class="text-muted small flex-grow-1">Encuentra clientes a partir de tu Outlook o de los usuarios registrados, elige un producto por categoría/subcategoría y crea la cotización.</p>
                    <a href="{{ route('admin.tareas.clientes') }}" class="btn btn-info">
                        <i class="fas fa-users me-1"></i> Buscar clientes
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>

    @if($puede['proveedores'] && $puede['productos'])
    {{-- ===== MODAL BUSCAR PROVEEDOR POR PRODUCTO ===== --}}
    <div class="modal fade" id="modalBuscarProveedor" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-globe me-2"></i> Buscar proveedor de producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Categoría</label>
                            <select id="bp-categoria" class="form-select">
                                <option value="">-- Elegir --</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Subcategoría</label>
                            <select id="bp-subcategoria" class="form-select" disabled>
                                <option value="">-- Primero categoría --</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Producto</label>
                            <select id="bp-producto" class="form-select" disabled>
                                <option value="">-- Primero subcategoría --</option>
                            </select>
                        </div>
                    </div>

                    <div id="bp-preview" class="d-none mb-3">
                        <div class="card border-primary">
                            <div class="card-body d-flex align-items-center">
                                <img id="bp-img" src="" alt="" class="me-3 rounded" style="width:70px;height:70px;object-fit:cover;">
                                <div class="flex-grow-1">
                                    <strong id="bp-nombre" class="d-block"></strong>
                                    <span id="bp-precio" class="text-muted small"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center p-3 border rounded bg-light">
                        <div class="small text-muted mb-2">Selecciona un producto y elige dónde buscar el proveedor</div>
                        <div id="bp-botones" class="d-none d-flex flex-wrap justify-content-center gap-2">
                            <a id="bp-search-ml" href="#" target="_blank" class="btn btn-sm btn-outline-danger">Mercado Libre</a>
                            <a id="bp-search-google" href="#" target="_blank" class="btn btn-sm btn-outline-primary">Google</a>
                            <a id="bp-search-alibaba" href="#" target="_blank" class="btn btn-sm btn-outline-warning">Alibaba</a>
                            <a id="bp-search-made" href="#" target="_blank" class="btn btn-sm btn-outline-success">Made in China</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const catSel = document.getElementById('bp-categoria');
        const subSel = document.getElementById('bp-subcategoria');
        const prodSel = document.getElementById('bp-producto');
        const preview = document.getElementById('bp-preview');
        const botones = document.getElementById('bp-botones');

        // Cargar categorías
        fetch('{{ route('admin.tareas.categoriasJson') }}')
            .then(r => r.json())
            .then(data => {
                data.forEach(c => {
                    const o = document.createElement('option');
                    o.value = c.id;
                    o.textContent = c.categoria;
                    catSel.appendChild(o);
                });
            }).catch(() => {});

        catSel.addEventListener('change', function () {
            subSel.innerHTML = '<option value="">-- Cargando... --</option>';
            subSel.disabled = false;
            prodSel.innerHTML = '<option value="">-- Primero subcategoría --</option>';
            prodSel.disabled = true;
            preview.classList.add('d-none');
            botones.classList.add('d-none');
            if (!this.value) return;
            fetch('{{ route('admin.tareas.subcategorias', ['categoria' => '__ID__']) }}'.replace('__ID__', this.value))
                .then(r => r.json())
                .then(data => {
                    subSel.innerHTML = '<option value="">-- Elegir --</option>';
                    data.forEach(s => {
                        const o = document.createElement('option');
                        o.value = s.id;
                        o.textContent = s.nombre;
                        subSel.appendChild(o);
                    });
                });
        });

        subSel.addEventListener('change', function () {
            prodSel.innerHTML = '<option value="">-- Cargando... --</option>';
            prodSel.disabled = false;
            preview.classList.add('d-none');
            botones.classList.add('d-none');
            if (!this.value) return;
            fetch('{{ route('admin.tareas.productos', ['subcategoria' => '__ID__']) }}'.replace('__ID__', this.value))
                .then(r => r.json())
                .then(data => {
                    prodSel.innerHTML = '<option value="">-- Elegir producto --</option>';
                    data.forEach(p => {
                        const o = document.createElement('option');
                        o.value = p.id;
                        o.dataset.nombre = p.titulo;
                        o.dataset.precio = p.precio;
                        o.dataset.portada = p.portada;
                        o.textContent = p.titulo + (p.precio ? ' — S/ ' + p.precio : '');
                        prodSel.appendChild(o);
                    });
                });
        });

        prodSel.addEventListener('change', function () {
            const opt = this.selectedOptions[0];
            if (!opt || !opt.value) { preview.classList.add('d-none'); botones.classList.add('d-none'); return; }
            const nombre = opt.dataset.nombre || '';
            const q = encodeURIComponent(nombre);
            document.getElementById('bp-nombre').textContent = nombre;
            document.getElementById('bp-precio').textContent = opt.dataset.precio ? 'S/ ' + opt.dataset.precio : '';
            const img = document.getElementById('bp-img');
            if (opt.dataset.portada) {
                img.src = '{{ asset('storage') }}/' + opt.dataset.portada;
            }
            preview.classList.remove('d-none');
            document.getElementById('bp-search-ml').href = 'https://listado.mercadolibre.com.pe/' + q;
            document.getElementById('bp-search-google').href = 'https://www.google.com/search?q=' + q + '%20proveedor%20mayorista';
            document.getElementById('bp-search-alibaba').href = 'https://www.alibaba.com/trade/search?fsb=y&IndexArea=product_en&keywords=' + q;
            document.getElementById('bp-search-made').href = 'https://www.made-in-china.com/products-search/hot-china-products/' + q + '.html';
            botones.classList.remove('d-none');
        });
    });
</script>
@endpush