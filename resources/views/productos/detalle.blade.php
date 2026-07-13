@extends('layouts.app')
@section('title', $cabecera?->titulo ?? $producto->titulo)
@section('description', $cabecera?->descripcion ?? $producto->descripcion)
@section('keywords', $cabecera?->palabras_claves ?? '')

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
                             alt="{{ $producto->titulo }}">
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
                    <img src="{{ asset('storage/' . $img) }}" class="img-fluid" alt="{{ $producto->titulo }}">
                @endif
            </div>
        @endforeach
    </div>
</div>


            </div>
        </div>

        <!-- Columna info + Zoom dinámico -->
        <div class="col-md-7">
            <x-product-card :producto="$producto" />
        </div>
    </div>

    {{-- Fichas --}}
    <x-product-tabs :producto="$producto" />
<!-- RELACIONADOS -->
    <x-related-products :productos="$relacionados" />
</div>
@endsection

@section('scripts')
<!-- SwiperJS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
/* --- Zoom simple en el mismo cuadro, sin Swiper --- */
document.addEventListener('DOMContentLoaded', () => {
  const wrappers = document.querySelectorAll('.zoom-wrapper');

  wrappers.forEach(wrapper => {
    const img = wrapper.querySelector('img');
    if (!img) return;

    // Cuando mueves el mouse
    wrapper.addEventListener('mousemove', e => {
      const rect = wrapper.getBoundingClientRect();
      const x = ((e.clientX - rect.left) / rect.width) * 100;
      const y = ((e.clientY - rect.top) / rect.height) * 100;
      img.style.transformOrigin = `${x}% ${y}%`;
      img.style.transform = 'scale(1.5)'; // puedes subir a 1.6 si quieres más zoom
    });

    // Cuando sacas el cursor
    wrapper.addEventListener('mouseleave', () => {
      img.style.transform = 'scale(1)';
      img.style.transformOrigin = 'center center';
    });
  });
});
</script>

@endsection
