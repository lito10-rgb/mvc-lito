@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="fw-bold mb-4">🛒 Mi Carrito</h2>

    @if(session('carrito') && count(session('carrito')) > 0)
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
                @php $total = 0; @endphp
                @foreach(session('carrito') as $id => $item)
                    @php $subtotal = $item['precio'] * $item['cantidad']; $total += $subtotal; @endphp
                    <tr>
                        <td>
                            <img src="{{ asset('storage/' . $item['imagen']) }}" 
                                 onerror="this.src='{{ asset('images/no-image.png') }}'"
                                 width="70">
                        </td>
                        <td>{{ $item['titulo'] }}</td>
                        <td>{{ $item['precio'] }} USD</td>
                        <td>{{ $item['cantidad'] }}</td>
                        <td>{{ $subtotal }} USD</td>
                        <td>
                            <form action="{{ route('carrito.eliminar', $id) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4" class="text-end fw-bold">Total:</td>
                    <td colspan="2" class="fw-bold">{{ $total }} USD</td>
                </tr>
            </tbody>
        </table>

        <form action="{{ route('carrito.vaciar') }}" method="POST">
            @csrf
            <button class="btn btn-outline-danger">Vaciar carrito</button>
        </form>
    @else
        <p class="text-muted">Tu carrito está vacío.</p>
    @endif
</div>
@endsection
