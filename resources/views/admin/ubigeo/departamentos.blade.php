@extends('layouts.admin')

@section('title', 'Departamentos')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold"><i class="fas fa-building me-2"></i>Departamentos</h3>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalDeptoNuevo">
            <i class="fas fa-plus me-1"></i> Nuevo Departamento
        </button>
    </div>

    @include('admin.ubigeo._alerts')

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.ubigeo.departamentos') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">País</label>
                    <select name="pais_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Todos los países</option>
                        @foreach($paises as $pais)
                            <option value="{{ $pais->id }}" @selected(request('pais_id') == $pais->id)>{{ $pais->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="{{ request('nombre') }}" placeholder="Buscar departamento...">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-secondary w-100"><i class="fas fa-search me-1"></i> Buscar</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.ubigeo.departamentos') }}" class="btn btn-outline-secondary w-100"><i class="fas fa-undo me-1"></i> Limpiar</a>
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
                            <th>Nombre</th>
                            <th>Provincias</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departamentos as $depto)
                        <tr>
                            <td>{{ $depto->id }}</td>
                            <td>{{ $depto->pais->nombre ?? '—' }}</td>
                            <td>{{ $depto->nombre }}</td>
                            <td>{{ $depto->provincias_count }}</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-warning btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalDeptoEditar"
                                        data-id="{{ $depto->id }}"
                                        data-pais="{{ $depto->pais_id }}"
                                        data-nombre="{{ $depto->nombre }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm"
                                        data-url="{{ route('admin.ubigeo.departamentos.destroy', $depto) }}"
                                        onclick="eliminarUbigeo(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No hay departamentos registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $departamentos->links() }}
    </div>
</div>

{{-- Modal Nuevo --}}
<div class="modal fade" id="modalDeptoNuevo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.ubigeo.departamentos.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Departamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">País</label>
                <select name="pais_id" class="form-select" required>
                    <option value="">Seleccione un país</option>
                    @foreach($paises as $pais)
                        <option value="{{ $pais->id }}">{{ $pais->nombre }}</option>
                    @endforeach
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
<div class="modal fade" id="modalDeptoEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="formDeptoEditar" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Editar Departamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">País</label>
                <select name="pais_id" id="inputDeptoEditarPais" class="form-select" required>
                    <option value="">Seleccione un país</option>
                    @foreach($paises as $pais)
                        <option value="{{ $pais->id }}">{{ $pais->nombre }}</option>
                    @endforeach
                </select>
                <label class="form-label mt-3">Nombre</label>
                <input type="text" name="nombre" id="inputDeptoEditarNombre" class="form-control" required>
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
    document.querySelectorAll('[data-bs-target="#modalDeptoEditar"]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var url = "{{ route('admin.ubigeo.departamentos.update', ['departamento' => '__ID__']) }}".replace('__ID__', this.dataset.id);
            document.getElementById('formDeptoEditar').action = url;
            document.getElementById('inputDeptoEditarNombre').value = this.dataset.nombre;
            document.getElementById('inputDeptoEditarPais').value = this.dataset.pais;
        });
    });

    function eliminarUbigeo(btn) {
        if (!confirm('¿Eliminar este departamento?')) return;
        var f = document.getElementById('formUbigeoDestroy');
        f.action = btn.dataset.url;
        f.submit();
    }
</script>
@endpush