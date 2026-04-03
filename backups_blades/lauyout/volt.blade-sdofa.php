<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel | Mono Tingales</title>
    <!--<link rel="stylesheet" href="{{ asset('volt/assets/css/volt.css') }}">
    <link rel="stylesheet" href="{{ asset('volt/assets/vendor/fontawesome/css/all.min.css') }}">-->
    <!-- Volt Bootstrap desde CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome desde CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<!-- esto -->
    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">
    @stack('styles')

    <style>
        .main-content {
            margin-left: 250px; /* Ajuste para sidebar */
        }
        .content {
            padding-top: 4.5rem; /* Ajuste para navbar */
            min-height: 100vh;
        }
/*lito*/
    @media (min-width: 768px) {
        .main-content {
            margin-left: 250px;
        }
    }
    .content {
        padding-top: 4.5rem;
        min-height: 100vh;
    }
    
    </style>

</head>
<body>

    {{-- Sidebar --}}
    @include('partials.sidebar')

    <div class="main-content" style="margin-left: 0;">
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

    <!-- {{-- JS --}}
    <script src="{{ asset('volt/assets/js/volt.js') }}"></script>
    <script src="{{ asset('volt/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('volt/assets/vendor/onscreen/dist/on-screen.umd.min.js') }}"></script>
    @stack('scripts') -->
    <!-- Bootstrap JS -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoA6A1mcHzcWnKj9GF8lF5cfJZQ1I1vbI1en8Ay9e+3sm5b" crossorigin="anonymous"></script>

</body>
</html>
