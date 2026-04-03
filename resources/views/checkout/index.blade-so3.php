@extends('layouts.app')

@section('title', 'Checkout - Confirmar pedido')
@section('description', 'Resumen del pedido y selección de método de pago')
@section('keywords', 'checkout,pago,mercadopago,paypal')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-3">Confirmar pedido</h1>

            {{-- Mensajes --}}
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
        </div>
    </div>

    @if(empty($carrito) || count($carrito) === 0)
        <div class="alert alert-warning">
            Tu carrito está vacío. <a href="{{ route('productos.index') }}">Ver productos</a>
        </div>
    @else
        <div class="row">
            {{-- Resumen (izq) --}}
            <div class="col-lg-8">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Productos</h5>

                        <div class="list-group list-group-flush">
                            @foreach($carrito as $id => $item)
                                <div class="list-group-item d-flex align-items-center">
                                    <div style="width:80px; height:80px; flex: 0 0 80px; margin-right:1rem;">
                                        @if(!empty($item['imagen']))
                                            <img src="{{ asset('storage/' . $item['imagen']) }}" alt="{{ $item['titulo'] }}"
                                                 class="img-fluid rounded" style="width:100%; height:100%; object-fit:cover;">
                                        @else
                                            <img src="{{ asset('images/no-image.png') }}" alt="Sin imagen" class="img-fluid rounded"
                                                 style="width:100%; height:100%; object-fit:cover;">
                                        @endif
                                    </div>

                                    <div class="flex-grow-1">
                                        <a href="{{ route('producto.mostrar', $item['ruta'] ?? '#') }}" class="fw-bold text-decoration-none">
                                            {{ $item['titulo'] }}
                                        </a>
                                        <div class="small text-muted">
                                            Cantidad: {{ $item['cantidad'] }} &middot; Precio unitario: ${{ number_format($item['precio'], 2) }}
                                        </div>
                                    </div>

                                    <div class="text-end ms-3">
                                        <div class="fw-bold">${{ number_format($item['precio'] * $item['cantidad'], 2) }}</div>

                                        <form action="{{ route('carrito.eliminar', $id) }}" method="POST" class="mt-2">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>

            {{-- Pago (der) --}}
            <div class="col-lg-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Resumen</h5>

                        <div class="d-flex justify-content-between">
                            <div>Subtotal</div>
                            <div>${{ number_format($subtotal, 2) }}</div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div>Envío</div>
                            <div>${{ number_format($envio, 2) }}</div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fs-5 fw-bold">
                            <div>Total</div>
                            <div>${{ number_format($total, 2) }}</div>
                        </div>

                        <hr>

                        {{-- Formulario para elegir método de pago --}}
                        <form action="{{ route('checkout.pay') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="metodo" class="form-label">Selecciona método de pago</label>
                                <select name="metodo" id="metodo" class="form-select" required>
                                    <option value="simulado">💠 Simulado (pruebas)</option>
                                    <option value="mercadopago">🟨 Mercado Pago</option>
                                    <option value="paypal">🅿️ PayPal</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success w-100 mb-2">
                                <i class="fas fa-credit-card me-2"></i> Pagar ahora
                            </button>

                            {{-- Alternativa: botones directos por pasarela --}}
                            <div class="d-grid gap-2">
                                <button type="button" onclick="document.getElementById('metodo').value='mercadopago'; document.forms[0].submit();"
                                        class="btn btn-outline-warning">
                                    <i class="fab fa-mercado-pago me-2"></i> Pagar con Mercado Pago
                                </button>

                                <button type="button" onclick="document.getElementById('metodo').value='paypal'; document.forms[0].submit();"
                                        class="btn btn-outline-primary">
                                    <i class="fab fa-paypal me-2"></i> Pagar con PayPal
                                </button>
                            </div>
                        </form>

                        <small class="text-muted d-block mt-3">
                            Debes estar <a href="{{ route('login') }}">logueado</a> para completar la compra.
                        </small>
                    </div>
                </div>

                <a href="{{ route('carrito.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                    ← Volver al carrito
                </a>

                <a href="{{ route('productos.index') }}" class="btn btn-outline-dark w-100">
                    ← Seguir comprando
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
