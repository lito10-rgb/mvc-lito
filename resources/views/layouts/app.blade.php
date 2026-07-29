<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Equipos Industriales')</title>
    <meta name="description" content="@yield('description', '')">
    <meta name="keywords" content="@yield('keywords', '')">
    @yield('meta')
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
            --theme-primary: {{ negocio_color('primary', config('theme.colors.primary')) }};
            --theme-secondary: {{ negocio_color('secondary', config('theme.colors.secondary')) }};
            --theme-accent: {{ negocio_color('accent', config('theme.colors.accent')) }};
            --theme-accent-light: {{ negocio_color('accent_light', config('theme.colors.accent_light')) }};
            --theme-header-bg: {{ negocio_theme('color_header_bg', '#1a1a1a') }};
            --theme-footer-bg: {{ negocio_theme('color_footer_bg', '#1a1a1a') }};
            --theme-nav-btn: {{ negocio_color('nav_btn', '#D4A373') }};
            --theme-nav-btn-texto: {{ negocio_color('nav_btn_texto', '#000000') }};
            --theme-nav-texto: {{ negocio_color('nav_texto', '#D4A373') }};
        }

        .bg-theme-dark { background-color: var(--theme-header-bg) !important; }
        .bg-theme-footer { background-color: var(--theme-footer-bg) !important; }
        .bg-theme-primary { background-color: var(--theme-primary) !important; }
        .text-theme-primary { color: var(--theme-primary) !important; }
        .text-theme-secondary { color: var(--theme-secondary) !important; }
        .text-theme-accent { color: var(--theme-accent) !important; }
        .btn-theme-accent { background-color: var(--theme-accent) !important; border-color: var(--theme-accent) !important; color: #000 !important; }
        .btn-theme-accent:hover { background-color: var(--theme-accent-light) !important; border-color: var(--theme-accent-light) !important; color: #000 !important; }
        .btn-cta-accent { background-color: var(--theme-accent) !important; border-color: var(--theme-accent) !important; color: #000 !important; transition: filter 0.3s ease, transform 0.2s ease; }
        .btn-cta-accent:hover { background-color: var(--theme-accent) !important; border-color: var(--theme-accent) !important; color: #000 !important; filter: brightness(0.85) !important; transform: scale(1.03); }
        .hero a.btn:hover { filter: brightness(0.85) !important; transform: scale(1.03); transition: filter 0.3s ease, transform 0.2s ease; }
        .btn-outline-theme-accent { color: var(--theme-accent); border-color: var(--theme-accent); }
        .btn-outline-theme-accent:hover { background-color: var(--theme-accent); color: #000; }
        .border-theme-accent { border-color: var(--theme-accent) !important; }
        .header-main { background: var(--theme-header-bg) !important; }
        .header-main .nav-link { color: var(--theme-nav-texto) !important; }
        .header-main .nav-link:hover { color: var(--theme-accent-light) !important; }
        .nav-btn {
            display: inline-block;
            padding: 0.35rem 1rem;
            background-color: var(--theme-nav-btn);
            color: var(--theme-nav-btn-texto) !important;
            border-radius: 0.4rem;
            font-weight: 500;
            text-decoration: none;
            transition: background-color 0.2s;
            margin: 0.15rem;
        }
        .nav-btn:hover {
            filter: brightness(1.2);
            color: var(--theme-nav-btn-texto) !important;
        }
        .nav-btn.dropdown-toggle::after {
            margin-left: 0.5em;
            vertical-align: middle;
        }
        .logo-negocio { height: {{ negocio_theme('logo_height', '70') }}px; transition: filter 0.3s ease, transform 0.2s ease; }
        .logo-negocio:hover { filter: brightness(1.15); transform: scale(1.03); }
        @media (max-width: 767.98px) {
            .logo-negocio { height: 50px; }
        }
        .mega-menu { background-color: var(--theme-primary) !important; }
        .mega-link { color: #fff !important; padding-left: 1.8rem !important; position: relative; }
        .mega-link::before { content: '▸'; position: absolute; left: 0.5rem; color: var(--theme-accent); font-weight: bold; }
        .mega-link:hover { background-color: var(--theme-accent) !important; color: #000 !important; }
        .mega-link:hover::before { color: #000; }
        .submenu-lateral { background-color: color-mix(in srgb, var(--theme-primary) 85%, white) !important; }
        .submenu-lateral a { color: #fff !important; padding-left: 1.5rem !important; position: relative; display: block; }
        .submenu-lateral a::before { content: '▹'; position: absolute; left: 0.3rem; color: var(--theme-accent); font-weight: bold; font-size: 1rem; line-height: 1; top: 0.35rem; }
        .submenu-lateral a:hover { background-color: var(--theme-accent) !important; color: #000 !important; }
        .submenu-lateral a:hover::before { color: #000; }
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
