@extends('layouts.app')
@section('title', 'Inicio - Equipos y Máquinas Industriales')
@section('content')

<!-- Hero -->
<!-- <section class="hero bg-dark text-white text-center" style="background-image: url('{{ asset('images/hero-bg.jpg') }}'); height: 430px;background-size: cover; background-position: center;">
    <div class="container py-5">
        <h1 class="display-4">Encuentra la Herramienta perfecta para tu Taller</h1>
        <a href="#" class="btn btn-primary mt-3">Ver Equipos</a>
    </div>
</section> -->
<!-- HERO SLIDE PARA ESCRITORIO -->
<div id="heroCarousel" class="carousel slide d-none d-md-block" data-bs-ride="carousel">
    <div class="carousel-inner">
        <!-- Slide 1 -->
        <div class="carousel-item active">
            <section class="hero text-white text-center" style="background-image: url('{{ asset('images/hero-bg.jpg') }}'); height: 430px; background-size: cover; background-position: center;">
                <div class="container py-5">
                    <h1 class="display-4">Encuentra la Herramienta Perfecta para tu Taller</h1>
                    <a href="#" class="btn btn-primary mt-3">Ver Equipos</a>
                </div>
            </section>
        </div>
        <!-- Slide 2 (puedes agregar más) -->
        <div class="carousel-item">
            <section class="hero text-white text-center" style="background-image: url('{{ asset('images/hero2.jpg') }}'); height: 430px; background-size: cover; background-position: center;">
                <div class="container py-5">
                    <h1 class="display-4">Soluciones Industriales Somos Fabricantes de Maquinas</h1>
                    <a href="#" class="btn btn-primary mt-3">Explorar</a>
                </div>
            </section>
        </div>
    </div>

    <!-- Controles -->
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<!-- HERO SIMPLE PARA MÓVIL -->
<section class="hero bg-dark text-white text-center d-block d-md-none" style="background-image: url('{{ asset('images/hero-bg.jpg') }}'); background-size: cover; background-position: center;width: 100%;
  height: auto;
  display: block;">
    <div class="container py-5">
        <h2 class="h3">Maquinaria ideal para tu negocio</h2>
        <a href="#" class="btn btn-primary mt-3">Ver Equipos</a>
    </div>
</section>

<!-- Buscador avanzado -->
<!-- <section class="buscador container my-5">
    <form class="row g-3">
        <div class="col-md-3"><input type="text" class="form-control" placeholder="Tipo de equipo"></div>
        <div class="col-md-2"><input type="text" class="form-control" placeholder="Marca"></div>
        <div class="col-md-2"><input type="text" class="form-control" placeholder="Capacidad"></div>
        <div class="col-md-2"><input type="number" class="form-control" placeholder="Precio mínimo"></div>
        <div class="col-md-2"><input type="number" class="form-control" placeholder="Precio máximo"></div>
        <div class="col-md-1"><button class="btn btn-success w-100">Buscar</button></div>
    </form>
</section> -->

@include('partials.buscador')

<!-- Productos destacados -->
<!-- <section class="productos-destacados container mb-5">
    <div class="row row-cols-1 row-cols-md-4 g-4">
        @for($i = 0; $i < 8; $i++)
        <div class="col">
            <div class="card h-100">
                <img src="{{ asset('images/producto.jpg') }}" class="card-img-top" alt="Producto">
                <div class="card-body">
                    <h5 class="card-title">Producto {{ $i+1 }}</h5>
                    <p class="card-text">Breve descripción del producto.</p>
                    <a href="#" class="btn btn-primary">Agregar al carrito</a>
                </div>
            </div>
        </div>
        @endfor
    </div>
</section> -->
<!-- @section('content')
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Productos Destacados</h1>
    @include('componentes.productos_destacados', ['productos' => $productos])
  </div>
@endsection -->
<!-- <section class="py-10 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Productos Destacados</h2>

        @include('componentes.productos_destacados', ['productos' => $productos])
    </div>
</section> -->
<section class="py-10 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Productos Destacados</h2>

        @include('componentes.productos_destacados_carrusel', ['productos' => $productos])
    </div>
</section>

<!-- {{-- Testimonios --}} -->
<section class="bg-light py-5">
    <div class="container">
        <h2 class="mb-4">Lo que dicen nuestros clientes</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card p-3">
                    <p>“Gracias a MaquinasPro optimizamos todo nuestro proceso.”</p>
                    <strong>- Ing. Ramirez, Planta Surco</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <p>“Gran atención técnica y excelente maquinaria.”</p>
                    <strong>- Claudia M., Cliente frecuente</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <p>“Equipos duraderos y asesoría en todo momento.”</p>
                    <strong>- Luis V., Técnico especializado</strong>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- CTA -->
<section class="cta text-center bg-primary text-white py-5">
    <h2>Solicita una cotización personalizada</h2>
    <a href="#" class="btn btn-light mt-3">Agenda una visita técnica</a>
</section>

@endsection
