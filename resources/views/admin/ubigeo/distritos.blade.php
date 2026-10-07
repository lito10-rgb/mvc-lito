@extends('layouts.admin')

@section('title', 'Distritos')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold"><i class="fas fa-map-pin me-2"></i>Distritos</h3>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalDistNuevo">
            <i class="fas fa-plus me-1"></i> Nuevo Distrito
        </button>
    </div>

    @include('admin.ubigeo._alerts')

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.ubigeo.distritos') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">País</label>
                    <select name="pais_id" id="filtroDistPais" class="form-select" onchange="this.form.submit()">
                        <option value="">Todos los países</option>
                        @foreach($paises as $pais)
                            <option value="{{ $pais->id }}" @selected(request('pais_id') == $pais->id)>{{ $pais->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Departamento</label>
                    <select name="departamento_id" id="filtroDistDepto" class="form-select" onchange="this.form.submit()">
                        <option value="">Todos los departamentos</option>
                        @foreach($departamentos as $depto)
                            <option value="{{ $depto->id }}" @selected(request('departamento_id') == $depto->id)>{{ $depto->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Provincia</label>
                    <select name="provincia_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Todas las provincias</option>
                        @foreach($provincias as $prov)
                            <option value="{{ $prov->id }}" @selected(request('provincia_id') == $prov->id)>{{ $prov->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="{{ request('nombre') }}" placeholder="Buscar distrito...">
                </div>
                <div class="col-md-1">
                    <button class="btn btn-secondary w-100"><i class="fas fa-search"></i></button>
                    <a href="{{ route('admin.ubigeo.distritos') }}" class="btn btn-outline-secondary w-100 mt-1" title="Limpiar"><i class="fas fa-undo"></i></a>
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
                            <th>Provincia</th>
                            <th>Nombre</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($distritos as $dist)
                        <tr>
                            <td>{{ $dist->id }}</td>
                            <td>{{ $dist->provincia->departamento->pais->nombre ?? '—' }}</td>
                            <td>{{ $dist->provincia->departamento->nombre ?? '—' }}</td>
                            <td>{{ $dist->provincia->nombre ?? '—' }}</td>
                            <td>{{ $dist->nombre }}</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-warning btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalDistEditar"
                                        data-id="{{ $dist->id }}"
                                        data-pais="{{ $dist->provincia->departamento->pais_id ?? '' }}"
                                        data-departamento="{{ $dist->provincia->departamento_id ?? '' }}"
                                        data-provincia="{{ $dist->provincia_id }}"
                                        data-nombre="{{ $dist->nombre }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm"
                                        data-url="{{ route('admin.ubigeo.distritos.destroy', $dist) }}"
                                        onclick="eliminarUbigeo(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No hay distritos registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $distritos->links() }}
    </div>
</div>

{{-- Modal Nuevo --}}
<div class="modal fade" id="modalDistNuevo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.ubigeo.distritos.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Distrito</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">País</label>
                <select id="distNuevoPais" class="form-select" required>
                    <option value="">Seleccione un país</option>
                    @foreach($paises as $pais)
                        <option value="{{ $pais->id }}">{{ $pais->nombre }}</option>
                    @endforeach
                </select>
                <label class="form-label mt-3">Departamento</label>
                <select id="distNuevoDepto" class="form-select" required>
                    <option value="">Primero seleccione el país</option>
                </select>
                <label class="form-label mt-3">Provincia</label>
                <select name="provincia_id" id="distNuevoProv" class="form-select" required>
                    <option value="">Primero seleccione el departamento</option>
                </select>
                <label class="form-label mt-3">Nombre</label>
                <input type="text" name="nombre" class="form-control" required placeholder="Ej: Miraflores">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Guardar</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Editar --}}
<div class="modal fade" id="modalDistEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="formDistEditar" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Editar Distrito</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">País</label>
                <select name="pais_id" id="distEditarPais" class="form-select" required>
                    <option value="">Seleccione un país</option>
                    @foreach($paises as $pais)
                        <option value="{{ $pais->id }}">{{ $pais->nombre }}</option>
                    @endforeach
                </select>
                <label class="form-label mt-3">Departamento</label>
                <select name="departamento_id" id="distEditarDepto" class="form-select" required>
                    <option value="">Seleccione un departamento</option>
                </select>
                <label class="form-label mt-3">Provincia</label>
                <select name="provincia_id" id="distEditarProv" class="form-select" required>
                    <option value="">Seleccione una provincia</option>
                </select>
                <label class="form-label mt-3">Nombre</label>
                <input type="text" name="nombre" id="distEditarNombre" class="form-control" required>
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
    var urlAjaxProvs = "{{ route('admin.ubigeo.provincias.ajax', ['departamento' => '__DEPT__']) }}";

    function cargarOpciones(url, select, seleccionado, placeholder, callback) {
        select.innerHTML = '<option value="">Cargando...</option>';
        fetch(url)
            .then(r => r.json())
            .then(data => {
                select.innerHTML = '<option value="">' + placeholder + '</option>';
                data.forEach(d => {
                    var op = document.createElement('option');
                    op.value = d.id;
                    op.textContent = d.nombre;
                    if (String(d.id) === String(seleccionado)) op.selected = true;
                    select.appendChild(op);
                });
                if (callback) callback();
            })
            .catch(() => { select.innerHTML = '<option value="">Error al cargar</option>'; });
    }

    // Modal nuevo
    document.getElementById('distNuevoPais').addEventListener('change', function() {
        var depto = document.getElementById('distNuevoDepto');
        var prov = document.getElementById('distNuevoProv');
        prov.innerHTML = '<option value="">Primero seleccione el departamento</option>';
        if (this.value) cargarOpciones(urlAjaxDeptos.replace('__PAIS__', this.value), depto, null, 'Seleccione un departamento');
        else depto.innerHTML = '<option value="">Primero seleccione el país</option>';
    });

    document.getElementById('distNuevoDepto').addEventListener('change', function() {
        var prov = document.getElementById('distNuevoProv');
        if (this.value) cargarOpciones(urlAjaxProvs.replace('__DEPT__', this.value), prov, null, 'Seleccione una provincia');
        else prov.innerHTML = '<option value="">Primero seleccione el departamento</option>';
    });

    // Modal editar
    document.querySelectorAll('[data-bs-target="#modalDistEditar"]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var base = "{{ route('admin.ubigeo.distritos.update', ['distrito' => '__ID__']) }}";
            document.getElementById('formDistEditar').action = base.replace('__ID__', this.dataset.id);
            document.getElementById('distEditarNombre').value = this.dataset.nombre;
            document.getElementById('distEditarPais').value = this.dataset.pais;
            cargarOpciones(
                urlAjaxDeptos.replace('__PAIS__', this.dataset.pais),
                document.getElementById('distEditarDepto'),
                this.dataset.departamento,
                'Seleccione un departamento',
                function() {
                    if (btn.dataset.departamento) {
                        cargarOpciones(
                            urlAjaxProvs.replace('__DEPT__', btn.dataset.departamento),
                            document.getElementById('distEditarProv'),
                            btn.dataset.provincia,
                            'Seleccione una provincia'
                        );
                    }
                }
            );
        });
    });

    document.getElementById('distEditarPais').addEventListener('change', function() {
        var depto = document.getElementById('distEditarDepto');
        var prov = document.getElementById('distEditarProv');
        prov.innerHTML = '<option value="">Seleccione una provincia</option>';
        cargarOpciones(urlAjaxDeptos.replace('__PAIS__', this.value), depto, null, 'Seleccione un departamento');
    });

    document.getElementById('distEditarDepto').addEventListener('change', function() {
        var prov = document.getElementById('distEditarProv');
        if (this.value) cargarOpciones(urlAjaxProvs.replace('__DEPT__', this.value), prov, null, 'Seleccione una provincia');
        else prov.innerHTML = '<option value="">Seleccione una provincia</option>';
    });

    function eliminarUbigeo(btn) {
        if (!confirm('¿Eliminar este distrito?')) return;
        var f = document.getElementById('formUbigeoDestroy');
        f.action = btn.dataset.url;
        f.submit();
    }
</script>
@endpush