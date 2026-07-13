@php
    $siteName = config('theme.site_name');
    $siteSlogan = config('theme.site_slogan');
    $phone = config('theme.contact.phone');
    $email = config('theme.contact.email');
    $whatsapp = config('theme.contact.whatsapp');
    $address = config('theme.contact.address');
@endphp
<footer class="bg-dark mt-5 py-5 text-warning">
    <div class="container">
        <div class="row g-4">

            <div class="col-md-6 col-lg-3">
                <h5 class="fw-bold mb-3 text-warning">{{ $siteName }}</h5>
                <p class="small text-warning opacity-75">{{ $siteSlogan }}</p>
                <a href="{{ route('visita-tecnica.create') }}" class="btn btn-outline-warning btn-sm"><i class="fa-solid fa-calendar-check me-1"></i> Agenda una visita técnica</a>
            </div>

            <div class="col-md-6 col-lg-3">
                <h5 class="fw-bold mb-3 text-warning">Catálogo</h5>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="{{ route('productos.index') }}" class="text-decoration-none text-warning">Productos</a></li>
                    <li class="mb-2"><a href="{{ route('categoria.index') }}" class="text-decoration-none text-warning">Categorías</a></li>
                    <li class="mb-2"><a href="{{ url('/ofertas') }}" class="text-decoration-none text-warning">Ofertas</a></li>
                </ul>
            </div>

            <div class="col-md-6 col-lg-3">
                <h5 class="fw-bold mb-3 text-warning">Contáctenos</h5>
                <ul class="list-unstyled small text-warning">
                    <li class="mb-2"><i class="fa-solid fa-phone me-2 text-warning opacity-75"></i> {{ $phone }}</li>
                    <li class="mb-2"><i class="fa-solid fa-envelope me-2 text-warning opacity-75"></i> {{ $email }}</li>
                    <li class="mb-2"><i class="fa-brands fa-whatsapp me-2 text-warning opacity-75"></i> {{ $whatsapp }}</li>
                    <li class="mb-2"><i class="fa-solid fa-map-location-dot me-2 text-warning opacity-75"></i> {{ $address }}</li>
                </ul>
                <div class="mt-3">
                    <a href="{{ config('theme.social.facebook') }}" class="me-2 fs-5 text-warning" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                    <a href="{{ config('theme.social.twitter') }}" class="me-2 fs-5 text-warning" target="_blank"><i class="fa-brands fa-twitter"></i></a>
                    <a href="{{ config('theme.social.instagram') }}" class="me-2 fs-5 text-warning" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                    <a href="{{ config('theme.social.linkedin') }}" class="me-2 fs-5 text-warning" target="_blank"><i class="fa-brands fa-linkedin"></i></a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <h5 class="fw-bold mb-3 text-warning">Boletín Informativo</h5>
                <p class="small text-warning opacity-75">Recibe nuestras ofertas y novedades.</p>
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
            <div class="col text-center small text-warning opacity-75">
                &copy; {{ date('Y') }} {{ $siteName }}. Todos los derechos reservados.
            </div>
        </div>
    </div>
</footer>
