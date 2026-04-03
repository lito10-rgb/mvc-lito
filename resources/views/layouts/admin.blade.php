<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin')</title>

    <!-- Bootstrap + Volt -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('volt/assets/css/volt.css') }}" rel="stylesheet">
   <!-- <meta name="csrf-token" content="{{ csrf_token() }}">
       <!~~ CSRF y URL para JS ~~>
    <meta name="route-eliminar" content="{{ route('admin.productos.eliminarMultiple') }}">-->
   <meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="route-eliminar" content="{{ url('/mvc-lito/public/admin/productos/eliminar-multiple') }}">
    @vite([
    'resources/scss/app.scss',
    'resources/js/proveedores.js',
    'resources/js/app.js'
])
</head>
<body>
    @include('partials.sidebar') {{-- barra lateral --}}
    @include('partials.navbar')  {{-- navbar superior --}}

    <main class="content">
        @yield('content')
    </main>

    @include('partials.footer')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('volt/assets/js/volt.js') }}"></script>
</body>
</html>
