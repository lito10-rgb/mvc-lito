@extends('layouts.admin')

@section('content')
<div class="container">

    <h3>Nuevo Contenido</h3>

    @if(session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data">

        @csrf

        <div class="mb-3">
            <label>Título</label>
            <input name="titulo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Contenido</label>
            <textarea name="contenido" class="form-control" rows="4"></textarea>
        </div>

        <div class="mb-3">
            <label>Imagen (opcional)</label>
            <input type="file" name="imagen" class="form-control" accept="image/*">
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
                        @foreach($users as $u)
                            <tr class="user-row"
                                data-id="{{ $u->id }}"
                                data-nombre="{{ strtolower($u->nombre . ' ' . $u->apellidos) }}"
                                data-empresa="{{ strtolower($u->profile->empresa ?? '') }}"
                                data-rubros="{{ $u->rubros->pluck('id')->join(',') }}"
                                data-roles="{{ $u->roles->pluck('id')->join(',') }}">
                                <td>
                                    <input type="checkbox" name="users[]" value="{{ $u->id }}" class="user-checkbox">
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
                    <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Guardar</button>
    </form>

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
        const visibleRows = document.querySelectorAll('.user-row[style*="display: none"]');
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
