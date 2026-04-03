<!-- resources/views/admin/categorias/index.blade.php -->
@extends('admin.layout')
@section('content')
<div class="container py-4">
    <h1>Categorías</h1>
    <a href="{{ route('admin.categorias.create') }}" class="btn btn-primary mb-3">Nueva Categoría</a>

    <form id="delete-multiple-form">
        @csrf
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><input type="checkbox" id="check-all"></th>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categorias as $categoria)
                <tr>
                    <td><input type="checkbox" class="item-check" value="{{ $categoria->id }}"></td>
                    <td>{{ $categoria->id }}</td>
                    <td>{{ $categoria->nombre }}</td>
                    <td>
                        <a href="{{ route('admin.categorias.edit', $categoria) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('admin.categorias.destroy', $categoria) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button id="delete-selected" class="btn btn-danger mt-2">Eliminar Seleccionados</button>
    </form>

    {{ $categorias->links() }}
</div>
@endsection
