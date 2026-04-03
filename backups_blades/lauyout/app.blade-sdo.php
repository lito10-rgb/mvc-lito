<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Equipos y máquinas para plantas de proceso.">
    <meta name="keywords" content="equipos, máquinas, planta de proceso, industria, producción">
    <title>@yield('title', 'Inicio')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header class="bg-dark text-white text-center py-4">
        <h1 class="display-5">Equipos y Máquinas para tu Planta</h1>
        <p>Soluciones industriales para potenciar tu producción</p>
    </header>
    <main class="container py-5">
        @yield('content')
    </main>
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 Equipos Industriales. Síguenos en
        <a href="#" class="text-warning">Facebook</a> |
        <a href="#" class="text-warning">Instagram</a></p>
    </footer>
</body>
</html>
