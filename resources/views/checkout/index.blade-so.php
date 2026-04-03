@extends('layouts.app')

@section('content')
<div class="container my-4">
    <h2>Checkout</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <h5>Resumen del pedido</h5>
            <ul class="list-unstyled">
                @foreach($carrito as $id => $item)
                    <li class="d-flex justify-content-between border-bottom py-2">
                        <div>
                            <strong>{{ $item['titulo'] }}</strong>
                            <div class="small text-muted">x {{ $item['cantidad'] }}</div>
                        </div>
                        <div>${{ number_format($item['precio'] * $item['cantidad'], 2) }}</div>
                    </li>
                @endforeach
            </ul>

            <div class="mt-3 text-end">
                <div>Subtotal: <strong>${{ number_format($subtotal, 2) }}</strong></div>
                <div>Envío: <strong>${{ number_format($envio, 2) }}</strong></div>
                <div class="fs-5">Total: <strong>${{ number_format($total, 2) }}</strong></div>
            </div>
        </div>
    </div>

    <form action="{{ route('checkout.pay') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Selecciona método de pago</label>
            <select name="metodo" class="form-select" required>
                <option value="simulado">Simulado (pruebas)</option>
                <option value="mercadopago">Mercado Pago</option>
                <option value="paypal">PayPal</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">💳 Realizar pago</button>
    </form>
</div>
@endsection
