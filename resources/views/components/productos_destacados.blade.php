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
                @if($producto->precioOferta)
                    <span class="precio-tachado">S/ {{ number_format($producto->precio, 2) }}</span>
                    <span class="precio-oferta">S/ {{ number_format($producto->precioOferta, 2) }}</span>
                @else
                    <span>S/ {{ number_format($producto->precio, 2) }}</span>
                @endif
            </p>

            <!-- Botón Comprar -->
            <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-comprar w-100">
                    <i class="fas fa-shopping-cart me-2"></i> Comprar
                </button>
            </form>
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
