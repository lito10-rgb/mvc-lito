<nav id="sidebarMenu" class="sidebar d-md-block bg-primary text-white collapse" data-simplebar>
    <div class="sidebar-inner px-4 pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link text-white">
                    <i class="fas fa-home me-2"></i> Panel
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.productos.index') }}" class="nav-link text-white">
                    <i class="fas fa-box me-2"></i> Productos
                </a>
            </li>
            <!-- Más ítems... -->
        </ul>
    </div>
</nav>
