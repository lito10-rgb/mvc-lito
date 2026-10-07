<div class="d-flex flex-column">
    <div class="px-3 py-2 text-center border-bottom border-secondary">
        <i class="fas fa-crown fa-2x text-warning"></i>
        <span class="d-block text-white fw-bold mt-1">Admin Panel</span>
    </div>

    <nav class="mt-1 pb-3">
        @if(auth()->user()->puede('negocios.gestionar'))
        <a href="{{ route('admin.negocios.index') }}" class="sidebar-link" style="background:#dc3545;color:#fff!important;">
            <i class="fas fa-store me-2"></i> ⚡ NEGOCIOS (prueba)
        </a>
        @endif
        <div class="px-3 py-2 text-secondary small text-uppercase fw-bold">Principal</div>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
            <i class="fas fa-gauge-high me-2"></i> Dashboard
        </a>

        <div class="px-3 py-1 text-secondary small text-uppercase fw-bold mt-2">Gestión</div>
        @if(auth()->user()->puede('pedidos.gestionar'))
        <a href="{{ route('admin.pedidos.index') }}" class="sidebar-link">
            <i class="fas fa-shopping-cart me-2"></i> Pedidos
        </a>
        @endif
        @if(auth()->user()->puede('envios.gestionar'))
        <a href="{{ route('admin.tipos-envio.index') }}" class="sidebar-link">
            <i class="fas fa-truck me-2"></i> Tipos de Envío
        </a>
        <a href="{{ route('admin.tarifas-envio.index') }}" class="sidebar-link">
            <i class="fas fa-dollar-sign me-2"></i> Tarifas de Envío
        </a>
        @endif
        @if(auth()->user()->puede('productos.ver'))
        <a href="{{ route('admin.productos.index') }}" class="sidebar-link">
            <i class="fas fa-boxes-stacked me-2"></i> Productos
        </a>
        @endif
        @if(auth()->user()->puede('ofertas.gestionar'))
        <a href="{{ route('admin.ofertas.index') }}" class="sidebar-link">
            <i class="fas fa-tags me-2 text-warning"></i> Ofertas
        </a>
        @endif
        @if(auth()->user()->puede('cupones.gestionar'))
        <a href="{{ route('admin.cupones.index') }}" class="sidebar-link">
            <i class="fas fa-ticket me-2 text-success"></i> Cupones
        </a>
        @endif
        @if(auth()->user()->puede('categorias.gestionar'))
        <a href="{{ route('admin.categorias.index') }}" class="sidebar-link">
            <i class="fas fa-tags me-2"></i> Categorías
        </a>
        @endif
        @if(auth()->user()->puede('subcategorias.gestionar'))
        <a href="{{ route('admin.subcategorias.index') }}" class="sidebar-link">
            <i class="fas fa-sitemap me-2"></i> Subcategorías
        </a>
        @endif
        @if(auth()->user()->puede('marcas.gestionar'))
        <a href="{{ route('admin.marcas.index') }}" class="sidebar-link">
            <i class="fas fa-copyright me-2"></i> Marcas
        </a>
        @endif
        @if(auth()->user()->puede('catalogos.ver'))
        <a href="{{ route('admin.catalogos.index') }}" class="sidebar-link">
            <i class="fas fa-book me-2"></i> Catálogo PDF
        </a>
        <a href="{{ route('admin.ficha-tecnica.cafe') }}" class="sidebar-link" target="_blank">
            <i class="fas fa-file-pdf me-2"></i> Ficha Técnica Café
        </a>
        @endif
        @if(auth()->user()->puede('proveedores.gestionar'))
        <a href="{{ route('admin.proveedores.index') }}" class="sidebar-link">
            <i class="fas fa-truck me-2"></i> Proveedores
        </a>
        <a href="{{ route('admin.tareas.index') }}" class="sidebar-link" style="background:#7c3aed;color:#fff!important;">
            <i class="fas fa-list-check me-2"></i> Tareas / Captura
        </a>
        @endif
        @if(auth()->user()->puede('cotizaciones.gestionar'))
        <a href="{{ route('admin.cotizaciones.index') }}" class="sidebar-link">
            <i class="fas fa-file-invoice-dollar me-2"></i> Cotizaciones
        </a>
        @endif
        @if(auth()->user()->puede('plantillas.gestionar'))
        <a href="{{ route('admin.plantillas.index') }}" class="sidebar-link">
            <i class="fas fa-envelope-open-text me-2"></i> Plantillas Correo
        </a>
        @endif
        @if(auth()->user()->puede('condiciones.gestionar'))
        <a href="{{ route('admin.condiciones.index') }}" class="sidebar-link">
            <i class="fas fa-file-contract me-2"></i> Condiciones
        </a>
        @endif
        @if(auth()->user()->puede('logos.gestionar'))
        <a href="{{ route('admin.logos.index') }}" class="sidebar-link">
            <i class="fas fa-image me-2"></i> Logos Empresa
        </a>
        @endif
        @if(auth()->user()->puede('exim.gestionar'))
        <a href="{{ route('admin.exim.dashboard') }}" class="sidebar-link">
            <i class="fas fa-ship me-2"></i> EXIM Exportaciones
        </a>
        @endif
        @if(auth()->user()->puede('visitas.gestionar'))
        <a href="{{ route('admin.visitas-tecnicas.index') }}" class="sidebar-link">
            <i class="fas fa-calendar-check me-2"></i> Visitas Técnicas
        </a>
        @endif
        @if(auth()->user()->puede('suscripciones.gestionar'))
        <a href="{{ route('admin.suscripciones.index') }}" class="sidebar-link">
            <i class="fas fa-envelope me-2"></i> Boletín / Suscripciones
        </a>
        @endif
        @if(auth()->user()->puede('concursos.gestionar'))
        <a href="{{ route('admin.concursos.index') }}" class="sidebar-link">
            <i class="fas fa-trophy me-2"></i> Concursos / Sorteos
        </a>
        <a href="{{ route('admin.concursos.sorteo') }}" class="sidebar-link" target="_blank" style="background:#7c3aed;color:#fff!important;">
            <i class="fas fa-star me-2"></i> SORTEO EN VIVO
        </a>
        @endif

        <div class="px-3 py-2 text-secondary small text-uppercase fw-bold mt-2">Usuarios</div>
        @if(auth()->user()->puede('usuarios.ver'))
        <a href="{{ route('admin.usuarios.index') }}" class="sidebar-link">
            <i class="fas fa-users me-2"></i> Usuarios
        </a>
        @endif
        @if(auth()->user()->puede('usuarios.gestionar'))
        <a href="{{ route('admin.email-logs.index') }}" class="sidebar-link">
            <i class="fas fa-paper-plane me-2"></i> Historial de Envíos
        </a>
        @endif
        @if(auth()->user()->puede('usuarios.gestionar'))
        <a href="{{ route('admin.usuarios.asignar.view') }}" class="sidebar-link">
            <i class="fas fa-user-tag me-2"></i> Asignar Roles
        </a>
        @endif
        @if(auth()->user()->puede('usuarios.gestionar'))
        <a href="{{ route('admin.rubros.index') }}" class="sidebar-link">
            <i class="fas fa-layer-group me-2"></i> Rubros
        </a>
        @endif
        @if(auth()->user()->puede('permisos.gestionar'))
        <a href="{{ route('admin.permisos.index') }}" class="sidebar-link" style="background:#0f766e;color:#fff!important;">
            <i class="fas fa-user-shield me-2"></i> Permisos
        </a>
        @endif
        @if(auth()->user()->puede('ubigeo.gestionar'))
        <div class="px-3 py-2 text-secondary small text-uppercase fw-bold mt-2">Ubigeo</div>
        <a href="{{ route('admin.ubigeo.paises') }}" class="sidebar-link">
            <i class="fas fa-globe me-2"></i> Países
        </a>
        <a href="{{ route('admin.ubigeo.departamentos') }}" class="sidebar-link">
            <i class="fas fa-building me-2"></i> Departamentos
        </a>
        <a href="{{ route('admin.ubigeo.provincias') }}" class="sidebar-link">
            <i class="fas fa-map-location-dot me-2"></i> Provincias
        </a>
        <a href="{{ route('admin.ubigeo.distritos') }}" class="sidebar-link">
            <i class="fas fa-map-pin me-2"></i> Distritos
        </a>
        @endif

        <div class="px-3 py-2 text-secondary small text-uppercase fw-bold mt-2">Contenido</div>
        @if(auth()->user()->puede('posts.gestionar'))
        <a href="{{ route('admin.posts.index') }}" class="sidebar-link">
            <i class="fas fa-blog me-2"></i> Posts / Blog
        </a>
        @endif

        <div class="px-3 py-2 text-secondary small text-uppercase fw-bold mt-2">Sistema</div>
        @if(auth()->user()->puede('negocios.gestionar'))
        <a href="{{ route('admin.negocios.index') }}" class="sidebar-link">
            <i class="fas fa-store me-2"></i> Negocios / Sitios
        </a>
        @endif
        <a href="{{ route('admin.mi-perfil') }}" class="sidebar-link">
            <i class="fas fa-user-pen me-2"></i> Modificar Perfil
        </a>
        <a href="{{ url('/') }}" class="sidebar-link" target="_blank">
            <i class="fas fa-up-right-from-square me-2"></i> Ver Sitio
        </a>
        <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="sidebar-link border-0 bg-transparent w-100 text-start">
                <i class="fas fa-right-from-bracket me-2"></i> Cerrar Sesión
            </button>
        </form>
    </nav>
</div>
