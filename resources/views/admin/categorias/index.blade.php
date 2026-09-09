@extends('layouts.volt')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Categorías</h3>
        <a href="{{ route('admin.categorias.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nueva Categoría
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <select name="negocio_id" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Todos los negocios</option>
                @foreach($negocios as $neg)
                    <option value="{{ $neg->id }}" {{ request('negocio_id', 1) == $neg->id ? 'selected' : '' }}>{{ $neg->nombre }}</option>
                @endforeach
            </select>
        </div>
    </form>

    <p class="text-muted small mb-2"><i class="fas fa-arrows-up-down"></i> Arrastra las filas para cambiar el orden en que aparecen en el menú del sitio.</p>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="tabla-categorias">
                <thead class="table-dark">
                    <tr>
                        <th style="width:40px"></th>
                        <th>Orden</th>
                        <th>Categoría</th>
                        <th>Ruta</th>
                        <th>Estado</th>
                        <th>Negocios</th>
                        <th>Subcategorías</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categorias as $categoria)
                    <tr data-id="{{ $categoria->id }}" style="cursor:grab;">
                        <td class="text-center text-muted drag-handle" style="vertical-align:middle;">
                            <i class="fas fa-grip-vertical"></i>
                        </td>
                        <td class="orden-cell" style="vertical-align:middle;">{{ $categoria->orden }}</td>
                        <td>{{ $categoria->categoria }}</td>
                        <td><code>{{ $categoria->ruta }}</code></td>
                        <td>
                            @if($categoria->estado)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            @foreach($categoria->negocios as $neg)
                                <span class="badge bg-info">{{ $neg->nombre }}</span>
                            @endforeach
                        </td>
                        <td>{{ $categoria->subcategorias->count() }}</td>
                        <td>
                            <a href="{{ route('admin.categorias.edit', $categoria) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.categorias.destroy', $categoria) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta categoría?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No hay categorías registradas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $categorias->links() }}
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var tbody = document.querySelector('#tabla-categorias tbody');
    if (!tbody) return;

    new Sortable(tbody, {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'table-active',
        onEnd: function () {
            var rows = tbody.querySelectorAll('tr[data-id]');
            var order = [];
            rows.forEach(function (row, idx) {
                var id = row.getAttribute('data-id');
                order.push({ id: id, orden: idx + 1 });
                row.querySelector('.orden-cell').textContent = idx + 1;
            });

            fetch('{{ route("admin.categorias.reorder") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ order: order })
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    var toast = document.createElement('div');
                    toast.className = 'position-fixed top-0 end-0 m-3 alert alert-success py-2 px-3 shadow';
                    toast.style.zIndex = 9999;
                    toast.textContent = 'Orden actualizado';
                    document.body.appendChild(toast);
                    setTimeout(function () { toast.remove(); }, 2000);
                }
            });
        }
    });
});
</script>
@endpush
@endsection
