<nav class="navbar navbar-top navbar-expand navbar-dashboard navbar-dark bg-gray-800 border-bottom">
    <div class="container-fluid px-3">
        <div class="d-flex justify-content-between w-100">
            <div>
                <h1 class="h4 text-white mb-0">Panel de Administración</h1>
            </div>
            <ul class="navbar-nav align-items-center">
                <li class="nav-item dropdown ms-lg-3">
                    <a class="nav-link dropdown-toggle pt-1 px-0" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="media d-flex align-items-center">
                            <img class="avatar rounded-circle" alt="Avatar" src="{{ asset('img/default-avatar.png') }}" height="40">
                            <div class="media-body ms-2 text-white align-items-center d-none d-lg-block">
                                <span class="mb-0">Admin</span>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Mi perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Cerrar sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
