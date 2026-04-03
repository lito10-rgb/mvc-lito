<nav class="navbar navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <button class="btn btn-outline-light d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
      <i class="fas fa-bars"></i>
    </button>
    <a class="navbar-brand ms-2" href="#">Mono Tingales</a>

    <div class="dropdown ms-auto me-2">
      <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
        <img src="{{ asset('img/avatar.png') }}" alt="avatar" width="32" height="32" class="rounded-circle me-2" />
        <strong>Usuario</strong>
      </a>
      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark text-small shadow">
        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Perfil</a></li>
        <li><hr class="dropdown-divider" /></li>
        <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt me-2"></i> Salir</a></li>
      </ul>
    </div>
  </div>
</nav>
