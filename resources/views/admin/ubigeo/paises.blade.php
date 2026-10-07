@extends('layouts.admin')

@section('title', 'Países')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold"><i class="fas fa-globe me-2"></i>Países</h3>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPaisNuevo">
            <i class="fas fa-plus me-1"></i> Nuevo País
        </button>
    </div>

    @include('admin.ubigeo._alerts')

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Departamentos</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paises as $pais)
                        <tr>
                            <td>{{ $pais->id }}</td>
                            <td>{{ $pais->nombre }}</td>
                            <td>{{ $pais->departamentos_count }}</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-warning btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalPaisEditar"
                                        data-id="{{ $pais->id }}"
                                        data-nombre="{{ $pais->nombre }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm"
                                        data-url="{{ route('admin.ubigeo.paises.destroy', $pais) }}"
                                        onclick="eliminarUbigeo(this)"> 
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No hay países registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Nuevo --}}
<div class="modal fade" id="modalPaisNuevo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.ubigeo.paises.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Nuevo País</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" required placeholder="Ej: Perú">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Guardar</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Editar --}}
<div class="modal fade" id="modalPaisEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="formPaisEditar" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Editar País</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" id="inputPaisEditarNombre" class="form-control" required>
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
    document.querySelectorAll('[data-bs-target="#modalPaisEditar"]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var url = "{{ route('admin.ubigeo.paises.update', ['pais' => '__ID__']) }}".replace('__ID__', this.dataset.id);
            document.getElementById('formPaisEditar').action = url;
            document.getElementById('inputPaisEditarNombre').value = this.dataset.nombre;
        });
    });

    function eliminarUbigeo(btn) {
        if (!confirm('¿Eliminar este país?')) return;
        var f = document.getElementById('formUbigeoDestroy');
        f.action = btn.dataset.url;
        f.submit();
    }
</script>
@endpush