<!-- resources/views/admin/categorias/edit.blade.php -->
@extends('admin.layout')
@section('content')
<div class="container py-4">
    <h1>Editar Categoría</h1>
    <form action="{{ route('admin.categorias.update', $categoria) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control" name="nombre" value="{{ $categoria->nombre }}" required>
        </div>
        <button class="btn btn-success">Actualizar</button>
        <a href="{{ route('admin.categorias.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
@endsection
