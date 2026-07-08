@extends('layouts.admin')

@section('title', 'Editar Post')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Editar Post</h3>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Título</label>
                    <input type="text" name="titulo" class="form-control"
                           value="{{ $post->titulo }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Contenido</label>
                    <textarea name="contenido" class="form-control" rows="6">{{ $post->cuerpo }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Imagen</label>
                    @if($post->imagen)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $post->imagen) }}" alt="Imagen del post" style="max-height: 150px; max-width: 100%;">
                        </div>
                    @endif
                    <input type="file" name="imagen" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-control">
                        <option value="1" {{ $post->estado == 1 ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ $post->estado == 0 ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Compartir con usuarios</label>

                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <select id="filter-rubro" class="form-select form-select-sm">
                                        <option value="">Todos los rubros</option>
                                        @foreach($rubros as $r)
                                            <option value="{{ $r->id }}">{{ $r->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select id="filter-rol" class="form-select form-select-sm">
                                        <option value="">Todos los roles</option>
                                        @foreach($roles as $r)
                                            <option value="{{ $r->id }}">{{ $r->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input id="filter-nombre" type="text" class="form-control form-control-sm" placeholder="Buscar por nombre...">
                                </div>
                                <div class="col-md-3">
                                    <input id="filter-empresa" type="text" class="form-control form-control-sm" placeholder="Buscar por empresa...">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border rounded" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th style="width: 40px;">
                                        <input type="checkbox" id="select-all">
                                    </th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Empresa</th>
                                    <th>Rubro</th>
                                    <th>Rol</th>
                                </tr>
                            </thead>
                            <tbody id="user-table-body">
                                @php $selectedIds = $post->users->pluck('id')->toArray(); @endphp
                                @foreach($users as $u)
                                    <tr class="user-row"
                                        data-id="{{ $u->id }}"
                                        data-nombre="{{ strtolower($u->nombre . ' ' . $u->apellidos) }}"
                                        data-empresa="{{ strtolower($u->profile->empresa ?? '') }}"
                                        data-rubros="{{ $u->rubros->pluck('id')->join(',') }}"
                                        data-roles="{{ $u->roles->pluck('id')->join(',') }}">
                                        <td>
                                            <input type="checkbox" name="users[]" value="{{ $u->id }}" class="user-checkbox"
                                                {{ in_array($u->id, $selectedIds) ? 'checked' : '' }}>
                                        </td>
                                        <td>{{ $u->nombre }} {{ $u->apellidos }}</td>
                                        <td>{{ $u->email }}</td>
                                        <td>{{ $u->profile->empresa ?? '-' }}</td>
                                        <td>{{ $u->rubros->pluck('nombre')->implode(', ') ?: '-' }}</td>
                                        <td>{{ $u->roles->pluck('nombre')->implode(', ') ?: '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-1 text-muted small">
                        <span id="selected-count">0</span> usuarios seleccionados
                    </div>
                </div>

                <div class="mb-3">
                    <label>Plataformas</label>
                    <select name="platforms[]" class="form-control" multiple>
                        @foreach($platforms as $p)
                            <option value="{{ $p->id }}"
                                {{ $post->platforms->contains($p->id) ? 'selected' : '' }}>
                                {{ $p->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Actualizar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    const rows = document.querySelectorAll('.user-row');
    const checkboxes = document.querySelectorAll('.user-checkbox');
    const selectAll = document.getElementById('select-all');
    const selectedCount = document.getElementById('selected-count');

    const filterRubro = document.getElementById('filter-rubro');
    const filterRol = document.getElementById('filter-rol');
    const filterNombre = document.getElementById('filter-nombre');
    const filterEmpresa = document.getElementById('filter-empresa');

    function updateSelectedCount() {
        const checked = document.querySelectorAll('.user-checkbox:checked').length;
        selectedCount.textContent = checked;
    }

    function applyFilters() {
        const rubroVal = filterRubro.value;
        const rolVal = filterRol.value;
        const nombreVal = filterNombre.value.trim().toLowerCase();
        const empresaVal = filterEmpresa.value.trim().toLowerCase();

        rows.forEach(function(row) {
            const rubros = row.dataset.rubros.split(',').filter(Boolean);
            const roles = row.dataset.roles.split(',').filter(Boolean);
            const nombre = row.dataset.nombre;
            const empresa = row.dataset.empresa;

            const matchRubro = !rubroVal || rubros.includes(rubroVal);
            const matchRol = !rolVal || roles.includes(rolVal);
            const matchNombre = !nombreVal || nombre.includes(nombreVal);
            const matchEmpresa = !empresaVal || empresa.includes(empresaVal);

            row.style.display = (matchRubro && matchRol && matchNombre && matchEmpresa) ? '' : 'none';
        });
    }

    filterRubro.addEventListener('change', applyFilters);
    filterRol.addEventListener('change', applyFilters);
    filterNombre.addEventListener('input', applyFilters);
    filterEmpresa.addEventListener('input', applyFilters);

    selectAll.addEventListener('change', function() {
        rows.forEach(function(row) {
            if (row.style.display !== 'none') {
                const cb = row.querySelector('.user-checkbox');
                cb.checked = selectAll.checked;
            }
        });
        updateSelectedCount();
    });

    checkboxes.forEach(function(cb) {
        cb.addEventListener('change', function() {
            updateSelectedCount();
            const allVisible = document.querySelectorAll('.user-row:not([style*="display: none"]) .user-checkbox');
            const allChecked = document.querySelectorAll('.user-row:not([style*="display: none"]) .user-checkbox:checked');
            selectAll.checked = allVisible.length > 0 && allVisible.length === allChecked.length;
        });
    });

    updateSelectedCount();
})();
</script>
@endpush
