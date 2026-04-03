@php
$categorias = [
    [
        'nombre' => 'Corte',
        'slug' => 'corte',
        'subcategorias' => [
            ['nombre' => 'Sierras eléctricas', 'slug' => 'sierras-electricas'],
            ['nombre' => 'Plasma CNC', 'slug' => 'plasma-cnc'],
            ['nombre' => 'Guillotinas', 'slug' => 'guillotinas'],
        ],
    ],
    [
        'nombre' => 'Soldadura',
        'slug' => 'soldadura',
        'subcategorias' => [
            ['nombre' => 'Máquinas MIG', 'slug' => 'mig'],
            ['nombre' => 'Inversores', 'slug' => 'inversores'],
            ['nombre' => 'Accesorios', 'slug' => 'accesorios-soldadura'],
        ],
    ],
    [
        'nombre' => 'Construcción',
        'slug' => 'construccion',
        'subcategorias' => [
            ['nombre' => 'Compactadoras', 'slug' => 'compactadoras'],
            ['nombre' => 'Mezcladoras', 'slug' => 'mezcladoras'],
            ['nombre' => 'Vibradores', 'slug' => 'vibradores'],
        ],
    ],
    [
        'nombre' => 'Carpintería',
        'slug' => 'carpinteria',
        'subcategorias' => [
            ['nombre' => 'Cepillos', 'slug' => 'cepillos'],
            ['nombre' => 'Fresadoras', 'slug' => 'fresadoras'],
            ['nombre' => 'Lijadoras', 'slug' => 'lijadoras'],
        ],
    ],
];

@endphp
<nav class="navbar navbar-expand-lg navbar-dark bg-dark header-main">
    <div class="container">
        <a class="navbar-brand text-warning" href="{{ url('/') }}">EquiposIndustriales</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">

                <li class="nav-item">
                    <a class="nav-link text-warning hover-bg" href="{{ url('/') }}">Inicio</a>
                </li>

                <!-- Mega Categorías -->
<li class="nav-item dropdown-mega">
    <div class="dropdown-mega-trigger">
        <a href="{{ url('/categorias') }}" class="nav-link text-warning hover-bg">Categorías</a>

        <div class="dropdown-menu mega-menu">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                @foreach ($categorias as $categoria)
                    <div>
                        <a href="{{ url('categorias/' . $categoria['slug']) }}"
                           class="text-warning fw-bold d-block mb-2 hover-bg px-2 py-1 rounded">
                            {{ $categoria['nombre'] }}
                        </a>
                        <ul class="list-unstyled">
                            @foreach ($categoria['subcategorias'] as $sub)
                                <li>
                                    <a href="{{ url('subcategorias/' . $sub['slug']) }}"
                                       class="text-light d-block hover-bg px-2 py-1 rounded small">
                                        {{ $sub['nombre'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</li>

                <li class="nav-item">
                    <a class="nav-link text-warning hover-bg" href="{{ url('/ofertas') }}">Ofertas</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-warning hover-bg" href="{{ url('/contacto') }}">Contacto</a>
                </li>

               <!--  <li class="nav-item">
                    <a class="nav-link text-warning hover-bg" href="{{ url('/login') }}">Iniciar sesión</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-warning hover-bg" href="{{ url('/register') }}">Registrarse</a>
                </li> -->
   @if(session('usuario'))
    <!-- Usuario logueado -->
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-warning hover-bg" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{ session('usuario')->nombre }}
        </a>
        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="{{ url('/perfil') }}">👤 Mi perfil</a></li>
            <li><a class="dropdown-item" href="{{ url('/pedidos') }}">📦 Mis pedidos</a></li>
            <li>
                <form action="{{ url('/logout') }}" method="POST">
                    @csrf
                    <button class="dropdown-item" type="submit">🚪 Salir</button>
                </form>
            </li>
        </ul>
    </li>
@else
    <!-- Invitado -->
    <li class="nav-item">
        <a class="nav-link text-warning hover-bg" href="{{ url('/login') }}">Iniciar sesión</a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-warning hover-bg" href="{{ url('/register') }}">Registrarse</a>
    </li>
@endif


                <!-- lllito -->
            </ul>
        </div>

        <div class="d-flex align-items-center">
            <a href="{{ url('/carrito') }}" class="nav-link position-relative text-warning">
                <i class="bi bi-cart4 fs-4"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    3
                </span>
            </a>
        </div>
    </div>
</nav>
