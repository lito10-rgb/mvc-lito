@props(['productos'])

<div class="related-products my-5">
    <h3 class="mb-4">🛍️ Productos Relacionados</h3>

    <div class="row">
        @foreach($productos as $prod)
            <div class="col-md-4 mb-4">
                <div class="product-card p-3 rounded shadow-sm">
                    <a href="{{ route('producto.mostrar', $prod->ruta) }}" class="text-decoration-none">
                        <img src="{{ asset('storage/' . $prod->imagen) }}" 
                             alt="{{ $prod->titulo }}" 
                             class="img-fluid rounded mb-3">

                        <h5 class="fw-bold">{{ $prod->titulo }}</h5>
                    </a>
                    <p class="text-muted mb-2">{{ $prod->categoria->nombre ?? '' }}</p>

                    <p class="precio fw-bold text-success">
                        {{ $prod->precio }} USD
                    </p>

                    <form action="{{ route('carrito.agregar', $prod->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100">
                            🛒 Comprar
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>
