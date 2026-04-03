@extends('layouts.app')

@section('title', 'Inicio - Equipos y Máquinas Industriales')

@section('content')
<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#">MiTienda</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="#">Inicio</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="categorias" data-bs-toggle="dropdown">Categorías</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Trituradoras</a></li>
            <li><a class="dropdown-item" href="#">Empacadoras</a></li>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link" href="#">Ofertas</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Contacto</a></li>
      </ul>
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="#">Iniciar sesión</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Registrarse</a></li>
        <li class="nav-item">
          <a class="nav-link" href="#">🛒 <span class="badge bg-success">2</span></a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- ================= HERO ================= -->
<header class="bg-light text-center py-5" style="background-image: url('/img/hero.jpg'); background-size: cover; background-position: center;">
  <div class="container text-white">
    <h1 class="display-4 fw-bold">Encuentra la maquinaria perfecta para tu planta</h1>
    <p class="lead">Explora soluciones industriales diseñadas para optimizar tu producción.</p>
    <a href="#" class="btn btn-primary btn-lg">Explora ahora</a>
  </div>
</header>

<!-- ================= BUSCADOR AVANZADO ================= -->
<section class="container py-5">
  <!-- Buscador -->
  <form class="row g-3">
    <div class="col-md-3">
      <input type="text" class="form-control" placeholder="Tipo de equipo">
    </div>
    <div class="col-md-3">
      <input type="text" class="form-control" placeholder="Marca">
    </div>
    <div class="col-md-2">
      <input type="text" class="form-control" placeholder="Capacidad">
    </div>
    <div class="col-md-2">
      <input type="number" class="form-control" placeholder="Precio mínimo">
    </div>
    <div class="col-md-2">
      <input type="number" class="form-control" placeholder="Precio máximo">
    </div>
  </form>
</section>

<!-- ================= PRODUCTOS DESTACADOS ================= -->
<section class="container py-5">
  <h2 class="mb-4 text-center">Productos Destacados</h2>
  <div class="row row-cols-1 row-cols-md-4 g-4">
    @for ($i = 0; $i < 8; $i++)
      <div class="col">
        <div class="card h-100">
          <img src="/img/placeholder.png" class="card-img-top" alt="Producto">
          <div class="card-body">
            <h5 class="card-title">Nombre del Producto {{ $i+1 }}</h5>
            <p class="card-text">Descripción breve del producto.</p>
            <a href="#" class="btn btn-outline-primary">Agregar al carrito</a>
          </div>
        </div>
      </div>
    @endfor
  </div>
</section>

<!-- ================= TESTIMONIOS ================= -->
<section class="bg-light py-5">
  <div class="container">
    <h2 class="text-center mb-4">Lo que dicen nuestros clientes</h2>
    <div class="row">
      <div class="col-md-4">
        <blockquote class="blockquote">
          <p>"Excelentes máquinas, optimizaron todo nuestro proceso."</p>
          <footer class="blockquote-footer">Juan Pérez</footer>
        </blockquote>
      </div>
      <div class="col-md-4">
        <blockquote class="blockquote">
          <p>"Servicio técnico impecable y entrega rápida."</p>
          <footer class="blockquote-footer">María García</footer>
        </blockquote>
      </div>
      <div class="col-md-4">
        <blockquote class="blockquote">
          <p>"Muy satisfechos con la calidad de los equipos."</p>
          <footer class="blockquote-footer">Carlos López</footer>
        </blockquote>
      </div>
    </div>
  </div>
</section>

<!-- ================= MULTIMEDIA ================= -->
<section class="container py-5 text-center">
  <h2 class="mb-4">Conoce nuestras instalaciones</h2>
  <div class="ratio ratio-16x9">
    <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Video"></iframe>
  </div>
</section>

<!-- ================= CTA ================= -->
<section class="bg-primary text-white text-center py-5">
  <h2 class="mb-3">Solicita una cotización personalizada</h2>
  <a href="#" class="btn btn-light btn-lg">Agenda una visita técnica</a>
</section>

<!-- ================= OFERTA ESPECIAL ================= -->
<section class="container py-5">
  <div class="alert alert-success text-center">
    ¡Bienvenido! Recibe un 10% de descuento en tu primera compra.
  </div>
</section>

<!-- ================= FOOTER ================= -->
<footer class="bg-dark text-white pt-4 pb-2">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <h5>Contacto</h5>
        <p>Dirección de la tienda</p>
        <p>Tel: 123456789</p>
        <p>Email: contacto@mitienda.com</p>
      </div>
      <div class="col-md-6 text-md-end">
        <h5>Síguenos</h5>
        <a href="#" class="text-white me-3">Facebook</a>
        <a href="#" class="text-white me-3">Instagram</a>
        <a href="#" class="text-white">WhatsApp</a>
      </div>
    </div>
    <div class="text-center mt-3">
      <small>&copy; 2025 MiTienda. Todos los derechos reservados.</small>
    </div>
  </div>
</footer>
@endsection
