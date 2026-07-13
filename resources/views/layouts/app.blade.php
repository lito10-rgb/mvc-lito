<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Equipos Industriales')</title>
    <!-- Token CSRF para peticiones AJAX -->
    <!-- <meta name="app-base" content="{{ url('') }}/public"> -->
    <!-- <meta name="app-base" content="{{ url('') }}/public">
<script>window.APP_BASE = document.querySelector('meta[name="app-base"]').getAttribute('content').replace(/\/$/, '');</script>

    <meta name="csrf-token" content="{{ csrf_token() }}"> -->
    <!-- Bootstrap 5 -->
      <meta name="app-base" content="{{ rtrim(config('app.url'), '/') }}">
  <script>window.APP_BASE = document.querySelector('meta[name="app-base"]').getAttribute('content').replace(/\/$/, '');</script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="carrito-url" content="{{ url('carrito') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
        :root {
            --theme-primary: {{ config('theme.colors.primary') }};
            --theme-secondary: {{ config('theme.colors.secondary') }};
            --theme-accent: {{ config('theme.colors.accent') }};
            --theme-accent-light: {{ config('theme.colors.accent_light') }};
        }
    </style>

    <!-- Vite: compilar SCSS y JS UNA sola vez -->
    @vite([
    'resources/scss/app.scss',
    'resources/js/app.js',
    'resources/js/carrito-actions.js',
    'resources/js/fallback-counter.js',
    'resources/js/fallback-thumbs.js'
])

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/css/all.min.css">
</head>
<body>
    <!-- IMPORTANTE: El contenedor #app-vue envuelve el header y el contenido donde usarás componentes Vue -->
    <div id="app-vue">
        @include('partials.header')

        <main class="min-h-screen">
            @yield('content')
        </main>

        @include('partials.footer')
    </div>

    <!-- Scripts adicionales -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
    @yield('scripts')

    <!-- NO repetir @@vite aquí: ya se cargó en el head (si prefieres mover a bottom, quita el de head) -->
</body>
</html>
