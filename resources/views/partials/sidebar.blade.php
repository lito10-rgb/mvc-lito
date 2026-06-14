<div class="d-flex flex-column">
    <div class="px-3 py-3 text-center border-bottom border-secondary">
        <i class="fas fa-crown fa-2x text-warning"></i>
        <span class="d-block text-white fw-bold mt-1">Admin Panel</span>
    </div>

    <nav class="mt-2">
        <div class="px-3 py-2 text-secondary small text-uppercase fw-bold">Principal</div>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
        </a>

        <div class="px-3 py-2 text-secondary small text-uppercase fw-bold mt-2">Gestión</div>
        <a href="{{ route('admin.productos.index') }}" class="sidebar-link">
            <i class="fas fa-boxes me-2"></i> Productos
        </a>
        <a href="{{ route('admin.categorias.index') }}" class="sidebar-link">
            <i class="fas fa-tags me-2"></i> Categorías
        </a>
        <a href="{{ route('admin.subcategorias.index') }}" class="sidebar-link">
            <i class="fas fa-sitemap me-2"></i> Subcategorías
        </a>
        <a href="{{ route('admin.marcas.index') }}" class="sidebar-link">
            <i class="fas fa-trademark me-2"></i> Marcas
        </a>
        <a href="{{ route('admin.proveedores.index') }}" class="sidebar-link">
            <i class="fas fa-truck me-2"></i> Proveedores
        </a>

        <div class="px-3 py-2 text-secondary small text-uppercase fw-bold mt-2">Usuarios</div>
        <a href="{{ route('admin.usuarios.index') }}" class="sidebar-link">
            <i class="fas fa-users me-2"></i> Usuarios
        </a>
        <a href="{{ route('admin.usuarios.asignar.view') }}" class="sidebar-link">
            <i class="fas fa-user-tag me-2"></i> Asignar Roles
        </a>

        <div class="px-3 py-2 text-secondary small text-uppercase fw-bold mt-2">Contenido</div>
        <a href="{{ route('admin.posts.index') }}" class="sidebar-link">
            <i class="fas fa-blog me-2"></i> Posts / Blog
        </a>

        <div class="px-3 py-2 text-secondary small text-uppercase fw-bold mt-2">Sistema</div>
        <a href="{{ url('/') }}" class="sidebar-link" target="_blank">
            <i class="fas fa-external-link-alt me-2"></i> Ver Sitio
        </a>
        <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="sidebar-link border-0 bg-transparent w-100 text-start">
                <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
            </button>
        </form>
    </nav>
</div>
