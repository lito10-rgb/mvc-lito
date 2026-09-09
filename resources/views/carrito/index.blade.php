@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="fw-bold mb-4"> Mi Carrito</h2>

    @if($carrito && count($carrito) > 0)
        @php $total = 0; @endphp
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($carrito as $id => $item)
                        @php $subtotal = $item['precio'] * $item['cantidad']; $total += $subtotal; @endphp
                        <tr>
                            <td>
                                <img src="{{ asset('storage/' . $item['imagen']) }}" 
                                     onerror="this.src='{{ asset('images/no-image.png') }}'"
                                     width="70">
                            </td>
                            <td>
                                @if(!empty($item['ruta']))
                                    <a href="{{ route('producto.mostrar', $item['ruta']) }}">{{ $item['titulo'] }}</a>
                                @else
                                    {{ $item['titulo'] }}
                                @endif
                                @if(!empty($item['envio_gratis']))
                                    <span class="badge bg-success ms-1"><i class="fa-solid fa-truck-fast"></i> Envío gratis</span>
                                @endif
                            </td>
                            <td>S/ {{ number_format($item['precio'], 2) }}</td>
                            <td>
                                <form action="{{ route('carrito.actualizar', $id) }}" method="POST" class="d-flex align-items-center gap-1">
                                    @csrf
                                    <input type="number" name="cantidad" value="{{ $item['cantidad'] }}" min="1" class="form-control form-control-sm" style="width:70px;">
                                    <button type="submit" class="btn btn-sm btn-outline-primary"><i class="fas fa-check"></i></button>
                                </form>
                            </td>
                            <td>S/ {{ number_format($subtotal, 2) }}</td>
                            <td>
                                <form action="{{ route('carrito.eliminar', $id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash-can"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Total:</td>
                        <td colspan="2" class="fw-bold">S/ {{ number_format($total, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('carrito.vaciar') }}" method="POST">
                @csrf
                <button class="btn btn-outline-danger"><i class="fas fa-trash-can me-1"></i> Vaciar carrito</button>
            </form>
            @auth
                <a href="{{ route('checkout.index') }}" class="btn btn-success"><i class="fas fa-credit-card me-1"></i> Realizar pago</a>
            @else
                <div class="alert alert-warning mb-0">
                    Debes <a href="{{ route('login') }}">iniciar sesión</a> o <a href="{{ route('register') }}">registrarte</a> para pagar.
                </div>
            @endauth
        </div>
    @else
        @php $ultima = session('ultima_ruta_productos'); @endphp
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart text-muted" style="font-size: 4rem;"></i>
            <p class="text-muted mt-3">Tu carrito está vacío.</p>
            <div class="d-flex justify-content-center gap-2">
                @if($ultima && isset($ultima['nombre']))
                    <a href="{{ url('/productos/buscar?' . $ultima['tipo'] . '=' . urlencode($ultima['nombre']) . '&negocio_id=' . negocio_actual_id()) }}" class="btn btn-primary">
                        <i class="fas fa-shopping-bag me-1"></i> Seguir comprando
                    </a>
                @else
                    <a href="{{ url('/productos/buscar?negocio_id=' . negocio_actual_id()) }}" class="btn btn-primary">
                        <i class="fas fa-shopping-bag me-1"></i> Empezar a comprar
                    </a>
                @endif
                <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-home me-1"></i> Volver al inicio
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
