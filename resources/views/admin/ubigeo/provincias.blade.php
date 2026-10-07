@extends('layouts.admin')

@section('title', 'Provincias')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold"><i class="fas fa-map-location-dot me-2"></i>Provincias</h3>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalProvNuevo">
            <i class="fas fa-plus me-1"></i> Nueva Provincia
        </button>
    </div>

    @include('admin.ubigeo._alerts')

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.ubigeo.provincias') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">País</label>
                    <select name="pais_id" id="filtroProvPais" class="form-select" onchange="this.form.submit()">
                        <option value="">Todos los países</option>
                        @foreach($paises as $pais)
                            <option value="{{ $pais->id }}" @selected(request('pais_id') == $pais->id)>{{ $pais->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Departamento</label>
                    <select name="departamento_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Todos los departamentos</option>
                        @foreach($departamentos as $depto)
                            <option value="{{ $depto->id }}" @selected(request('departamento_id') == $depto->id)>{{ $depto->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="{{ request('nombre') }}" placeholder="Buscar provincia...">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-secondary w-100"><i class="fas fa-search me-1"></i> Buscar</button>
                </div>
                <div class="col-md-1">
                    <a href="{{ route('admin.ubigeo.provincias') }}" class="btn btn-outline-secondary w-100" title="Limpiar"><i class="fas fa-undo"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>País</th>
                            <th>Departamento</th>
                            <th>Nombre</th>
                            <th>Distritos</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($provincias as $prov)
                        <tr>
                            <td>{{ $prov->id }}</td>
                            <td>{{ $prov->departamento->pais->nombre ?? '—' }}</td>
                            <td>{{ $prov->departamento->nombre ?? '—' }}</td>
                            <td>{{ $prov->nombre }}</td>
                            <td>{{ $prov->distritos_count }}</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-warning btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalProvEditar"
                                        data-id="{{ $prov->id }}"
                                        data-pais="{{ $prov->departamento->pais_id ?? '' }}"
                                        data-departamento="{{ $prov->departamento_id }}"
                                        data-nombre="{{ $prov->nombre }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm"
                                        data-url="{{ route('admin.ubigeo.provincias.destroy', $prov) }}"
                                        onclick="eliminarUbigeo(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No hay provincias registradas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $provincias->links() }}
    </div>
</div>

{{-- Modal Nuevo --}}
<div class="modal fade" id="modalProvNuevo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.ubigeo.provincias.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Nueva Provincia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">País</label>
                <select id="provNuevoPais" class="form-select" required>
                    <option value="">Seleccione un país</option>
                    @foreach($paises as $pais)
                        <option value="{{ $pais->id }}">{{ $pais->nombre }}</option>
                    @endforeach
                </select>
                <label class="form-label mt-3">Departamento</label>
                <select name="departamento_id" id="provNuevoDepto" class="form-select" required>
                    <option value="">Primero seleccione el país</option>
                </select>
                <label class="form-label mt-3">Nombre</label>
                <input type="text" name="nombre" class="form-control" required placeholder="Ej: Lima">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Guardar</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Editar --}}
<div class="modal fade" id="modalProvEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="formProvEditar" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Editar Provincia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">País</label>
                <select name="pais_id" id="provEditarPais" class="form-select" required>
                    <option value="">Seleccione un país</option>
                    @foreach($paises as $pais)
                        <option value="{{ $pais->id }}">{{ $pais->nombre }}</option>
                    @endforeach
                </select>
                <label class="form-label mt-3">Departamento</label>
                <select name="departamento_id" id="provEditarDepto" class="form-select" required>
                    <option value="">Seleccione un departamento</option>
                </select>
                <label class="form-label mt-3">Nombre</label>
                <input type="text" name="nombre" id="provEditarNombre" class="form-control" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Actualizar</button>
            </div>
        </form>
    </div>
</div>

{{-- Form oculto para eliminar (evita formularios anidados) --}}
<form method="POST" id="formUbigeoDestroy" style="display:none">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    var urlAjaxDeptos = "{{ route('admin.ubigeo.departamentos.ajax', ['pais' => '__PAIS__']) }}";

    function cargarDepartamentosEn(select, paisId, seleccionado) {
        select.innerHTML = '<option value="">Cargando...</option>';
        fetch(urlAjaxDeptos.replace('__PAIS__', paisId))
            .then(r => r.json())
            .then(data => {
                select.innerHTML = '<option value="">Seleccione un departamento</option>';
                data.forEach(d => {
                    var op = document.createElement('option');
                    op.value = d.id;
                    op.textContent = d.nombre;
                    if (String(d.id) === String(seleccionado)) op.selected = true;
                    select.appendChild(op);
                });
            })
            .catch(() => { select.innerHTML = '<option value="">Error al cargar</option>'; });
    }

    // Modal nuevo
    document.getElementById('provNuevoPais').addEventListener('change', function() {
        if (this.value) cargarDepartamentosEn(document.getElementById('provNuevoDepto'), this.value, null);
        else document.getElementById('provNuevoDepto').innerHTML = '<option value="">Primero seleccione el país</option>';
    });

    // Modal editar
    document.querySelectorAll('[data-bs-target="#modalProvEditar"]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var base = "{{ route('admin.ubigeo.provincias.update', ['provincia' => '__ID__']) }}";
            document.getElementById('formProvEditar').action = base.replace('__ID__', this.dataset.id);
            document.getElementById('provEditarNombre').value = this.dataset.nombre;
            document.getElementById('provEditarPais').value = this.dataset.pais;
            cargarDepartamentosEn(document.getElementById('provEditarDepto'), this.dataset.pais, this.dataset.departamento);
        });
    });

    document.getElementById('provEditarPais').addEventListener('change', function() {
        cargarDepartamentosEn(document.getElementById('provEditarDepto'), this.value, null);
    });

    function eliminarUbigeo(btn) {
        if (!confirm('¿Eliminar esta provincia?')) return;
        var f = document.getElementById('formUbigeoDestroy');
        f.action = btn.dataset.url;
        f.submit();
    }
</script>
@endpush