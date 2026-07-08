@extends('layouts.admin')

@section('title', 'Marcas')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Marcas</h3>
        <a href="{{ route('admin.marcas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nueva Marca
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
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($marcas as $marca)
                        <tr>
                            <td><input type="checkbox" class="checkItem" value="{{ $marca->id }}"></td>
                            <td>{{ $marca->id }}</td>
                            <td>{{ $marca->nombre }}</td>
                            <td>
                                <a href="{{ route('admin.marcas.edit', $marca) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.marcas.destroy', $marca) }}" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No hay marcas registradas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $marcas->links() }}
    </div>
</div>

<meta name="marcas-eliminar-url" content="{{ route('admin.marcas.eliminarMultiple') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
