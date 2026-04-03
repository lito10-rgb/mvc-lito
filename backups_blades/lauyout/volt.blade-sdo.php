<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Panel Admin MVC-Lito')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    
    <style>
        body {
            min-height: 100vh;
            overflow-x: hidden;
        }
        /* Sidebar */
        #sidebar {
            min-width: 220px;
            max-width: 220px;
            min-height: 100vh;
            background-color: #212529;
            color: #ffc107;
            transition: all 0.3s;
        }
        #sidebar a {
            color: #ffc107;
            text-decoration: none;
        }
        #sidebar a:hover, #sidebar a.active {
            background-color: #343a40;
            color: #fff;
            text-decoration: none;
        }
        /* Content */
        #content {
            width: calc(100% - 220px);
            margin-left: 220px;
            transition: all 0.3s;
            padding: 20px;
        }
        /* Responsive */
        @media (max-width: 768px) {
            #sidebar {
                margin-left: -220px;
                position: fixed;
                z-index: 9999;
            }
            #sidebar.active {
                margin-left: 0;
            }
            #content {
                width: 100%;
                margin: 0;
            }
            #sidebarToggle {
                display: inline-block;
            }
        }
        #sidebarToggle {
            display: none;
            cursor: pointer;
            font-size: 1.5rem;
            color: #ffc107;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <span id="sidebarToggle" class="bi bi-list"></span>
            <a class="navbar-brand" href="{{ url('/admin') }}">MVC-Lito Admin</a>
        </div>
    </nav>

    <div class="d-flex">
        <nav id="sidebar" class="bg-dark">
            <ul class="list-unstyled pt-3">
                <li class="mb-2 px-3">
                    <a href="{{ url('/admin') }}" class="{{ request()->is('admin') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="mb-2 px-3">
                    <a href="{{ url('/admin/productos') }}" class="{{ request()->is('admin/productos*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam me-2"></i> Productos
                    </a>
                </li>
                <li class="mb-2 px-3">
                    <a href="{{ url('/admin/categorias') }}" class="{{ request()->is('admin/categorias*') ? 'active' : '' }}">
                        <i class="bi bi-tags me-2"></i> Categorías
                    </a>
                </li>
                <li class="mb-2 px-3">
                    <a href="{{ url('/admin/usuarios') }}" class="{{ request()->is('admin/usuarios*') ? 'active' : '' }}">
                        <i class="bi bi-people me-2"></i> Usuarios
                    </a>
                </li>
                <li class="mb-2 px-3">
                    <a href="{{ url('/admin/configuracion') }}" class="{{ request()->is('admin/configuracion*') ? 'active' : '' }}">
                        <i class="bi bi-gear me-2"></i> Configuración
                    </a>
                </li>
            </ul>
        </nav>

        <main id="content" class="flex-grow-1">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 JS bundle (Popper + Bootstrap) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle for small screens
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>