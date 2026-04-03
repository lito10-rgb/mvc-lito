<!-- resources/views/admin/categorias/create.blade.php -->
@extends('admin.layout')
@section('content')
<div class="container py-4">
    <h1>Nueva Categoría</h1>
    <form action="{{ route('admin.categorias.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control" name="nombre" required>
        </div>
        <button class="btn btn-success">Guardar</button>
        <a href="{{ route('admin.categorias.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
@endsection