@props(['producto'])

<div class="card shadow-sm h-100">
    <img src="{{ asset('storage/' . $producto->portada) }}" 
         alt="{{ $producto->titulo }}" 
         class="card-img-top img-fluid">

    <div class="card-body d-flex flex-column">
        <h5 class="card-title">{{ $producto->titulo }}</h5>

        <p class="card-text">
            <strong>{{ $producto->precio }} USD</strong>
        </p>

        @if ($producto->precio_oferta)
            <p class="card-text text-danger">
                Oferta: <strong>{{ $producto->precio_oferta }} USD</strong>
            </p>
        @endif

        <div class="mt-auto">
            <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm">🛒 Comprar</button>
            </form>

            <a href="{{ route('producto.mostrar', $producto->ruta) }}" 
               class="btn btn-outline-secondary btn-sm ms-2">
                📄 Ver más
            </a>
        </div>
    </div>
</div>
