@extends('layouts.admin')

@section('title', 'Productos - EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Productos</h3>
        <a href="{{ route('admin.exim.productos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nuevo Producto
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
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Tipo</th>
                            <th>Nombre</th>
                            <th>Precio Base</th>
                            <th>Moneda</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productos as $producto)
                        <tr>
                            <td>{{ $producto->id }}</td>
                            <td><span class="badge bg-info text-capitalize">{{ str_replace('_', ' ', $producto->tipo) }}</span></td>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ number_format($producto->precio_base, 2) }}</td>
                            <td>{{ $producto->moneda->codigo ?? '—' }}</td>
                            <td>
                                @if($producto->estado)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.exim.productos.show', $producto) }}" class="btn btn-info btn-sm text-white">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.exim.productos.edit', $producto) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.exim.productos.destroy', $producto) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar producto?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No hay productos registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $productos->links() }}
    </div>
</div>
@endsection
