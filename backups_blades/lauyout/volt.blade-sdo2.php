{{-- resources/views/layouts/volt.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Panel de Administración')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Volt CSS --}}
    <link rel="stylesheet" href="{{ asset('vendor/volt-assets/css/volt.css') }}">
</head>
<body>
    {{-- Sidebar de Volt --}}
    @include('partials.sidebar')

    <main class="content">
        {{-- Navbar superior de Volt --}}
        @include('partials.navbar')

        <div class="py-4">
            @yield('content')
        </div>

        {{-- Footer si lo necesitas --}}
        @include('partials.footer')
    </main>

    {{-- Volt JS --}}
    <script src="{{ asset('vendor/volt-assets/js/volt.js') }}"></script>
</body>
</html>
