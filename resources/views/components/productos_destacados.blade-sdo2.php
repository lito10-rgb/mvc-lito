<div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
    @foreach ($productos as $producto)
        <div class="col">
            <div class="card h-100 border shadow-sm position-relative">
                <!-- Imagen -->
                <a href="{{ route('producto.mostrar', $producto->ruta) }}"><img src="{{ asset('storage/' . $producto->portada) }}" class="card-img-top" alt="{{ $producto->titulo }}" style="height: 200px; object-fit: cover;"></a>

           <!-- Cuerpo -->
                <div class="card-body">
                    <div class="position-relative group">
                         <a href="{{ route('producto.mostrar', $producto->ruta) }}"><h5 class="card-title hover-area text-primary" style="cursor: pointer;">
                            {{ $producto->titulo }}
                        </h5></a>

                        <div class="hover-block card shadow p-2"
                             style="position: absolute; top: 100%; left: 0; z-index: 1050; width: 220px; display: none;">
                            <ul class="list-unstyled mb-0">
                                <li><a href="#" class="text-dark d-flex align-items-center mb-2"><i class="fas fa-cart-plus me-2"></i>Agregar al carrito</a></li>
                                <li><a href="#" class="text-dark d-flex align-items-center mb-2"><i class="fas fa-search me-2"></i>Vista rápida</a></li>
                                <li><a href="#" class="text-dark d-flex align-items-center mb-2"><i class="fas fa-heart me-2"></i>Favoritos</a></li>
                                <li class="d-flex align-items-center">
                                    <span class="me-2">Compartir:</span>
                                    <a href="#" class="text-primary me-2"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#" class="text-danger me-2"><i class="fab fa-instagram"></i></a>
                                    <a href="#" class="text-danger"><i class="fab fa-pinterest-p"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Precio -->
                    <p class="card-text mt-3">
                        @if($producto->precio_oferta)
                            <span class="text-muted text-decoration-line-through">S/ {{ number_format($producto->precio, 2) }}</span>
                            <span class="text-success fw-bold ms-2">S/ {{ number_format($producto->precio_oferta, 2) }}</span>
                        @else
                            <span class="fw-bold">S/ {{ number_format($producto->precio, 2) }}</span>
                        @endif
                    </p>
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
