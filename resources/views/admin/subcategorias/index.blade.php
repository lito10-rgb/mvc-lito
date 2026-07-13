@extends('layouts.admin')

@section('title', 'Subcategorías')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Subcategorías</h3>
        <a href="{{ route('admin.subcategorias.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nueva Subcategoría
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
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
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <button id="btnEliminar" class="btn btn-danger" disabled>
                    <i class="fas fa-trash me-1"></i> Eliminar seleccionados
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th><input type="checkbox" id="checkAll"></th>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Negocios</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subcategorias as $s)
                        <tr>
                            <td><input type="checkbox" class="checkItem" value="{{ $s->id }}"></td>
                            <td>{{ $s->id }}</td>
                            <td>{{ $s->subcategoria }}</td>
                            <td>{{ $s->categoria->categoria ?? '' }}</td>
                            <td>
                                @foreach($s->negocios as $neg)
                                    <span class="badge bg-info">{{ $neg->nombre }}</span>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('admin.subcategorias.edit', $s) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.subcategorias.destroy', $s) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta subcategoría?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No hay subcategorías registradas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $subcategorias->links() }}
    </div>
</div>

<meta name="subcategorias-eliminar-url" content="{{ route('admin.subcategorias.eliminarMultiple') }}">
@endsection
