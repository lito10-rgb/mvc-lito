<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="{{ route('home') }}">MiTienda</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Inicio</a></li>

       <!--  <li class="nav-item mega-dropdown position-static">
          <a class="nav-link" href="#">Categorías</a>
          <div class="dropdown-menu mega-menu p-4">
            <div class="row">
              @foreach($categorias as $categoria)
                <div class="col-6 col-md-3 mb-3">
                  <h6 class="fw-bold">
                    <a href="{{ route('categoria.show', $categoria->id) }}" class="text-dark text-decoration-none">
                      {{ $categoria->categoria }}
                    </a>
                  </h6>
                  <ul class="list-unstyled">
                    @foreach($categoria->subcategorias as $sub)
                      <li>
                        <a href="{{ route('subcategoria.show', $sub->ruta) }}" class="dropdown-item px-0 text-muted">
                          {{ $sub->subcategoria }}
                        </a>
                      </li>
                    @endforeach
                  </ul>
                </div>
              @endforeach
            </div>
          </div>
        </li> --><!-- 
                      <li class="nav-item dropdown mega-dropdown">
    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Categorías</a>

    <div class="dropdown-menu mega-menu w-100">
        <div class="row">
            @foreach($categorias as $categoria)
                <div class="col-md-3 position-relative">
                    <a href="{{ route('categoria.show', ['id' => $categoria->id]) }}" class="dropdown-item">
                        {{ $categoria->name }}
                    </a>

                    @if($categoria->subcategorias->count())
                        <ul class="position-absolute bg-white shadow p-3"
                            style="top: 0; left: 100%; display: none; z-index: 1000;">
                            @foreach($categoria->subcategorias as $subcategoria)
                                <li>
                                    <a href="{{ route('subcategoria.show', ['id' => $subcategoria->id]) }}">
                                        {{ $subcategoria->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</li>
 -->
 <!-- <li class="nav-item dropdown mega-dropdown" x-data="{ open: false }"
    @mouseenter="open = true" @mouseleave="open = false">
    
    <a href="#" class="nav-link dropdown-toggle" @click.prevent="open = !open">
        Categorías
    </a>

    <div class="dropdown-menu mega-menu w-100" x-show="open" x-cloak>
        <div class="row">
            <div class="col-md-3 position-relative">
                <a href="/categoria/ropa" class="dropdown-item fw-bold">Ropa</a>
                <ul class="subcategory-menu">
                    <li><a href="/subcategoria/camisas">Camisas</a></li>
                    <li><a href="/subcategoria/pantalones">Pantalones</a></li>
                </ul>
            </div>

            <div class="col-md-3 position-relative">
                <a href="/categoria/tecnologia" class="dropdown-item fw-bold">Tecnología</a>
                <ul class="subcategory-menu">
                    <li><a href="/subcategoria/laptops">Laptops</a></li>
                    <li><a href="/subcategoria/smartphones">Smartphones</a></li>
                </ul>
            </div>

            <div class="col-md-3 position-relative">
                <a href="/categoria/hogar" class="dropdown-item fw-bold">Hogar</a>
                <ul class="subcategory-menu">
                    <li><a href="/subcategoria/cocina">Cocina</a></li>
                    <li><a href="/subcategoria/decoracion">Decoración</a></li>
                </ul>
            </div>
        </div>
    </div>
</li> -->
<li class="nav-item dropdown mega-dropdown" x-data="{ open: false }"
    @mouseenter.window="if(window.innerWidth >= 992) open = true"
    @mouseleave.window="if(window.innerWidth >= 992) open = false">

    <a href="#" class="nav-link dropdown-toggle"
       @click.prevent="open = !open">
        Categorías
    </a>

    <div class="dropdown-menu mega-menu w-100"
         :class="{ 'd-block': open }" x-cloak>
        <div class="row">
            <div class="col-md-3 position-relative">
                <a href="/categoria/ropa" class="dropdown-item fw-bold">Ropa</a>
                <ul class="subcategory-menu">
                    <li><a href="/subcategoria/camisas">Camisas</a></li>
                    <li><a href="/subcategoria/pantalones">Pantalones</a></li>
                </ul>
            </div>

            <div class="col-md-3 position-relative">
                <a href="/categoria/tecnologia" class="dropdown-item fw-bold">Tecnología</a>
                <ul class="subcategory-menu">
                    <li><a href="/subcategoria/laptops">Laptops</a></li>
                    <li><a href="/subcategoria/smartphones">Smartphones</a></li>
                </ul>
            </div>

            <div class="col-md-3 position-relative">
                <a href="/categoria/hogar" class="dropdown-item fw-bold">Hogar</a>
                <ul class="subcategory-menu">
                    <li><a href="/subcategoria/cocina">Cocina</a></li>
                    <li><a href="/subcategoria/decoracion">Decoración</a></li>
                </ul>
            </div>
        </div>
    </div>
</li>


        <li class="nav-item"><a class="nav-link" href="#">Ofertas</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Contacto</a></li>
      </ul>

      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Iniciar sesión</a>
</li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/register') }}">Registrarse</a></li>
        <li class="nav-item"><a class="nav-link" href="#">🛒 ({{ count(session('cart', [])) }})</a></li>
      </ul>
    </div>
  </div>
</nav>



