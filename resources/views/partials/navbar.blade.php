<nav class="navbar navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <button class="btn btn-outline-light d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
      <i class="fas fa-bars"></i>
    </button>
    <a class="navbar-brand ms-2" href="{{ route('admin.dashboard') }}">
      <i class="fas fa-crown text-warning me-2"></i>Admin Panel
    </a>

    <div class="dropdown ms-auto me-2">
      <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
        <i class="fas fa-user-circle fa-lg me-2"></i>
        <strong>{{ Auth::user()->nombre ?? Auth::user()->name ?? 'Admin' }}</strong>
      </a>
      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark text-small shadow">
        <li><a class="dropdown-item" href="{{ url('/') }}" target="_blank"><i class="fas fa-external-link-alt me-2"></i> Ver Sitio</a></li>
        <li><hr class="dropdown-divider" /></li>
        <li>
          <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</nav>
