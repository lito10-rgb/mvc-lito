<nav class="navbar navbar-expand-lg navbar-dark bg-dark header-main">
    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('images/dcondor-peru.png') }}" 
                 alt="Mono Tingales"
                 style="height:70px;">
        </a>

        <!-- Botón mobile -->
        <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">

            <ul class="navbar-nav align-items-lg-center">

                <!-- Inicio -->
                <li class="nav-item">
                    <a class="nav-link text-warning hover-bg" href="{{ url('/') }}">
                        Inicio
                    </a>
                </li>

                <!-- MEGAMENU CATEGORIAS -->
                <li class="nav-item mega-wrapper">

                    <a class="nav-link text-warning hover-bg" href="#">
                        Categorías
                    </a>

                    <div class="mega-menu">

                        <div class="row">

                            @foreach($menuCategorias->chunk(ceil($menuCategorias->count()/4)) as $chunk)

                                <div class="col-lg-3">

                                    @foreach($chunk as $categoria)

                                        <div class="mega-item">

                                            <a href="{{ route('productos.buscar', ['categoria' => $categoria->nombre]) }}"
                                               class="mega-link">
                                                {{ $categoria->nombre }}
                                            </a>

                                            @if($categoria->subcategorias->count())

                                                <div class="submenu-lateral">

                                                    @foreach($categoria->subcategorias as $sub)

                                                        <a href="{{ route('productos.buscar', ['subcategoria' => $sub->subcategoria]) }}">
                                                            {{ $sub->subcategoria }}
                                                        </a>

                                                    @endforeach

                                                </div>

                                            @endif

                                        </div>

                                    @endforeach

                                </div>

                            @endforeach

                        </div>

                    </div>

                </li>

                <!-- Ofertas -->
                <li class="nav-item">
                    <a class="nav-link text-warning hover-bg" href="{{ url('/ofertas') }}">
                        Ofertas
                    </a>
                </li>

                <!-- Contacto -->
                <li class="nav-item">
                    <a class="nav-link text-warning hover-bg" href="{{ route('contacto.index') }}">
                        Contacto
                    </a>
                </li>

                <!-- LOGIN -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="{{ route('login') }}">
                            Iniciar sesión
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-warning" href="{{ route('register') }}">
                            Registrarse
                        </a>
                    </li>
                @endguest

                <!-- USUARIO -->
                @auth
                    <li class="nav-item dropdown">

                        <a class="nav-link dropdown-toggle text-warning"
                           data-bs-toggle="dropdown">
                           {{ Auth::user()->nombre }}
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">

                            <li>
                                <a class="dropdown-item" href="{{ route('perfil') }}">
                                    Mi Perfil
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="#">
                                    Mis pedidos
                                </a>
                            </li>

                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item">
                                        Salir
                                    </button>
                                </form>
                            </li>

                        </ul>

                    </li>
                @endauth

            </ul>

        </div>

        <!-- CARRITO DERECHA -->
        <div class="d-flex align-items-center ms-auto">

            <cart-counter></cart-counter>

        </div>

    </div>
</nav>