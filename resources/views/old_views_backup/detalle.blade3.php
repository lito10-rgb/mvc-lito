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

    {{-- Galería de Imágenes --}}
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
        <!-- Columna de imágenes -->
        <div class="col-md-5">
            <div class="gallery row g-2">
                @foreach ($imagenes as $img)
                    @php $img = trim($img); @endphp
                    <div class="col-6 col-md-12 mb-2">
                        @if (preg_match('/^https?:\/\//', $img))
                            <!-- Si es un enlace externo, mostrar iframe -->
                            <iframe width="100%" height="200" src="{{ $img }}" frameborder="0" allowfullscreen></iframe>
                        @else
                            <!-- Si es imagen interna -->
                            <a href="{{ asset('storage/' . $img) }}" data-fancybox="galeria" data-caption="{{ $producto->nombre }}">
                                <img src="{{ asset('storage/' . $img) }}" class="img-fluid rounded shadow-sm w-100" alt="{{ $producto->nombre }}">
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Columna info -->
        <div class="col-md-7">
            <h2 class="fw-bold">{{ $producto->nombre }}</h2>
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
<!-- Fancybox -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

<script>
    function toggleFicha(id) {
        const seccion = document.getElementById(id);
        seccion.style.display = (seccion.style.display === 'none' || seccion.style.display === '') 
            ? 'block' : 'none';
    }
</script>
@endsection
