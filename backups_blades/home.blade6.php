<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Equipos y Máquinas Industriales</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .hero-desktop {
      height: 60vh;
      background-size: cover;
      background-position: center;
      animation: slideBg 10s infinite;
    }

    @keyframes slideBg {
      0%   { background-image: url('/images/hero-bg1.jpg'); }
      50%  { background-image: url('/images/hero-bg2.jpg'); }
      100% { background-image: url('/images/hero-bg3.jpg'); }
    }

    @media (max-width: 768px) {
      .hero-desktop {
        background-image: url('/images/hero-bg1.jpg') !important;
        animation: none;
      }
    }

    .product-card {
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 10px;
      height: 100%;
    }

    footer {
      background: #222;
      color: #fff;
      padding: 20px 0;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">🛠️ MaquinasPro</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarMenu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="#">Inicio</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Categorías</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Tostadoras</a></li>
            <li><a class="dropdown-item" href="#">Selladoras</a></li>
            <li><a class="dropdown-item" href="#">Empacadoras</a></li>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link" href="#">Ofertas</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Contacto</a></li>
      </ul>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="#">Iniciar sesión</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Registrarse</a></li>
        <li class="nav-item">
          <a class="nav-link" href="#">🛒 (2)</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<header class="hero-desktop d-flex align-items-center justify-content-center text-white text-center">
  <div class="container">
    <h1 class="display-4 fw-bold">Encuentra la maquinaria perfecta para tu planta</h1>
    <p class="lead">Soluciones industriales confiables y eficientes</p>
  </div>
</header>

<section class="py-4 bg-light">
  <div class="container">
    <form class="row g-3">
      <div class="col-md-3"><input type="text" class="form-control" placeholder="Tipo de equipo"></div>
      <div class="col-md-2"><input type="text" class="form-control" placeholder="Marca"></div>
      <div class="col-md-2"><input type="text" class="form-control" placeholder="Capacidad"></div>
      <div class="col-md-2"><input type="number" class="form-control" placeholder="Precio mínimo"></div>
      <div class="col-md-2"><input type="number" class="form-control" placeholder="Precio máximo"></div>
      <div class="col-md-1"><button class="btn btn-primary w-100"><i class="bi bi-search"></i></button></div>
    </form>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <h2 class="mb-4 text-center">Productos Destacados</h2>
    <div class="row g-4">
      <!-- Simulación de productos -->
      <div class="col-md-3" *ngFor="let producto of [1,2,3,4,5,6,7,8]">
        <div class="product-card text-center">
          <img src="/images/producto1.jpg" class="img-fluid mb-2" alt="Producto">
          <h5>Máquina Industrial</h5>
          <p>Descripción breve del producto.</p>
          <a href="#" class="btn btn-sm btn-success">Agregar al carrito</a>
        </div>
      </div>
    </div>
  </div>
</section>

<footer>
  <div class="container text-center">
    <p>📍 Dirección de la empresa | 📞 Teléfono: +51 999 999 999 | ✉️ contacto@maquinaspro.com</p>
    <p>
      <a href="#" class="text-white me-2"><i class="bi bi-facebook"></i></a>
      <a href="#" class="text-white me-2"><i class="bi bi-instagram"></i></a>
      <a href="#" class="text-white me-2"><i class="bi bi-youtube"></i></a>
    </p>
    <p class="mb-0">&copy; 2025 MaquinasPro. Todos los derechos reservados.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
