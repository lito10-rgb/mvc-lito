<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel | Mono Tingales</title>

    {{-- Volt Admin CSS --}}
    <link rel="stylesheet" href="{{ asset('volt/assets/css/volt.css') }}">
    <link rel="stylesheet" href="{{ asset('volt/assets/vendor/fontawesome/css/all.min.css') }}">
    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">
    <style>
        .main-content {
            margin-left: 260px; /* Ajusta según el ancho del sidebar */
        }

        .content {
            padding-top: 4.5rem; /* Espacio para el navbar */
            min-height: 100vh;
        }
    </style>
    {{-- Estilos adicionales --}}
    @stack('styles')
</head>
<body>

    {{-- Sidebar --}}
    @include('partials.sidebar')

    <div class="main-content">
        {{-- Navbar --}}
        @include('partials.navbar')

        {{-- Contenido principal --}}
        <main class="content">
            <div class="container-fluid pt-4">
                @yield('content')
            </div>
        </main>

        {{-- Footer --}}
        @include('partials.footer')
    </div>

    {{-- Volt JS --}}
    <script src="{{ asset('volt/assets/js/volt.js') }}"></script>
    <script src="{{ asset('volt/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('volt/assets/vendor/onscreen/dist/on-screen.umd.min.js') }}"></script>

    {{-- Scripts adicionales --}}
    @stack('scripts')
</body>
</html>