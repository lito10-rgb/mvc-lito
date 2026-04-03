@extends('admin.layouts.admin')

@section('title','Nuevo Proveedor')

@section('content')
<div class="container py-3">
    <h3>Nuevo Proveedor</h3>
    <form action="{{ route('admin.proveedores.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>RUC</label>
            <input type="text" name="ruc" class="form-control">
        </div>
        <div class="mb-3">
            <label>Teléfono</label>
            <input type="text" name="telefono" class="form-control">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control">
        </div>
        <div class="mb-3">
            <label>Dirección</label>
            <input type="text" name="direccion" class="form-control">
        </div>

        <button class="btn btn-success">Guardar</button>
        <a href="{{ route('admin.proveedores.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
@endsection
e.php