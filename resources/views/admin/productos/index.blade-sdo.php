<!-- @extends('layouts.admin') -->
@extends('layouts.volt')
@section('title', 'Listado de Productos')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Productos</h2>
        <a href="{{ route('admin.productos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Producto
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Título</th>
                    <th>Precio</th>
                    <th>Categoría</th>
                    <th>Subcategoría</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($productos as $producto)
                    <tr>
                        <td>{{ $producto->id }}</td>
                        <td>
                            @if($producto->portada)
                                <img src="{{ asset('storage/' . $producto->portada) }}" alt="Portada" width="60">
                            @else
                                <span class="text-muted">Sin imagen</span>
                            @endif
                        </td>
                        <td>{{ $producto->titulo }}</td>
                        <td>S/. {{ number_format($producto->precio, 2) }}</td>
                        <td>{{ $producto->categoria->nombre ?? '-' }}</td>
                        <td>{{ $producto->subcategoria->nombre ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $producto->estado ? 'success' : 'secondary' }}">
                                {{ $producto->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.productos.edit', $producto->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('admin.productos.destroy', $producto->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('¿Estás seguro de eliminar este producto?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No hay productos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $productos->links() }} <!-- Paginación -->
    </div>
</div>
@endsection
