<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel | Mono Tingales</title>

    <!-- Volt Admin CSS -->
    <link rel="stylesheet" href="{{ asset('volt/assets/css/volt.css') }}">
    <link rel="stylesheet" href="{{ asset('volt/assets/vendor/fontawesome/css/all.min.css') }}">
    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">

    @stack('styles')
</head>
<body>

    {{-- Sidebar --}}
    <div class="d-flex" id="wrapper">
        @include('partials.sidebar')

        <div id="page-content-wrapper" class="flex-grow-1">
            {{-- Navbar --}}
            @include('partials.navbar')

            {{-- Contenido principal --}}
            <main class="content p-4">
                <div class="container-fluid pt-4">
                    @yield('content')
                </div>
            </main>

            {{-- Footer --}}
            @include('partials.footer')
        </div>
    </div>

    <!-- Scripts Volt -->
    <script src="{{ asset('volt/assets/js/volt.js') }}"></script>
    <script src="{{ asset('volt/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

    @stack('scripts')
</body>
</html>
