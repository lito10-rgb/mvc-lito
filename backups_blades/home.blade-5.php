{{-- home.blade.php - Plantilla Blade para Equipos y Máquinas Industriales --}}

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Venta de Equipos y Máquinas Industriales para plantas de proceso">
    <title>Inicio | Tienda de Equipos y Máquinas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero {
            background-image: url('{{ asset('images/hero-bg.jpg') }}');
            background-size: cover;
            background-position: center;
            height: 70vh;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>

{{-- Navbar --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">MaquinasPro</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link active" href="#">Inicio</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Categorías</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Empacadoras</a></li>
                        <li><a class="dropdown-item" href="#">Tostadoras</a></li>
                        <li><a class="dropdown-item" href="#">Filtros</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="#">Ofertas</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Contacto</a></li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="#">Iniciar sesión</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Registrarse</a></li>
                <li class="nav-item"><a class="nav-link" href="#">🛒 (2)</a></li>
            </ul>
        </div>
    </div>
</nav>

{{-- Hero --}}
<section class="hero">
    <div class="container">
        <h1 class="display-4 fw-bold">Encuentra la maquinaria perfecta para tu planta</h1>
        <a href="#productos" class="btn btn-primary btn-lg mt-4">Ver Equipos</a>
    </div>
</section>

{{-- Buscador avanzado --}}
<section class="py-5 bg-light">
    <div class="container">
        <form class="row g-3">
            <div class="col-md-3">
                <input type="text" class="form-control" placeholder="Tipo de equipo">
            </div>
            <div class="col-md-2">
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
            <div class="col-md-1">
                <button class="btn btn-primary w-100">Buscar</button>
            </div>
        </form>
    </div>
</section>

{{-- Productos destacados --}}
<section id="productos" class="py-5">
    <div class="container">
        <h2 class="mb-4">Productos Destacados</h2>
        <div class="row">
            @for ($i = 0; $i < 8; $i++)
                <div class="col-md-3 mb-4">
                    <div class="card product-card">
                        <img src="{{ asset('images/producto.jpg') }}" alt="Producto">
                        <div class="card-body">
                            <h5 class="card-title">Máquina Industrial {{ $i + 1 }}</h5>
                            <p class="card-text">Breve descripción del equipo industrial.</p>
                            <a href="#" class="btn btn-sm btn-success">Agregar al carrito</a>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</section>

{{-- Testimonios --}}
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

{{-- Multimedia --}}
<section class="py-5">
    <div class="container text-center">
        <h2 class="mb-4">Mira nuestros equipos en acción</h2>
        {{-- Reemplaza por tu video real --}}
        <iframe width="100%" height="400" src="https://www.youtube.com/embed/dQw4w9WgXcQ" frameborder="0" allowfullscreen></iframe>
    </div>
</section>

{{-- CTA --}}
<section class="py-5 bg-primary text-white text-center">
    <div class="container">
        <h2>¿Necesitas una solución personalizada?</h2>
        <p class="lead">Agenda una visita técnica o solicita tu cotización sin compromiso</p>
        <a href="#" class="btn btn-light btn-lg">Solicita Ahora</a>
    </div>
</section>

{{-- Oferta especial --}}
<section class="py-4 bg-warning text-center">
    <div class="container">
        <strong>¡Oferta especial para nuevos usuarios! Regístrate y recibe 10% de descuento</strong>
    </div>
</section>

{{-- Footer --}}
<footer class="bg-dark text-white py-4">
    <div class="container text-center">
        <p>© 2025 MaquinasPro - Todos los derechos reservados.</p>
        <p>Contacto: ventas@maquinaspro.com | Tel: (01) 234 5678</p>
        <p>
            <a href="#" class="text-white me-3"><i class="fab fa-facebook"></i></a>
            <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
            <a href="#" class="text-white"><i class="fab fa-whatsapp"></i></a>
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
