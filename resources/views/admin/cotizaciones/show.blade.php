@extends('layouts.admin')

@section('title', 'Detalle de Cotización')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Cotización #{{ $cotizacione->id }}</h3>
        <div>
            <a href="{{ route('admin.cotizaciones.edit', $cotizacione) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
            <a href="{{ route('admin.cotizaciones.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    @php
        $badges = ['pendiente' => 'warning', 'aprobada' => 'success', 'rechazada' => 'danger', 'completada' => 'info'];
    @endphp

    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Datos del Cliente</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="ps-0" style="width:120px;">Cliente</th>
                            <td>{{ $cotizacione->cliente }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Teléfono</th>
                            <td>{{ $cotizacione->telefono ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Correo</th>
                            <td>{{ $cotizacione->correo ?? '—' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Resumen</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="ps-0" style="width:120px;">Fecha</th>
                            <td>{{ $cotizacione->fecha->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Estado</th>
                            <td><span class="badge bg-{{ $badges[$cotizacione->estado] ?? 'secondary' }}">{{ ucfirst($cotizacione->estado) }}</span></td>
                        </tr>
                        <tr>
                            <th class="ps-0">Creado</th>
                            <td>{{ $cotizacione->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Detalle del Producto</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h6>Producto</h6>
                    <p class="mb-0">{{ $cotizacione->producto }}</p>
                </div>
            </div>

            @if($cotizacione->descripcion)
            <div class="row mb-3">
                <div class="col-12">
                    <h6>Descripción</h6>
                    <p class="mb-0">{{ $cotizacione->descripcion }}</p>
                </div>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered mb-0" style="max-width:500px;">
                    <thead class="table-light">
                        <tr>
                            <th>Cantidad</th>
                            <th>Precio Unit.</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $cotizacione->cantidad }}</td>
                            <td>S/ {{ number_format($cotizacione->precio_unitario, 2) }}</td>
                            <td>S/ {{ number_format($cotizacione->subtotal, 2) }}</td>
                        </tr>
                    </tbody>
                    <tfoot class="table-group-divider">
                        <tr>
                            <th colspan="2" class="text-end">Subtotal</th>
                            <td>S/ {{ number_format($cotizacione->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <th colspan="2" class="text-end">Impuesto</th>
                            <td>S/ {{ number_format($cotizacione->impuesto, 2) }}</td>
                        </tr>
                        <tr class="table-active">
                            <th colspan="2" class="text-end">Total</th>
                            <td><strong>S/ {{ number_format($cotizacione->total, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
