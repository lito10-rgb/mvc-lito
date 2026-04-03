@extends('layouts.app')
@section('title', $cabecera->titulo)
@section('description', $cabecera->descripcion)
@section('keywords', $cabecera->palabras_claves)

@section('content')
<div class="container">
    <!-- Miga de pan -->
    <nav aria-label="breadcrumb" class="hero-cafe p-2 rounded shadow-sm">
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
     <!--  {{-- Hero (opcional) --}}
  <section class="hero-cafe rounded-3 p-4 mb-4">
    <div class="container">
      <h1 class="h2 text-white mb-1">{{ $producto->nombre }}</h1>
      @if(!empty($cabecera->descripcio

      n))
        <p class="mb-0 text-white-50">{{ $cabecera->descripcion }}</p>
      @endif
    </div>
  </section> -->
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
                <div class="swiper mySwiper2 rounded shadow-sm mb-3">
                    <div class="swiper-wrapper">
                        @foreach ($imagenes as $img)
                            @php $img = trim($img); @endphp
                            <div class="swiper-slide">
                                @if (preg_match('/^https?:\/\//', $img))
                                    <iframe width="100%" height="350" src="{{ $img }}" frameborder="0" allowfullscreen></iframe>
                                @else
                                    <img src="{{ asset('storage/' . $img) }}"
                                         class="img-fluid zoom-image"
                                         data-zoom="{{ asset('storage/' . $img) }}"
                                         alt="{{ $producto->nombre }}">
                                @endif
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
            <h2 class="fw-bold mb-3">{{ $producto->nombre }}</h2>
            <p class="fs-3 text-success fw-bold">Precio: ${{ $producto->precio }}</p>

            @if($producto->precio_oferta)
                <p class="fs-4 text-danger fw-bold">Oferta: ${{ $producto->precio_oferta }}</p>
            @endif

            <!-- Zoom dinámico -->
            <div id="zoom-container" class="d-none mb-3">
                <div id="zoom-viewer"
                     class="rounded shadow-sm"
                     style="border:1px solid #ddd; width:100%; height:400px; background-size:cover; background-repeat:no-repeat;">
                </div>
            </div>

            <!-- Acciones -->
            <div class="acciones mt-4">
                <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-cafe mb-2">
                        <i class="fas fa-shopping-cart"></i> Añadir al carrito
                    </button>
                </form>

                <a href="{{ route('cotizacion.solicitar', $producto->id) }}" class="btn btn-dorado mb-2 ms-2">
                    <i class="fas fa-envelope"></i> Solicitar Cotización
                </a>

                <div class="mt-3">
                    <button onclick="mostrarQr('{{ route('producto.mostrar', $producto->ruta) }}')" class="btn btn-sm btn-info">
                        <i class="fas fa-qrcode"></i> Ver QR
                    </button>
                    <button class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-heart"></i> Favorito
                    </button>
                    <button class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-star"></i> Lista de deseos
                    </button>
                </div>

                <div class="compartir mt-3">
                    <a href="#" class="me-2 text-primary"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="me-2 text-danger"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-danger"><i class="fab fa-pinterest"></i></a>
                </div>
            </div>
        </div>
    </div>

    {{-- Fichas estilizadas con Bootstrap Accordion --}}
    <div class="fichas mt-4">
        <div class="accordion" id="fichasProducto">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingDesc">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#desc" aria-expanded="true" aria-controls="desc">
                        Descripción
                    </button>
                </h2>
                <div id="desc" class="accordion-collapse collapse show" aria-labelledby="headingDesc" data-bs-parent="#fichasProducto">
                    <div class="accordion-body">
                        {{ $producto->descripcion }}
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingDet">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#det" aria-expanded="false" aria-controls="det">
                        Detalles del Producto
                    </button>
                </h2>
                <div id="det" class="accordion-collapse collapse" aria-labelledby="headingDet" data-bs-parent="#fichasProducto">
                    <div class="accordion-body">
                        {!! $producto->detalles !!}
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingCom">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#com" aria-expanded="false" aria-controls="com">
                        Comentarios
                    </button>
                </h2>
                <div id="com" class="accordion-collapse collapse" aria-labelledby="headingCom" data-bs-parent="#fichasProducto">
                    <div class="accordion-body">
                        @include('productos.comentarios', ['producto' => $producto])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- SwiperJS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<style>
    .mySwiper2 {
        width: 100%;
        height: 350px;
    }
    .mySwiper2 img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        cursor: zoom-in;
    }
    .mySwiper {
        height: 100px;
        margin-top: 10px;
    }
    .mySwiper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        cursor: pointer;
    }
    .swiper-slide-thumb-active img {
        border: 2px solid #0d6efd;
    }
</style>

<script>
    // Inicializar Swiper
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

    // Zoom dinámico lateral
    document.addEventListener('DOMContentLoaded', function () {
        const zoomImages   = document.querySelectorAll('.zoom-image');
        const zoomViewer   = document.getElementById('zoom-viewer');
        const zoomContainer= document.getElementById('zoom-container');

        zoomImages.forEach(img => {
            img.addEventListener('mouseenter', function () {
                zoomContainer.classList.remove('d-none');
                zoomViewer.style.backgroundImage = `url(${this.dataset.zoom})`;
                zoomViewer.style.backgroundSize  = '200%';
            });

            img.addEventListener('mousemove', function (e) {
                const rect = this.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width) * 100;
                const y = ((e.clientY - rect.top) / rect.height) * 100;
                zoomViewer.style.backgroundPosition = `${x}% ${y}%`;
            });

            img.addEventListener('mouseleave', function () {
                zoomContainer.classList.add('d-none');
                zoomViewer.style.backgroundImage = 'none';
            });
        });
    });
</script>
@endsection
