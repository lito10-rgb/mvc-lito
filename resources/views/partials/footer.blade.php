<footer class="bg-dark mt-5 py-5" style="color: #ffc107;">
    <div class="container">
        <div class="row g-4">

            <div class="col-md-6 col-lg-3">
                <h5 class="fw-bold mb-3">Equipos y Máquinas</h5>
                <p class="small" style="color: #ffda75;">Soluciones industriales para tu negocio. Fabricantes de maquinaria de calidad.</p>
                <a href="{{ route('visita-tecnica.create') }}" class="btn btn-outline-warning btn-sm"><i class="fa-solid fa-calendar-check me-1"></i> Agenda una visita técnica</a>
            </div>

            <div class="col-md-6 col-lg-3">
                <h5 class="fw-bold mb-3">Catálogo</h5>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="{{ route('productos.index') }}" class="text-decoration-none" style="color: #ffc107;">Productos</a></li>
                    <li class="mb-2"><a href="{{ route('categoria.index') }}" class="text-decoration-none" style="color: #ffc107;">Categorías</a></li>
                    <li class="mb-2"><a href="{{ url('/ofertas') }}" class="text-decoration-none" style="color: #ffc107;">Ofertas</a></li>
                </ul>
            </div>

            <div class="col-md-6 col-lg-3">
                <h5 class="fw-bold mb-3">Contáctenos</h5>
                <ul class="list-unstyled small" style="color: #ffc107;">
                    <li class="mb-2"><i class="fa-solid fa-phone me-2" style="color: #ffda75;"></i> +51 1 7051923</li>
                    <li class="mb-2"><i class="fa-solid fa-envelope me-2" style="color: #ffda75;"></i> informes@equiposymaquinas.com</li>
                    <li class="mb-2"><i class="fa-brands fa-whatsapp me-2" style="color: #ffda75;"></i> +51 949296155</li>
                    <li class="mb-2"><i class="fa-solid fa-map-location-dot me-2" style="color: #ffda75;"></i> Lima, Perú</li>
                </ul>
                <div class="mt-3">
                    <a href="https://facebook.com" class="me-2 fs-5" style="color: #ffc107;" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                    <a href="https://twitter.com" class="me-2 fs-5" style="color: #ffc107;" target="_blank"><i class="fa-brands fa-twitter"></i></a>
                    <a href="https://instagram.com" class="me-2 fs-5" style="color: #ffc107;" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://linkedin.com" class="me-2 fs-5" style="color: #ffc107;" target="_blank"><i class="fa-brands fa-linkedin"></i></a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <h5 class="fw-bold mb-3">Boletín Informativo</h5>
                <p class="small" style="color: #ffda75;">Recibe nuestras ofertas y novedades.</p>
                @if(session('success'))
                    <div class="alert alert-success py-2 small">{{ session('success') }}</div>
                @endif
                <form action="{{ route('boletin.suscribir') }}" method="POST">
                    @csrf
                    <div class="input-group input-group-sm mb-2">
                        <input type="email" name="email" class="form-control" placeholder="Tu email" required>
                        <button type="submit" class="btn btn-warning"><i class="fa-solid fa-paper-plane"></i></button>
                    </div>
                    @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </form>
            </div>

        </div>
        <hr class="my-4 border-warning">
        <div class="row">
            <div class="col text-center small" style="color: #ffda75;">
                &copy; {{ date('Y') }} Equipos y Máquinas. Todos los derechos reservados.
            </div>
        </div>
    </div>
</footer>
