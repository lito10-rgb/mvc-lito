<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="{{ route('home') }}">MiTienda</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="{{ route('home') }}">Inicio</a>
        </li>

        {{-- Mega menú de categorías --}}
        <li class="nav-item dropdown mega-dropdown position-static">
          <a class="nav-link" href="#">Categorías</a>

          <div class="dropdown-menu mega-menu p-4">
            <div class="row">

              @foreach($categorias as $categoria)

                <div class="col-6 col-md-3 mb-3">
                  <h6 class="fw-bold">
                    <a href="{{ route('categoria.show', $categoria->id) }}" class="text-dark text-decoration-none">
                      {{ $categoria->categoria }}
                       @dump($categoria->subcategorias)
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
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#">Ofertas</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#">Contacto</a>
        </li>
      </ul>

      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="#">Iniciar sesión</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Registrarse</a></li>
        <li class="nav-item"><a class="nav-link" href="#">🛒 ({{ count(session('cart', [])) }})</a></li>
      </ul>
    </div>
  </div>
</nav>
