@extends('layouts.admin')

@section('title', 'Buscar clientes')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0"><i class="fas fa-user-tie me-2 text-info"></i> Buscar clientes</h3>
        <a href="{{ route('admin.tareas.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Tareas
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Buscador de producto por categoría/subcategoría --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-info bg-opacity-25 fw-bold">
            <i class="fas fa-box-open me-2"></i> Buscar producto para cotizar
            <span class="text-muted small ms-2">elígelo por categoría y subcategoría</span>
        </div>
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Categoría</label>
                    <select id="cli-categoria" class="form-select">
                        <option value="">-- Elegir --</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->categoria }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Subcategoría</label>
                    <select id="cli-subcategoria" class="form-select" disabled>
                        <option value="">-- Primero categoría --</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Producto</label>
                    <select id="cli-producto" class="form-select" disabled>
                        <option value="">-- Primero subcategoría --</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-info w-100" id="cli-cotizar-producto" disabled>
                        <i class="fas fa-file-invoice-dollar me-1"></i> Cotizar
                    </button>
                </div>
            </div>
            <div id="cli-preview" class="d-none mt-3">
                <div class="border rounded p-2 bg-light d-flex align-items-center">
                    <img id="cli-img" src="" alt="" class="me-3 rounded" style="width:50px;height:50px;object-fit:cover;">
                    <div class="flex-grow-1">
                        <strong id="cli-nombre" class="d-block"></strong>
                        <span id="cli-precio" class="text-muted small"></span>
                        <span class="text-muted small" id="cli-cliente-info"></span>
                    </div>
                    <button type="button" class="btn btn-outline-success btn-sm" id="cli-cotizar-a-seleccionado" disabled>
                        <i class="fas fa-user-check me-1"></i> Cotizar al cliente seleccionado
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de clientes --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header fw-bold">
            <i class="fas fa-users me-2"></i> Clientes potenciales
            <span class="badge bg-dark ms-1">{{ count($clientes) }}</span>
            <div class="float-end">
                <div class="form-check form-switch d-inline-block me-3">
                    <input class="form-check-input" type="checkbox" id="cli-solo-outlook">
                    <label class="form-check-label small" for="cli-solo-outlook">Solo Outlook 🔖</label>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Cliente</th>
                            <th>Origen</th>
                            <th>Rol / Asunto</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clientes as $cliente)
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input cli-radio" type="radio" name="cli-seleccionado"
                                           value="{{ $cliente['email'] }}" id="cli-radio-{{ md5($cliente['email']) }}"
                                           data-nombre="{{ $cliente['nombre'] }}">
                                    <label class="form-check-label" for="cli-radio-{{ md5($cliente['email']) }}">
                                        <span class="fw-bold">{{ $cliente['nombre'] }}</span>
                                        <span class="text-muted small d-block">{{ $cliente['email'] }}</span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                @if($cliente['origen'] === 'registrado')
                                    <span class="badge bg-success">Registrado</span>
                                @elseif($cliente['origen'] === 'ambos')
                                    <span class="badge bg-primary">Ambos</span>
                                @else
                                    <span class="badge bg-warning text-dark">Outlook</span>
                                @endif
                                @if($cliente['bandera'])
                                    <span class="badge bg-danger">🔖 bandera</span>
                                @endif
                            </td>
                            <td class="small text-muted">
                                @if($cliente['roles'])
                                    {{ $cliente['roles'] }}
                                @else
                                    {{ Str::limit($cliente['asunto'], 60) }}
                                @endif
                            </td>
                            <td class="small">{{ $cliente['fecha'] }}</td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <a href="{{ route('admin.cotizaciones.create', [
                                        'cliente' => $cliente['nombre'],
                                        'correo'  => $cliente['email'],
                                    ]) }}" class="btn btn-sm btn-info" title="Crear cotización">
                                        <i class="fas fa-file-invoice-dollar me-1"></i> Cotizar
                                    </a>
                                    <a href="mailto:{{ $cliente['email'] }}" class="btn btn-sm btn-outline-primary" title="Escribir">
                                        <i class="fas fa-paper-plane me-1"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No se encontraron clientes potenciales.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const catSel = document.getElementById('cli-categoria');
    const subSel = document.getElementById('cli-subcategoria');
    const prodSel = document.getElementById('cli-producto');
    const btnCotizar = document.getElementById('cli-cotizar-producto');
    const preview = document.getElementById('cli-preview');
    const btnCotizarSel = document.getElementById('cli-cotizar-a-seleccionado');
    let productoActual = null;

    // Selección de cliente
    document.querySelectorAll('.cli-radio').forEach(r => {
        r.addEventListener('change', function () {
            if (preview.classList.contains('d-none') === false) {
                document.getElementById('cli-cliente-info').textContent =
                    'Cliente: ' + (this.dataset.nombre || this.value);
                btnCotizarSel.disabled = false;
            }
        });
    });

    catSel.addEventListener('change', function () {
        subSel.innerHTML = '<option value="">-- Cargando... --</option>';
        subSel.disabled = false;
        prodSel.innerHTML = '<option value="">-- Primero subcategoría --</option>';
        prodSel.disabled = true;
        btnCotizar.disabled = true;
        preview.classList.add('d-none');
        productoActual = null;
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
        btnCotizar.disabled = true;
        preview.classList.add('d-none');
        productoActual = null;
        if (!this.value) return;
        fetch('{{ route('admin.tareas.productos', ['subcategoria' => '__ID__']) }}'.replace('__ID__', this.value))
            .then(r => r.json())
            .then(data => {
                prodSel.innerHTML = '<option value="">-- Elegir producto --</option>';
                if (data.length === 0) {
                    prodSel.append(new Option('Sin productos', ''));
                }
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
        if (!opt || !opt.value) { btnCotizar.disabled = true; preview.classList.add('d-none'); productoActual = null; return; }
        productoActual = { id: opt.value, nombre: opt.dataset.nombre, precio: opt.dataset.precio, portada: opt.dataset.portada };
        document.getElementById('cli-nombre').textContent = opt.dataset.nombre;
        document.getElementById('cli-precio').textContent = opt.dataset.precio ? 'S/ ' + opt.dataset.precio : '';
        const img = document.getElementById('cli-img');
        if (opt.dataset.portada) { img.src = '{{ asset('storage') }}/' + opt.dataset.portada; }
        const selCliente = document.querySelector('.cli-radio:checked');
        document.getElementById('cli-cliente-info').textContent = selCliente ? 'Cliente: ' + selCliente.dataset.nombre : 'Elige un cliente de la tabla';
        btnCotizarSel.disabled = !selCliente;
        preview.classList.remove('d-none');
        btnCotizar.disabled = false;
    });

    // Cotizar producto (sin cliente seleccionado)
    btnCotizar.addEventListener('click', function () {
        if (!productoActual) return;
        window.location.href = '{{ route('admin.cotizaciones.create') }}' +
            '?cliente=&producto_id=' + productoActual.id + '&producto_nombre=' + encodeURIComponent(productoActual.nombre);
    });

    // Cotizar producto al cliente seleccionado
    btnCotizarSel.addEventListener('click', function () {
        const sel = document.querySelector('.cli-radio:checked');
        if (!sel || !productoActual) return;
        window.location.href = '{{ route('admin.cotizaciones.create') }}' +
            '?cliente=' + encodeURIComponent(sel.dataset.nombre) +
            '&correo=' + encodeURIComponent(sel.value) +
            '&producto_id=' + productoActual.id +
            '&producto_nombre=' + encodeURIComponent(productoActual.nombre);
    });

    // Filtro solo Outlook
    document.getElementById('cli-solo-outlook').addEventListener('change', function () {
        const filas = document.querySelectorAll('tbody tr');
        filas.forEach(tr => {
            const badge = tr.querySelector('td:nth-child(2) .badge');
            if (!badge) return;
            const esOutlook = badge.classList.contains('bg-warning') || badge.classList.contains('bg-primary');
            tr.style.display = this.checked && !esOutlook ? 'none' : '';
        });
    });
</script>
@endpush