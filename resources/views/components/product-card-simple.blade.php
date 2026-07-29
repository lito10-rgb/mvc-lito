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

        @if($producto->tipo === 'servicio' && $producto->precio == 0)
            <p class="card-text fw-bold text-theme-accent">Consultar precio</p>
        @else
            <p class="card-text fw-bold">S/ {{ number_format($producto->precio, 2) }}</p>
        @endif

        @php($dias = $producto->entrega ?? '2')
        @if($producto->tipo === 'servicio')
            <p class="small text-info mb-2"><i class="fa-solid fa-gear"></i> Servicio — {{ $dias == 0 ? 'Entrega inmediata' : $dias . ' días' }}</p>
        @elseif($producto->tipo === 'digital' || $producto->tipo === 'planos')
            <p class="small text-success mb-2"><i class="fa-solid fa-download"></i> Descarga digital</p>
        @elseif($producto->stock > 0)
            <p class="small text-success mb-2"><i class="fa-solid fa-check-circle"></i> {{ $dias == 0 ? 'Entrega inmediata' : 'En stock — ' . $dias . ' días' }}</p>
        @else
            <p class="small text-warning mb-2"><i class="fa-solid fa-clock"></i> {{ $dias == 0 ? 'Disponibilidad — Entrega inmediata' : 'Por encargo — ' . $dias . ' días (aprox)' }}</p>
        @endif

        @if($producto->tipo === 'servicio' && $producto->precio == 0)
            <a href="{{ route('cotizacion.solicitar', $producto->id) }}" class="btn btn-outline-warning w-100"><i class="fa-solid fa-file-invoice-dollar"></i> Cotizar</a>
        @else
        <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary w-100">🛒 Comprar</button>
        </form>
        @endif
    </div>
</div>
