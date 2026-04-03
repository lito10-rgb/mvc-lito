<nav class="navbar navbar-top navbar-expand navbar-dashboard navbar-dark bg-primary px-4">
    <div class="container-fluid px-0">
       <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
</button>

        <div class="d-flex align-items-center ms-auto">
            <!-- Usuario / avatar -->
            <div class="dropdown">
                <a class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" href="#" data-bs-toggle="dropdown">
                    <img src="{{ asset('img/avatar.png') }}" alt="Avatar" class="avatar rounded-circle me-2">
                    <span>Admin</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">Perfil</a></li>
                    <li><a class="dropdown-item" href="#">Salir</a></li>
                </ul>
            </div>
        </div>
    </div>
    
</nav>
