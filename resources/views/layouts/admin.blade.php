<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite([
        'resources/scss/app.scss',
        'resources/js/app.js',
        'resources/js/proveedores.js',
    ])

    <style>
        body { overflow-x: hidden; }

        #sidebar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 250px !important;
            height: 100vh !important;
            overflow-y: scroll !important;
            background-color: #343a40 !important;
            color: white !important;
            z-index: 1040 !important;
            scrollbar-width: thin !important;
            scrollbar-color: #f59e0b #212529 !important;
        }
        #sidebar::-webkit-scrollbar { width: 12px !important; }
        #sidebar::-webkit-scrollbar-track { background: #212529 !important; }
        #sidebar::-webkit-scrollbar-thumb { background: #f59e0b !important; border: 2px solid #212529 !important; border-radius: 6px !important; }

        .sidebar-link {
            color: white !important;
            display: block !important;
            padding: 7px 20px !important;
            text-decoration: none !important;
            white-space: nowrap !important;
            font-size: 13.5px !important;
        }
        .sidebar-link:hover {
            background-color: #495057 !important;
        }

        .main-content {
            margin-left: 250px;
            padding-top: 4.5rem;
        }
        @media (max-width: 767.98px) {
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>
    <div id="sidebar">
        @include('partials.sidebar')
    </div>

    <div class="offcanvas offcanvas-start w-100 d-md-none bg-dark text-white" tabindex="-1" id="mobileSidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Menú</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            @include('partials.sidebar')
        </div>
    </div>

    <div class="main-content">
        @include('partials.navbar')
        <main class="container-fluid pt-4">
            @yield('content')
        </main>
        @include('partials.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var sb = document.getElementById('sidebar');
            if (sb) {
                sb.scrollTop = 1;
                setTimeout(function() { sb.scrollTop = 0; }, 100);
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
