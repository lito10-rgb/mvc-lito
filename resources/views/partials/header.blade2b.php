<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm custom-navbar">
    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand fw-bold text-primary" href="{{ url('/')}}">
            <img src="{{ asset('images/dcondor-peru.png') }}" 
         alt="Mono Tingales" 
         class="d-inline-block align-text-top" 
         style="height: 70px;">
        </a>

        <!-- Mobile toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">

            <ul class="navbar-nav mx-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">Inicio</a>
                </li>

                <!-- MEGAMENU CATEGORIAS -->
              <li class="nav-item mega-wrapper">

                    <a class="nav-link" href="#">
                        Categorías
                    </a>

                    <div class="mega-menu shadow-lg">
                        <div class="row">

                            @foreach($categorias->chunk(ceil($categorias->count()/4)) as $chunk)
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
                                                            {{ $sub->subcategoria}}
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

                <li class="nav-item">
                    <a class="nav-link" href="#">Ofertas</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Contacto</a>
                </li>

            </ul>

            <!-- Right -->
            <ul class="navbar-nav align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Iniciar sesión</a>
                </li>

                <li class="nav-item">
                    <a class="btn btn-outline-primary btn-sm ms-lg-2" href="{{ route('register') }}">
                        Registrarse
                    </a>
                </li>

                <li class="nav-item ms-lg-3">
                    <a href="{{ route('carrito.index') }}" class="cart-icon">
                        <i class="bi bi-cart3"></i>
                    </a>
                </li>

            </ul>

        </div>
    </div>
</nav>