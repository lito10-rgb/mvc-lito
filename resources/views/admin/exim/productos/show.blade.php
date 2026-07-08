@extends('layouts.admin')

@section('title', 'Detalle de Producto - EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">{{ $producto->nombre }}</h3>
        <div>
            <a href="{{ route('admin.exim.productos.edit', $producto) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
            <a href="{{ route('admin.exim.productos.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Información del Producto</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="ps-0" style="width:140px;">ID</th>
                            <td>{{ $producto->id }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Tipo</th>
                            <td><span class="badge bg-info text-capitalize">{{ str_replace('_', ' ', $producto->tipo) }}</span></td>
                        </tr>
                        <tr>
                            <th class="ps-0">Nombre</th>
                            <td>{{ $producto->nombre }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Producto Relacionado</th>
                            <td>{{ $producto->productoRelacionado->nombre ?? 'Ninguno' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Precio y Estado</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="ps-0" style="width:140px;">Precio Base</th>
                            <td>
                                @if($producto->moneda)
                                    {{ $producto->moneda->simbolo }} {{ number_format($producto->precio_base, 2) }}
                                @else
                                    {{ number_format($producto->precio_base, 2) }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="ps-0">Moneda</th>
                            <td>{{ $producto->moneda->codigo ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Estado</th>
                            <td>
                                @if($producto->estado)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="ps-0">Creado</th>
                            <td>{{ $producto->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
