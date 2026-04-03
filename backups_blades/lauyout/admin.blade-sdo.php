<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Panel Admin - mvc-lito')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Volt Admin CSS (con Bootstrap 5) -->
    <link href="https://cdn.jsdelivr.net/npm/@themesberg/volt@1.0.0/dist/css/volt.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        /* Puedes agregar estilos personalizados acá */
    </style>

    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <nav id="sidebarMenu" class="sidebar d-lg-block bg-dark text-white collapse" data-simplebar>
        <div class="sidebar-inner px-4 pt-3">
            <a href="{{ url('/admin') }}" class="navbar-brand text-warning mb-4 d-block fs-4 fw-bold">
                mvc-lito Admin
            </a>
            <ul class="nav flex-column">
                <li class="nav-item mb-1">
                    <a href="{{ url('/admin') }}" class="nav-link text-warning">
                        <i class="bi bi-house"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ url('/admin/productos') }}" class="nav-link text-warning">
                        <i class="bi bi-box-seam"></i> Productos
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ url('/admin/categorias') }}" class="nav-link text-warning">
                        <i class="bi bi-tags"></i> Categorías
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ url('/admin/usuarios') }}" class="nav-link text-warning">
                        <i class="bi bi-people"></i> Usuarios
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main content -->
    <main class="content">

        <!-- Navbar top -->
        <nav class="navbar navbar-dark navbar-expand-lg bg-dark sticky-top">
            <div class="container-fluid">

                <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu"
                    aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle sidebar">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <a class="navbar-brand text-warning fw-bold" href="{{ url('/') }}">mvc-lito</a>

                <div class="d-flex align-items-center ms-auto">
                    <a href="{{ url('/carrito') }}" class="btn btn-outline-warning position-relative me-3">
                        <i class="bi bi-cart"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ session('cart_count', 0) }}
                        </span>
                    </a>

                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-warning text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-4 me-2"></i> Admin
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark text-small shadow" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ url('/admin/perfil') }}">Perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ url('/logout') }}">Cerrar sesión</a></li>
                        </ul>
                    </div>
                </div>

            </div>
        </nav>

        <!-- Contenido principal -->
        <div class="container-fluid p-4">
            @yield('content')
        </div>

    </main>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
