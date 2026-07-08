@extends('layouts.admin')

@section('title', 'Proveedores')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Proveedores</h3>
        <a href="{{ route('admin.proveedores.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nuevo Proveedor
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
                <button id="btnEliminarProveedores" class="btn btn-danger" disabled>
                    <i class="fas fa-trash me-1"></i> Eliminar seleccionados
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th><input type="checkbox" id="checkAllProveedores"></th>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>RUC</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proveedores as $p)
                        <tr>
                            <td><input type="checkbox" class="checkProveedor" value="{{ $p->id }}"></td>
                            <td>{{ $p->id }}</td>
                            <td>{{ $p->nombre }}</td>
                            <td>{{ $p->ruc }}</td>
                            <td>{{ $p->telefono }}</td>
                            <td>{{ $p->email }}</td>
                            <td>
                                <a href="{{ route('admin.proveedores.edit', $p) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.proveedores.destroy', $p) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar proveedor?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No hay proveedores registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $proveedores->links() }}
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="proveedores-eliminar-url" content="{{ route('admin.proveedores.eliminarMultiple') }}">
@endsection
