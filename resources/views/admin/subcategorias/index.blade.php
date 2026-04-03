@extends('admin')

@section('title', 'Subcategorías')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3>Subcategorías</h3>
    <a href="{{ route('admin.subcategorias.create') }}" class="btn btn-primary">Nueva</a>
</div>

<button id="btnEliminar" class="btn btn-danger mb-3" disabled>
    Eliminar seleccionados
</button>

<table class="table table-bordered">
    <thead>
        <tr>
            <th><input type="checkbox" id="checkAll"></th>
            <th>ID</th>
            <th>Nombre</th>
            <th>Categoría</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($subcategorias as $s)
        <tr>
            <td><input type="checkbox" class="checkItem" value="{{ $s->id }}"></td>
            <td>{{ $s->id }}</td>
            <td>{{ $s->nombre }}</td>
            <td>{{ $s->categoria->nombre }}</td>
            <td>
                <a href="{{ route('admin.subcategorias.edit', $s) }}" class="btn btn-sm btn-warning">
                    Editar
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $subcategorias->links() }}

<meta name="subcategorias-eliminar-url" content="{{ route('admin.subcategorias.eliminarMultiple') }}">
@endsection
