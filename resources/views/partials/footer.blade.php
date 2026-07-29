@php
    $_neg = negocio_actual();
    $siteName = $_neg->nombre ?? config('theme.site_name');
    $siteSlogan = config('theme.site_slogan');
    $phone = $_neg->footer_phone ?? config('theme.contact.phone');
    $email = $_neg->footer_email ?? config('theme.contact.email');
    $whatsapp = $_neg->footer_whatsapp ?? config('theme.contact.whatsapp');
    $address = $_neg->footer_address ?? config('theme.contact.address');
    $fb = $_neg->footer_facebook ?? config('theme.social.facebook');
    $tw = $_neg->footer_twitter ?? config('theme.social.twitter');
    $ig = $_neg->footer_instagram ?? config('theme.social.instagram');
    $li = $_neg->footer_linkedin ?? config('theme.social.linkedin');
    $footerHtml = $_neg->footer_html ?? '';
    $mapLat = $_neg->map_lat ?? null;
    $mapLng = $_neg->map_lng ?? null;
@endphp
<style>
    footer a.text-theme-accent { transition: color 0.2s, opacity 0.2s; }
    footer a.text-theme-accent:hover { color: #fff !important; opacity: 1; text-shadow: 0 0 8px var(--theme-accent); }
    footer .nav-btn:hover { transform: scale(1.08); box-shadow: 0 0 10px var(--theme-accent); }
</style>
<footer class="bg-theme-footer mt-5 py-5 text-theme-accent">
    <div class="container">
        <div class="row g-4">

            <div class="col-md-6 col-lg-3">
                <h5 class="fw-bold mb-3 text-theme-accent">{{ $siteName }}</h5>
                <p class="small text-theme-accent opacity-75">{{ $siteSlogan }}</p>
                <a href="{{ route('visita-tecnica.create') }}" class="btn btn-cta-accent btn-sm"><i class="fa-solid fa-calendar-check me-1"></i> Agenda una visita técnica</a>
            </div>

            <div class="col-md-6 col-lg-3">
                <h5 class="fw-bold mb-3 text-theme-accent">Catálogo</h5>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="{{ route('productos.index') }}" class="nav-btn d-inline-block">Productos</a></li>
                    <li class="mb-2"><a href="{{ route('categoria.index') }}" class="nav-btn d-inline-block">Categorías</a></li>
                    <li class="mb-2"><a href="{{ url('/ofertas') }}" class="nav-btn d-inline-block">Ofertas</a></li>
                </ul>
            </div>

            <div class="col-md-6 col-lg-3">
                <h5 class="fw-bold mb-3 text-theme-accent">Contáctenos</h5>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="tel:{{ $phone }}" class="text-theme-accent text-decoration-none"><i class="fa-solid fa-phone me-2 text-theme-accent opacity-75"></i> {{ $phone }}</a></li>
                    @foreach(explode(';', $email) as $mail)
                        <li class="mb-2"><a href="mailto:{{ trim($mail) }}" class="text-theme-accent text-decoration-none"><i class="fa-solid fa-envelope me-2 text-theme-accent opacity-75"></i> {{ trim($mail) }}</a></li>
                    @endforeach
                    <li class="mb-2"><a href="https://wa.me/{{ ltrim($whatsapp, '+') }}" class="text-theme-accent text-decoration-none" target="_blank"><i class="fa-brands fa-whatsapp me-2 text-theme-accent opacity-75"></i> {{ $whatsapp }}</a></li>
                    <li class="mb-2"><i class="fa-solid fa-map-location-dot me-2 text-theme-accent opacity-75"></i> {{ $address }}@if($mapLat && $mapLng) <a href="https://www.google.com/maps?q={{ $mapLat }},{{ $mapLng }}" target="_blank" class="text-theme-accent text-decoration-underline small">(Ver en Maps)</a>@endif</li>
                </ul>
                <div class="mt-3">
                    <a href="{{ $fb }}" class="nav-btn me-2 d-inline-block text-center" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                    <a href="{{ $tw }}" class="nav-btn me-2 d-inline-block text-center" target="_blank"><i class="fa-brands fa-twitter"></i></a>
                    <a href="{{ $ig }}" class="nav-btn me-2 d-inline-block text-center" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                    <a href="{{ $li }}" class="nav-btn me-2 d-inline-block text-center" target="_blank"><i class="fa-brands fa-linkedin"></i></a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <h5 class="fw-bold mb-3 text-theme-accent">Boletín Informativo</h5>
                <p class="small text-theme-accent opacity-75">Recibe nuestras ofertas y novedades.</p>
                @if(session('success'))
                    <div class="alert alert-success py-2 small">{{ session('success') }}</div>
                @endif
                <form action="{{ route('boletin.suscribir') }}" method="POST">
                    @csrf
                    <div class="input-group input-group-sm mb-2">
                        <input type="email" name="email" class="form-control" placeholder="Tu email" required>
                        <button type="submit" class="btn btn-theme-accent"><i class="fa-solid fa-paper-plane"></i></button>
                    </div>
                    @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </form>
            </div>

        </div>

        @if($footerHtml)
            <div class="footer-custom-html mt-3">
                {!! $footerHtml !!}
            </div>
        @endif

        @if($mapLat && $mapLng)
            <div class="mt-4">
                <h6 class="fw-bold text-theme-accent mb-2"><i class="fa-solid fa-location-dot me-1"></i> Encuéntranos</h6>
                <div id="footerMap" style="height:250px; border-radius:8px;"></div>
                <div class="mt-2">
                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ urlencode($address) }}" target="_blank" class="btn btn-cta-accent btn-sm"><i class="fa-solid fa-diamond-turn-right me-1"></i> Cómo llegar</a>
                </div>
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var m = L.map('footerMap').setView([{{ $mapLat }}, {{ $mapLng }}], 16);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap', maxZoom: 19
                    }).addTo(m);
                    var mapPhoto = '{{ $_neg->map_photo ? asset("storage/" . $_neg->map_photo) : "" }}';
                    var popupHtml = '<div style="text-align:center;min-width:180px;">';
                    if (mapPhoto) {
                        popupHtml += '<img src="' + mapPhoto + '" style="width:100%;max-height:120px;object-fit:cover;border-radius:6px;margin-bottom:6px;">';
                    }
                    popupHtml += '<div id="footerMapAddr" style="font-size:13px;color:#333;">Cargando dirección...</div>';
                    popupHtml += '</div>';
                    var marker = L.marker([{{ $mapLat }}, {{ $mapLng }}]).addTo(m).bindPopup(popupHtml).openPopup();
                    fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat={{ $mapLat }}&lon={{ $mapLng }}&zoom=18')
                        .then(function(r){ return r.json(); })
                        .then(function(d){
                            if (d && d.display_name) {
                                document.getElementById('footerMapAddr').textContent = d.display_name;
                            } else {
                                document.getElementById('footerMapAddr').textContent = '{{ addslashes($address) }}';
                            }
                        })
                        .catch(function(){ document.getElementById('footerMapAddr').textContent = '{{ addslashes($address) }}'; });
                });
                </script>
            </div>
        @endif

        <hr class="my-4 border-theme-accent">
        <div class="row">
            <div class="col text-center small text-theme-accent opacity-75">
                &copy; {{ date('Y') }} {{ $siteName }}. Todos los derechos reservados.
            </div>
        </div>
    </div>
</footer>
