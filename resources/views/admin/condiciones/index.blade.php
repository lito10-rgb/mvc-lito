@extends('layouts.admin')

@section('title', 'Condiciones Comerciales')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Condiciones Comerciales</h3>
        <a href="{{ route('admin.condiciones.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nueva Condición
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Contenido</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($condiciones as $c)
                    <tr>
                        <td>{{ $c->id }}</td>
                        <td>{{ $c->titulo }}</td>
                        <td>{{ Str::limit($c->contenido, 80) }}</td>
                        <td>
                            @if($c->activo)
                                <span class="badge bg-success">Sí</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.condiciones.edit', $c) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.condiciones.destroy', $c) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Sin condiciones registradas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $condiciones->links() }}</div>
</div>
@endsection
