@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
<!-- Hero Section -->
<div id="heroCarousel" class="carousel slide d-none d-md-block" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <div class="d-block w-100 hero-slide" style="background-image: url('{{ asset('images/hero-bg.jpg') }}'); height: 400px; background-size: cover; background-position: center;"></div>
      <div class="carousel-caption d-none d-md-block">
        <h1 class="text-light">Encuentra la maquinaria perfecta para tu planta</h1>
      </div>
    </div>
  </div>
</div>

<!-- Static image for small screens -->
<div class="d-md-none">
  <img src="{{ asset('images/hero-bg.jpg') }}" alt="Hero" class="img-fluid w-100">
</div>

<!-- Placeholder de secciones futuras -->
<div class="container py-4">
  <h2>Bienvenido a nuestra tienda</h2>
  <p>Explora nuestras categorías de equipos y máquinas industriales.</p>
</div>
@endsection
