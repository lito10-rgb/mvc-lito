@extends('layouts.app')
@section('title', 'Inicio - Equipos y Máquinas Industriales')
@section('content')

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
                    <a href="#" class="btn btn-primary mt-3"><i class="fas fa-search me-2"></i>Explorar</a>
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
        <a href="#" class="btn btn-primary mt-3"><i class="fas fa-list me-2"></i> Ver Equipos</a>
    </div>
</section>

@include('partials.buscador')

<!-- Productos destacados -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="mb-4">Productos Destacados</h2>
        @include('components.productos_destacados', ['productos' => $productos])
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
