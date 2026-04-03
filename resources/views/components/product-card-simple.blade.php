@props(['producto'])

<div class="card h-100 shadow-sm">
    <a href="{{ route('producto.mostrar', $producto->ruta) }}">
        <img 
            src="{{ $producto->portada
                ? asset('storage/' . $producto->portada) 
                : asset('images/no-image.png') }}" 
            class="card-img-top" 
            alt="{{ $producto->titulo }}">
    </a>

    <div class="card-body">
        <h5 class="card-title">
            <a href="{{ route('producto.mostrar', $producto->ruta) }}" class="text-decoration-none text-dark">
                {{ $producto->titulo }}
            </a>
        </h5>

        <p class="card-text fw-bold">
            ${{ $producto->precio }}
        </p>

        <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary w-100">🛒 Comprar</button>
        </form>
    </div>
</div>
