@extends('layouts.admin')

@section('title', 'Rubros')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Rubros</h3>
        <a href="{{ route('admin.rubros.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nuevo Rubro
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <button id="btnEliminarSeleccionados" class="btn btn-danger" disabled>
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
                            <th>Usuarios</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rubros as $rubro)
                        <tr>
                            <td><input type="checkbox" class="checkItem" value="{{ $rubro->id }}"></td>
                            <td>{{ $rubro->id }}</td>
                            <td>{{ $rubro->nombre }}</td>
                            <td>{{ $rubro->users->count() }}</td>
                            <td>
                                <a href="{{ route('admin.rubros.edit', $rubro) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.rubros.destroy', $rubro) }}" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar rubro?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No hay rubros registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $rubros->links() }}
    </div>
</div>

<meta name="rubros-eliminar-url" content="{{ route('admin.rubros.eliminarMultiple') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
