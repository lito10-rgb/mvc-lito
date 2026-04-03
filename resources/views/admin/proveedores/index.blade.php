@extends('admin.layouts.admin')

@section('title','Proveedores')

@section('content')
<div class="container py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Proveedores</h3>
        <a href="{{ route('admin.proveedores.create') }}" class="btn btn-primary">Nuevo proveedor</a>
    </div>

    <button id="btnEliminarProveedores" class="btn btn-danger mb-3" disabled>Eliminar seleccionados</button>

    <table class="table table-striped">
        <thead>
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
            @foreach($proveedores as $p)
            <tr>
                <td><input type="checkbox" class="checkProveedor" value="{{ $p->id }}"></td>
                <td>{{ $p->id }}</td>
                <td>{{ $p->nombre }}</td>
                <td>{{ $p->ruc }}</td>
                <td>{{ $p->telefono }}</td>
                <td>{{ $p->email }}</td>
                <td>
                    <a href="{{ route('admin.proveedores.edit', $p) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('admin.proveedores.destroy', $p) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Eliminar proveedor?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $proveedores->links() }}

    <!-- metas para JS -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="proveedores-eliminar-url" content="{{ route('admin.proveedores.eliminarMultiple') }}">
</div>
@endsection
