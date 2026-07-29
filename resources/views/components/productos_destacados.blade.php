<div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
    @foreach ($productos as $producto)
        <div class="col">
    <div class="card card-producto h-100 position-relative">
        <!-- Imagen -->
        <a href="{{ route('producto.mostrar', $producto->ruta) }}">
            <img src="{{ asset('storage/' . $producto->portada) }}" 
                 class="card-img-top" 
                 alt="{{ $producto->titulo }}" 
                 style="height: 300px; object-fit: cover;">
        </a>

        <!-- Cuerpo -->
        <div class="card-body text-center">
            <!-- Categoría -->
            <p class="categoria">{{ $producto->categoria->nombre ?? 'General' }}</p>

            <!-- Título Producto -->
            <h5 class="card-title">
                <a href="{{ route('producto.mostrar', $producto->ruta) }}">
                    {{ $producto->titulo }}
                </a>
            </h5>

            <!-- Precio -->
            <p class="precio">
                @if($producto->tipo === 'servicio' && $producto->precio == 0)
                    <span class="text-theme-accent fw-bold">Consultar precio</span>
                @elseif($producto->precioOferta)
                    <span class="precio-tachado">S/ {{ number_format($producto->precio, 2) }}</span>
                    <span class="precio-oferta">S/ {{ number_format($producto->precioOferta, 2) }}</span>
                @else
                    <span>S/ {{ number_format($producto->precio, 2) }}</span>
                @endif
            </p>

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

            <!-- Botón Comprar / Cotizar -->
            @if($producto->tipo === 'servicio' && $producto->precio == 0)
                <a href="{{ route('cotizacion.solicitar', $producto->id) }}" class="btn btn-comprar w-100" style="background-color:#ffc107;border-color:#ffc107;color:#000;">
                    <i class="fa-solid fa-file-invoice-dollar me-2"></i> Cotizar
                </a>
            @else
            <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-comprar w-100">
                    <i class="fas fa-shopping-cart me-2"></i> Comprar
                </button>
            </form>
            @endif
        </div>
    </div>
</div>

    @endforeach
</div>

<!-- PAGINADOR -->
<div class="d-flex justify-content-center mt-4">
    {{ $productos->links('pagination::bootstrap-5') }}
</div>

<!-- JS para el hover -->
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.hover-area').forEach(function (trigger) {
            const card = trigger.closest('.group');
            const hoverBlock = card.querySelector('.hover-block');

            let timer;

            trigger.addEventListener('mouseenter', () => {
                clearTimeout(timer);
                hoverBlock.style.display = 'block';
            });

            trigger.addEventListener('mouseleave', () => {
                timer = setTimeout(() => {
                    hoverBlock.style.display = 'none';
                }, 300);
            });

            hoverBlock.addEventListener('mouseenter', () => {
                clearTimeout(timer);
                hoverBlock.style.display = 'block';
            });

            hoverBlock.addEventListener('mouseleave', () => {
                hoverBlock.style.display = 'none';
            });
        });
    });
</script>
@endpush
