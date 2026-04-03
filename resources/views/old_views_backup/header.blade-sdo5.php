<!-- header.blade.php -->
@php
              $categorias = [
    [
        'nombre' => 'Café Arábica',
        'slug' => 'arabica',
        'subcategorias' => [
            ['nombre' => 'Grano entero', 'slug' => 'grano-entero'],
            ['nombre' => 'Molido', 'slug' => 'molido'],
            ['nombre' => 'Orgánico', 'slug' => 'organico'],
        ]
    ],
    [
        'nombre' => 'Café Robusta',
        'slug' => 'robusta',
        'subcategorias' => [
            ['nombre' => 'Espresso', 'slug' => 'espresso'],
            ['nombre' => 'Soluble', 'slug' => 'soluble'],
        ]
    ],
    [
        'nombre' => 'Accesorios',
        'slug' => 'accesorios',
        'subcategorias' => [
            ['nombre' => 'Prensas', 'slug' => 'prensas'],
            ['nombre' => 'Filtros', 'slug' => 'filtros'],
        ]
    ],
    [
        'nombre' => 'Máquinas de café',
        'slug' => 'maquinas',
        'subcategorias' => [
            ['nombre' => 'Automáticas', 'slug' => 'automaticas'],
            ['nombre' => 'Manuales', 'slug' => 'manuales'],
        ]
    ],
];


              @endphp
<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="/">Mono Tingales</a>

    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto align-items-center"
          x-data="{ open: false, activeCategory: null, insideSubmenu: false }"
          @mouseleave.window="if(window.innerWidth >= 992){ open = false; activeCategory = null; insideSubmenu = false }"
      >
        <li class="nav-item"><a class="nav-link" href="/">Inicio</a></li>

        <!-- Categorías -->
        <li class="nav-item position-relative"
            @mouseenter="if(window.innerWidth >= 992) open = true"
            @mouseleave="if(window.innerWidth >= 992 && !insideSubmenu) { open = false; activeCategory = null }"
        >
          <a href="#" class="nav-link" @click.prevent="open = !open">Categorías</a>

          <!-- Mega Menú -->
          <div class="mega-menu shadow border bg-white position-absolute w-100 p-3"
               x-show="open"
               x-cloak
               @mouseenter="open = true"
               @mouseleave="if(!insideSubmenu){ open = false; activeCategory = null }"
               style="top: 100%; left: 0; z-index: 1050;"
          >
            <div class="row">
              @foreach($categorias as $index => $categoria)
              <div class="col-md-3 position-relative">
                <a href="/categoria/{{ $categoria['slug'] }}"
                   class="fw-bold d-block mb-2"
                   @mouseenter="activeCategory = {{ $index }}"
                   @mouseleave="if(!insideSubmenu) activeCategory = null"
                >
                  {{ $categoria['nombre'] }}
                </a>

                <!-- Subcategorías -->
                @if(count($categoria['subcategorias']))
                <div class="submenu position-absolute bg-white shadow border p-3"
                     x-show="activeCategory === {{ $index }}"
                     x-cloak
                     @mouseenter="insideSubmenu = true"
                     @mouseleave="insideSubmenu = false; activeCategory = null"
                >
                  <div class="subcategory-grid">
                    @foreach($categoria['subcategorias'] as $sub)
                      <a href="/subcategoria/{{ $sub['slug'] }}" class="text-dark text-decoration-none mb-2 d-block">
                        {{ $sub['nombre'] }}
                      </a>
                    @endforeach
                  </div>
                </div>
                @endif
              </div>
              @endforeach
            </div>
          </div>
        </li>

        <li class="nav-item"><a class="nav-link" href="/ofertas">Ofertas</a></li>
        <li class="nav-item"><a class="nav-link" href="/contacto">Contacto</a></li>
        <li class="nav-item"><a class="nav-link" href="/login">Iniciar sesión</a></li>
        <li class="nav-item"><a class="nav-link" href="/register">Registrarse</a></li>
        <li class="nav-item"><a class="nav-link" href="/carrito">🛒</a></li>
      </ul>
    </div>
  </div>
  
</nav>
