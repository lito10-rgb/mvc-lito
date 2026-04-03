@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
{{-- Hero --}}
<div class="bg-primary text-white text-center py-5" style="background-image: url('{{ asset('images/hero-bg.jpg') }}'); background-size: cover;">
    <div class="container">
        <h1>Encuentra la maquinaria perfecta para tu planta</h1>
        <p>Calidad, eficiencia y soporte técnico asegurado</p>
    </div>
</div>

{{-- Buscador avanzado --}}
<div class="container my-4">
    <form class="row g-2">
        <div class="col-md-3"><input type="text" class="form-control" placeholder="Tipo de equipo"></div>
        <div class="col-md-3"><input type="text" class="form-control" placeholder="Marca"></div>
        <div class="col-md-2"><input type="number" class="form-control" placeholder="Capacidad"></div>
        <div class="col-md-2"><input type="number" class="form-control" placeholder="Precio mínimo"></div>
        <div class="col-md-2"><input type="number" class="form-control" placeholder="Precio máximo"></div>
    </form>
</div>

{{-- Productos destacados --}}
<div class="container my-5">
    <h2 class="mb-4">Productos Destacados</h2>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
        @for ($i = 1; $i <= 8; $i++)
        <div class="col">
            <div class="card h-100">
                <img src="/images/producto{{ $i }}.jpg" class="card-img-top" alt="Producto {{ $i }}">
                <div class="card-body">
                    <h5 class="card-title">Máquina #{{ $i }}</h5>
                    <p class="card-text">Breve descripción del equipo industrial.</p>
                    <a href="#" class="btn btn-outline-primary">Agregar al carrito</a>
                </div>
            </div>
        </div>
        @endfor
    </div>
</div>

{{-- Testimonios --}}
<div class="container my-5">
    <h3 class="mb-4">Lo que dicen nuestros clientes</h3>
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="p-3 border rounded bg-light">
                <p>"Gracias a sus equipos, optimizamos nuestra producción un 30%."</p>
                <strong>— Empresa A</strong>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="p-3 border rounded bg-light">
                <p>"El soporte técnico fue excelente. Altamente recomendados."</p>
                <strong>— Empresa B</strong>
            </div>
        </div>
    </div>
</div>

{{-- Multimedia --}}
<div class="container my-5 text-center">
    <h3>Conoce nuestras máquinas en acción</h3>
    <div class="ratio ratio-16x9">
        <iframe src="https://www.youtube.com/embed/VIDEO_ID" title="Video de maquinaria industrial" allowfullscreen></iframe>
    </div>
</div>

{{-- CTA --}}
<div class="bg-secondary text-white text-center py-5">
    <h2>¿Necesitas asesoría personalizada?</h2>
    <p>Agenda una visita técnica o solicita tu cotización sin compromiso</p>
    <a href="#" class="btn btn-light mt-3">Solicitar cotización</a>
</div>

{{-- Oferta especial --}}
<div class="container text-center my-5">
    <div class="alert alert-success">
        🎉 Oferta especial para nuevos usuarios: 10% de descuento en tu primera compra. ¡Regístrate ahora!
    </div>
</div>
@endsection
