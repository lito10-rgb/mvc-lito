@extends('layouts.volt')
@section('title', 'Listado de Productos')
@push('styles')
<style>
    .table img {
        max-width: 60px;
        height: auto;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="card border-0 shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Productos</h5>
            <a href="{{ route('admin.productos.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo Producto
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <!-- <div class="mb-3">
                <button id="btnEliminarSeleccionados" class="btn btn-danger btn-sm" disabled>
                    <i class="bi bi-trash"></i> Eliminar seleccionados
                </button>
            </div> -->

            <div class="table-responsive">
<table class="table table-hover align-middle text-sm">
    <thead class="table-dark text-center">
        <tr>
            <th>
                <input type="checkbox" id="checkAll">
            </th>
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

    <tbody class="text-center">
        @forelse ($productos as $producto)
            <tr>
                <td>
                    <input type="checkbox" name="ids[]" class="checkItem" value="{{ $producto->id }}">
                </td>

                <td>{{ $producto->id }}</td>

                <td style="width:80px;">
                    @if($producto->portada)
                        <img src="{{ asset('storage/' . $producto->portada) }}"
                             alt="Imagen"
                             class="img-fluid rounded"
                             style="max-height:60px;">
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
                    <a href="{{ route('admin.productos.edit', $producto->id) }}"
                       class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil-square"></i>
                    </a>

                    <form action="{{ route('admin.productos.destroy', $producto->id) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('¿Eliminar este producto?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="9" class="text-center text-muted">
                    No hay productos registrados.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

            </div>
        <button id="btnEliminarSeleccionados" class="btn btn-danger" disabled>
            Eliminar seleccionados
        </button>

        <!-- Meta necesarias para JS -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="route-eliminar" content="{{ route('admin.productos.eliminarMultiple') }}">
            {{-- Paginador --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $productos->links() }}
            </div>
        </div>
    </div>
</div>
@endsection