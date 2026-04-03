<nav class="sidebar d-lg-block bg-gray-800 text-white collapse" id="sidebarMenu">
    <div class="position-sticky">
        <div class="list-group list-group-flush mx-3 mt-4">
            <a href="{{ route('admin.productos.index') }}" class="list-group-item list-group-item-action bg-gray-800 text-white border-0 d-flex align-items-center">
                <i class="fas fa-box me-2"></i> Productos
            </a>
            {{-- Agrega más enlaces aquí --}}
            <a href="{{ route('home') }}" class="list-group-item list-group-item-action bg-gray-800 text-white border-0 d-flex align-items-center">
                <i class="fas fa-home me-2"></i> Ir al sitio
            </a>
        </div>
    </div>
</nav>
