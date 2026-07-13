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

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
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
                    <tr>
                        <td>{{ $categoria->id }}</td>
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
                        <td colspan="7" class="text-center text-muted py-4">No hay categorías registradas</td>
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
@endsection
