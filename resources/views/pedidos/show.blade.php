@extends('layouts.app')

@section('title', 'Pedido #' . $pedido->id)

@section('content')
<div class="container py-4">
    <a href="{{ route('pedidos') }}" class="btn btn-outline-secondary btn-sm mb-3">&larr; Mis Pedidos</a>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Pedido #{{ $pedido->id }}</h4>
            @switch($pedido->status)
                @case('paid')
                @case('approved')
                    <span class="badge bg-success">Aprobado</span>
                    @break
                @case('pending')
                    <span class="badge bg-warning text-dark">Pendiente</span>
                    @break
                @case('rejected')
                @case('failed')
                    <span class="badge bg-danger">Rechazado</span>
                    @break
                @default
                    <span class="badge bg-secondary">{{ $pedido->status }}</span>
            @endswitch
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <strong>Fecha:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}<br>
                    <strong>Método de pago:</strong>
                    @switch($pedido->gateway)
                        @case('mercadopago') Mercado Pago @break
                        @case('paypal') PayPal @break
                        @case('simulado') Simulado @break
                        @default {{ $pedido->gateway ?? '—' }}
                    @endswitch
                </div>
                <div class="col-md-6">
                    @if($pedido->direccion)
                        <strong>Dirección de envío:</strong><br>
                        {{ $pedido->direccion }}<br>
                        @if($pedido->ciudad) {{ $pedido->ciudad }}, @endif
                        @if($pedido->departamento) {{ $pedido->departamento }} @endif
                        @if($pedido->telefono)<br>Tel: {{ $pedido->telefono }}@endif
                    @endif
                </div>
            </div>

            <h5>Productos</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedido->items as $item)
                        <tr>
                            <td>{{ $item->product->titulo ?? 'Producto #' . $item->product_id }}</td>
                            <td>S/ {{ number_format($item->precio, 2) }}</td>
                            <td>{{ $item->cantidad }}</td>
                            <td>S/ {{ number_format($item->precio * $item->cantidad, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total</th>
                        <th>S/ {{ number_format($pedido->amount, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
