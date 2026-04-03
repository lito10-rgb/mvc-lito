@extends('admin.layouts.admin')

@section('title','Editar Proveedor')

@section('content')
<div class="container py-3">
    <h3>Editar Proveedor</h3>
    <form action="{{ route('admin.proveedores.update', $proveedor) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ $proveedor->nombre }}" required>
        </div>
        <div class="mb-3">
            <label>RUC</label>
            <input type="text" name="ruc" class="form-control" value="{{ $proveedor->ruc }}">
        </div>
        <div class="mb-3">
            <label>Teléfono</label>
            <input type="text" name="telefono" class="form-control" value="{{ $proveedor->telefono }}">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $proveedor->email }}">
        </div>
        <div class="mb-3">
            <label>Dirección</label>
            <input type="text" name="direccion" class="form-control" value="{{ $proveedor->direccion }}">
        </div>

        <button class="btn btn-success">Actualizar</button>
        <a href="{{ route('admin.proveedores.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
@endsection
