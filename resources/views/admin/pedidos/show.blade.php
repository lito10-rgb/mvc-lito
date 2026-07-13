@extends('layouts.volt')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Pedido #{{ $pedido->id }}</h3>
        <a href="{{ route('admin.pedidos.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información del Pedido</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr><th>ID</th><td>{{ $pedido->id }}</td></tr>
                        <tr><th>Monto</th><td><strong>S/ {{ number_format($pedido->amount, 2) }}</strong></td></tr>
                        <tr>
                            <th>Estado</th>
                            <td>
                                @php
                                    $badge = match($pedido->status) {
                                        'paid' => 'success',
                                        'approved' => 'info',
                                        'pending' => 'warning',
                                        'failed' => 'danger',
                                        'cancelled' => 'secondary',
                                        'refunded' => 'dark',
                                        default => 'light'
                                    };
                                @endphp
                                <span class="badge bg-{{ $badge }}">{{ ucfirst($pedido->status) }}</span>
                            </td>
                        </tr>
                        <tr><th>Gateway</th><td>{{ $pedido->gateway ?? '—' }}</td></tr>
                        <tr><th>Preference ID</th><td><code>{{ $pedido->preference_id ?? '—' }}</code></td></tr>
                        <tr><th>MP Payment ID</th><td><code>{{ $pedido->mp_payment_id ?? '—' }}</code></td></tr>
                        <tr><th>Fecha</th><td>{{ $pedido->created_at ? $pedido->created_at->format('d/m/Y H:i:s') : '—' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Cliente</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr><th>Nombre</th><td>{{ $pedido->user?->name ?? '—' }}</td></tr>
                        <tr><th>Email</th><td>{{ $pedido->user?->email ?? '—' }}</td></tr>
                        <tr><th>Teléfono</th><td>{{ $pedido->telefono ?? '—' }}</td></tr>
                    </table>

                    @if($pedido->direccion)
                    <h6 class="mt-3"><i class="fas fa-map-marker-alt me-1"></i> Dirección de Envío</h6>
                    <table class="table table-sm">
                        <tr><th>Dirección</th><td>{{ $pedido->direccion }}</td></tr>
                        <tr><th>Ciudad</th><td>{{ $pedido->ciudad ?? '—' }}</td></tr>
                        <tr><th>Departamento</th><td>{{ $pedido->departamento ?? '—' }}</td></tr>
                    </table>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-box me-2"></i>Productos ({{ $pedido->items->count() }})</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unit.</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedido->items as $item)
                            <tr>
                                <td>
                                    {{ $item->product?->titulo ?? 'Producto #' . $item->product_id }}
                                    @if($item->product?->ruta)
                                        <br><small class="text-muted"><code>{{ $item->product->ruta }}</code></small>
                                    @endif
                                </td>
                                <td>{{ $item->cantidad }}</td>
                                <td>S/ {{ number_format($item->precio, 2) }}</td>
                                <td><strong>S/ {{ number_format($item->cantidad * $item->precio, 2) }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-dark">
                            <tr>
                                <th colspan="3" class="text-end">Total</th>
                                <th>S/ {{ number_format($pedido->amount, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endSection
