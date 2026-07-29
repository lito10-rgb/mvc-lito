@extends('layouts.app')
@section('title', 'Inicio - ' . negocio_actual_nombre())

@section('content')

@php($slides = negocio_actual()?->bannerSlides ?? collect())
@if($slides->count() == 0)
    @php($slides = collect([(object)['imagen' => null, 'titulo' => 'Bienvenido', 'subtitulo' => null, 'boton_texto' => null, 'boton_url' => null, 'categoria_id' => null, 'categoria' => null, 'color_texto' => null, 'color_boton_fondo' => null, 'color_boton_texto' => null, 'posicion' => null]]))
@endif

<!-- HERO SLIDE PARA ESCRITORIO -->
<div id="heroCarousel" class="carousel slide d-none d-md-block" data-bs-ride="carousel">
    <div class="carousel-inner">
        @foreach($slides as $i => $slide)
            @php($bg = $slide->imagen ? asset('storage/' . $slide->imagen) : asset('images/hero-bg.jpg'))
            @php($textColor = $slide->color_texto ?: '#ffffff')
            @php($btnBg = $slide->color_boton_fondo ?: '')
            @php($btnColor = $slide->color_boton_texto ?: '#000000')
            @php($pos = $slide->posicion ?: 'center')
            @php($textAlign = $pos == 'left' ? 'text-start' : ($pos == 'right' ? 'text-end' : 'text-center'))
            @php($justify = $pos == 'left' ? 'justify-content-start' : ($pos == 'right' ? 'justify-content-end' : 'justify-content-center'))
            @php($url = $slide->boton_url ? url($slide->boton_url) : ($slide->categoria_id ? url('categoria/' . ($slide->categoria?->ruta ?? $slide->categoria?->id)) : '#'))
            <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                <section class="hero text-white d-flex align-items-center {{ $justify }}" style="background-image: url('{{ $bg }}'); height: 430px; background-size: cover; background-position: center; color: {{ $textColor }} !important;">
                    <div class="container py-5 {{ $textAlign }}">
                        @if($slide->titulo)
                            <h1 class="display-4" style="color:{{ $textColor }}; text-shadow: 1px 1px 4px rgba(0,0,0,0.6);">{{ $slide->titulo }}</h1>
                        @endif
                        @if($slide->subtitulo)
                            <p class="lead" style="color:{{ $textColor }}; text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">{{ $slide->subtitulo }}</p>
                        @endif
                        @if($slide->boton_texto)
                            <a href="{{ $url }}" class="btn mt-3" style="background-color:{{ $btnBg ?: 'var(--theme-accent)' }}; color:{{ $btnColor }}; border-color:{{ $btnBg ?: 'var(--theme-accent)' }};">{{ $slide->boton_texto }}</a>
                        @endif
                    </div>
                </section>
            </div>
        @endforeach
    </div>
    @if($slides->count() > 1)
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    @endif
</div>

<!-- HERO SIMPLE PARA MÓVIL -->
@php($first = $slides->first())
@php($mbBg = $first->imagen ? asset('storage/' . $first->imagen) : asset('images/hero-bg.jpg'))
@php($mbTextColor = $first->color_texto ?: '#ffffff')
@php($mbBtnBg = $first->color_boton_fondo ?: '')
@php($mbBtnColor = $first->color_boton_texto ?: '#000000')
@php($mbUrl = $first->boton_url ? url($first->boton_url) : ($first->categoria_id ? url('categoria/' . ($first->categoria?->ruta ?? $first->categoria?->id)) : '#'))
<section class="hero bg-theme-dark d-flex align-items-center d-block d-md-none" style="background-image: url('{{ $mbBg }}'); background-size: cover; background-position: center;width: 100%; height: auto; display: block; color: {{ $mbTextColor }} !important;">
    <div class="container py-5 text-center">
        @if($first->titulo)
            <h2 class="h3" style="color:{{ $mbTextColor }}; text-shadow: 1px 1px 4px rgba(0,0,0,0.6);">{{ $first->titulo }}</h2>
        @endif
        @if($first->boton_texto)
            <a href="{{ $mbUrl }}" class="btn mt-3" style="background-color:{{ $mbBtnBg ?: 'var(--theme-accent)' }}; color:{{ $mbBtnColor }}; border-color:{{ $mbBtnBg ?: 'var(--theme-accent)' }};">{{ $first->boton_texto }}</a>
        @endif
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
<section class="cta text-center bg-theme-dark text-white py-5">
    <h2>Contáctanos para una cotización</h2>
    @if (negocio_actual_nombre() == 'Cafe Peruano')
        <a href="{{ route('visita-tecnica.create') }}" class="btn btn-cta-accent mt-3"><i class="fa-solid fa-calendar-check me-2"></i> Cotiza ahora</a>
        <a href="{{ route('visita-tecnica.create') }}" class="btn btn-cta-accent mt-3 ms-2"><i class="fa-solid fa-truck me-2"></i> Agenda una visita técnica</a>
    @else
        <a href="{{ route('visita-tecnica.create') }}" class="btn btn-cta-accent mt-3"><i class="fa-solid fa-calendar-check me-2"></i> Agenda una visita técnica</a>
    @endif
</section>

@endsection
