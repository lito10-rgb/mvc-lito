@extends('layouts.app')
@section('title', $cabecera->titulo)
@section('description', $cabecera->descripcion)
@section('keywords', $cabecera->palabras_claves)

@section('content')
<div class="container">
    <!-- Miga de pan -->
    <nav aria-label="breadcrumb" class="bg-light p-2 rounded shadow-sm">
        <ol class="breadcrumb mb-0 text-uppercase fw-semibold">
            <li class="breadcrumb-item">
                <a href="{{ url('/') }}" class="text-decoration-none text-primary">Inicio</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('productos.index') }}" class="text-decoration-none text-primary">Productos</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                {{ $producto->titulo }}
            </li>
        </ol>
    </nav>

    {{-- Galería de Imágenes con Swiper + Zoom --}}
    @php
        $imagenes = [];
        $raw = $producto->multimedia ?? '';

        if (is_string($raw) && strlen($raw) > 0) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $imagenes = $decoded;
            } elseif (strpos($raw, ',') !== false) {
                $imagenes = explode(',', $raw);
            } else {
                $imagenes = [$raw];
            }
        }
    @endphp

    <div class="row align-items-start my-4">
        <!-- Columna de imágenes con Swiper -->
        <div class="col-md-5">
            <div style="--swiper-navigation-color: #000; --swiper-pagination-color: #000">
                <!-- Swiper principal -->
<!-- Swiper principal -->
<div class="swiper mySwiper2 rounded shadow-sm mb-3">
    <div class="swiper-wrapper">
        @foreach ($imagenes as $img)
            @php $img = trim($img); @endphp
            <div class="swiper-slide">
                <div class="zoom-wrapper">
                    @if (preg_match('/^https?:\/\//', $img))
                        <iframe width="100%" height="350" src="{{ $img }}" frameborder="0" allowfullscreen></iframe>
                    @else
                        <img src="{{ asset('storage/' . $img) }}"
                             class="img-fluid"
                             data-zoom="{{ asset('storage/' . $img) }}"
                             alt="{{ $producto->nombre }}">
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Miniaturas -->
<div class="swiper mySwiper">
    <div class="swiper-wrapper">
        @foreach ($imagenes as $img)
            @php $img = trim($img); @endphp
            <div class="swiper-slide">
                @if (!preg_match('/^https?:\/\//', $img))
                    <img src="{{ asset('storage/' . $img) }}" class="img-fluid" alt="{{ $producto->nombre }}">
                @endif
            </div>
        @endforeach
    </div>
</div>


            </div>
        </div>

        <!-- Columna info + Zoom dinámico -->
        <div class="col-md-7">
            <h2 class="fw-bold">{{ $producto->titulo }}</h2>
            <p class="fs-5">Precio: <strong>{{ $producto->precio }} USD</strong></p>

            @if($producto->precio_oferta)
                <p class="fs-5 text-danger">Oferta: <strong>{{ $producto->precio_oferta }} USD</strong></p>
            @endif

            
            <!-- Acciones -->
            <div class="acciones mt-4">
                <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary mb-2">🛒 Añadir al carrito</button>
                </form>

                <a href="{{ route('cotizacion.solicitar', $producto->id) }}" class="btn btn-outline-secondary mb-2 ms-2">📩 Solicitar Cotización</a>

                <div class="mt-3">
                    <button onclick="mostrarQr('{{ route('producto.mostrar', $producto->ruta) }}')" class="btn btn-sm btn-info">📱 Ver QR</button>
                    <button class="btn btn-sm btn-outline-danger">❤️ Favorito</button>
                    <button class="btn btn-sm btn-outline-warning">🌟 Lista de deseos</button>
                </div>

                <div class="compartir mt-3">
                    <a href="#" onclick="compartir('facebook')">Facebook</a> |
                    <a href="#" onclick="compartir('instagram')">Instagram</a> |
                    <a href="#" onclick="compartir('pinterest')">Pinterest</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Fichas --}}
    <div class="fichas mt-4">
        <h3 onclick="toggleFicha('desc')" style="cursor:pointer">Descripción</h3>
        <div id="desc" style="display:none;">{{ $producto->descripcion }}</div>

        <h3 onclick="toggleFicha('det')" style="cursor:pointer">Detalles del Producto</h3>
        <div id="det" style="display:none;">{!! $producto->detalles !!}</div>

        <h3 onclick="toggleFicha('com')" style="cursor:pointer">Comentarios</h3>
        <div id="com" style="display:none;">
            @include('productos.comentarios', ['producto' => $producto])
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- SwiperJS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    // Mostrar/ocultar fichas
    function toggleFicha(id) {
        const seccion = document.getElementById(id);
        seccion.style.display = (seccion.style.display === 'none' || seccion.style.display === '')
            ? 'block' : 'none';
    }

    // Inicializar Swiper (miniaturas + principal)
    const swiperThumbs = new Swiper(".mySwiper", {
        spaceBetween: 10,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesProgress: true,
    });

    const swiperMain = new Swiper(".mySwiper2", {
        spaceBetween: 10,
        thumbs: { swiper: swiperThumbs },
    });

    // Zoom lateral dinámico: solo ocupa espacio mientras hay hover
   document.querySelectorAll('.zoom-wrapper').forEach(wrapper => {
    const img = wrapper.querySelector('img');

    wrapper.addEventListener('mousemove', (e) => {
        const rect = wrapper.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top) / rect.height) * 100;

        img.style.transformOrigin = `${x}% ${y}%`;
        wrapper.classList.add('activo');
    });

    wrapper.addEventListener('mouseleave', () => {
        wrapper.classList.remove('activo');
        img.style.transformOrigin = 'center center';
    });
});



</script>
@endsection
