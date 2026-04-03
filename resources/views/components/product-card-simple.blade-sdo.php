@props(['producto'])

<div class="card h-100 shadow-sm">
    <img src="{{ asset('storage/' . $producto->portada) }}" class="card-img-top" alt="{{ $producto->titulo }}">

    <div class="card-body">
        <h5 class="card-title">{{ $producto->titulo }}</h5>
        <p class="card-text fw-bold">${{ $producto->precio }}</p>

        <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary w-100">🛒 Comprar</button>
        </form>
    </div>
</div>
