@extends('layouts.volt')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">{{ $producto->titulo }}</h3>
        <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table class="table">
                <tr><th>ID</th><td>{{ $producto->id }}</td></tr>
                <tr><th>Título</th><td>{{ $producto->titulo }}</td></tr>
                <tr><th>Ruta</th><td>{{ $producto->ruta }}</td></tr>
                <tr><th>Precio</th><td>S/. {{ number_format($producto->precio, 2) }}</td></tr>
                <tr><th>Estado</th><td>{{ $producto->estado ? 'Activo' : 'Inactivo' }}</td></tr>
                <tr><th>Categoría</th><td>{{ $producto->categoria->categoria ?? $producto->categoria->nombre ?? '-' }}</td></tr>
                <tr><th>Subcategoría</th><td>{{ $producto->subcategoria->subcategoria ?? $producto->subcategoria->nombre ?? '-' }}</td></tr>
                <tr><th>Marca</th><td>{{ $producto->marca->nombre ?? '-' }}</td></tr>
                <tr><th>Proveedor</th><td>{{ $producto->proveedor->nombre ?? '-' }}</td></tr>
                <tr><th>Vistas</th><td>{{ $producto->vistas ?? 0 }}</td></tr>
                <tr><th>Ventas</th><td>{{ $producto->ventas ?? 0 }}</td></tr>
            </table>
        </div>
    </div>
</div>
@endsection
