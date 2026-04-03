<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Panel | Mono Tingales</title>

  <!-- Bootstrap y Font Awesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon" />
  @stack('styles')
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])

  <style>
    body {
      overflow-x: hidden;
    }

    #sidebar {
      background-color: #343a40;
      color: white;
      padding-top: 4.5rem;
    }

    .sidebar-link {
      color: white;
      display: block;
      padding: 10px 20px;
      text-decoration: none;
    }

    .sidebar-link:hover {
      background-color: #495057;
    }

    .main-content {
      margin-left: 250px;
      padding-top: 4.5rem;
    }

    @media (max-width: 767.98px) {
      .main-content {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>
  {{-- Sidebar permanente en escritorio y offcanvas en móviles --}}
  <div class="d-none d-md-block position-fixed top-0 start-0 h-100" style="width: 250px;" id="sidebar">
    @include('partials.sidebar')
  </div>

  {{-- Offcanvas Sidebar para móviles --}}
  <!-- <div class="offcanvas offcanvas-start d-md-none bg-dark text-white" tabindex="-1" id="mobileSidebar"> -->
    {{-- Offcanvas Sidebar para móviles --}}
<div class="offcanvas offcanvas-start w-100 d-md-none bg-dark text-white" tabindex="-1" id="mobileSidebar">

    <div class="offcanvas-header">
      <h5 class="offcanvas-title">Menú</h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
      @include('partials.sidebar')
    </div>
  </div>

  {{-- Contenido principal --}}
  <div class="main-content">
    {{-- Navbar --}}
    @include('partials.navbar')

    <main class="content container-fluid pt-4">
      @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.footer')
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
  @yield('scripts')
</body>
</html>
