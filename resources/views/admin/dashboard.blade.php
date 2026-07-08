@php
    use App\Models\Producto;
    use App\Models\User;
    use App\Models\Categoria;
    use App\Models\Subcategoria;
    use App\Models\Marca;
    use App\Models\Proveedor;
    use App\Models\Post;
    use App\Models\Order;
    use App\Models\Comentario;
    use App\Models\Compra;
    use App\Models\Slide;
    use App\Models\Banner;
    use App\Models\Cabecera;
    use App\Models\Cotizacion;
    use Illuminate\Support\Facades\Schema;

    $eximCotizaciones = Schema::hasTable('exim_cotizaciones') ? \DB::table('exim_cotizaciones')->count() : 0;
    $eximClientes = Schema::hasTable('exim_clientes') ? \DB::table('exim_clientes')->count() : 0;
    $eximProductos = Schema::hasTable('exim_productos') ? \DB::table('exim_productos')->count() : 0;

    $counts = [
        'productos' => Producto::count(),
        'usuarios' => User::count(),
        'categorias' => Categoria::count(),
        'subcategorias' => Subcategoria::count(),
        'marcas' => Marca::count(),
        'proveedores' => Proveedor::count(),
        'cotizaciones' => Cotizacion::count(),
        'posts' => Post::count(),
        'ordenes' => Order::count(),
        'comentarios' => Comentario::count(),
        'compras' => Compra::count(),
        'slides' => Slide::count(),
        'banners' => Banner::count(),
        'cabeceras' => Cabecera::count(),
        'exim_cotizaciones' => $eximCotizaciones,
        'exim_clientes' => $eximClientes,
        'exim_productos' => $eximProductos,
    ];
@endphp

@extends('layouts.volt')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Dashboard</h3>
        <span class="text-muted">{{ now()->format('d/m/Y H:i') }}</span>
    </div>

    <div class="row g-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm bg-dark text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Productos</h6>
                        <h2 class="mb-0">{{ $counts['productos'] }}</h2>
                    </div>
                    <i class="fas fa-boxes-stacked fa-3x opacity-50"></i>
                </div>
                <a href="{{ route('admin.productos.index') }}" class="card-footer text-white text-center text-decoration-none small bg-dark border-0">Gestionar →</a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm text-white" style="background:#2c3e50;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Usuarios</h6>
                        <h2 class="mb-0">{{ $counts['usuarios'] }}</h2>
                    </div>
                    <i class="fas fa-users fa-3x opacity-50"></i>
                </div>
                <a href="{{ route('admin.usuarios.index') }}" class="card-footer text-white text-center text-decoration-none small" style="background:#2c3e50;">Gestionar →</a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm text-white" style="background:#8e44ad;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Categorías</h6>
                        <h2 class="mb-0">{{ $counts['categorias'] }}</h2>
                    </div>
                    <i class="fas fa-tag fa-3x opacity-50"></i>
                </div>
                <a href="{{ route('admin.categorias.index') }}" class="card-footer text-white text-center text-decoration-none small" style="background:#8e44ad;">Gestionar →</a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm text-white" style="background:#27ae60;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Subcategorías</h6>
                        <h2 class="mb-0">{{ $counts['subcategorias'] }}</h2>
                    </div>
                    <i class="fas fa-sitemap fa-3x opacity-50"></i>
                </div>
                <a href="{{ route('admin.subcategorias.index') }}" class="card-footer text-white text-center text-decoration-none small" style="background:#27ae60;">Gestionar →</a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm text-white" style="background:#e67e22;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Marcas</h6>
                        <h2 class="mb-0">{{ $counts['marcas'] }}</h2>
                    </div>
                    <i class="fas fa-copyright fa-3x opacity-50"></i>
                </div>
                <a href="{{ route('admin.marcas.index') }}" class="card-footer text-white text-center text-decoration-none small" style="background:#e67e22;">Gestionar →</a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm text-white" style="background:#c0392b;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Proveedores</h6>
                        <h2 class="mb-0">{{ $counts['proveedores'] }}</h2>
                    </div>
                    <i class="fas fa-truck fa-3x opacity-50"></i>
                </div>
                <a href="{{ route('admin.proveedores.index') }}" class="card-footer text-white text-center text-decoration-none small" style="background:#c0392b;">Gestionar →</a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm text-white" style="background:#1abc9c;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Cotizaciones</h6>
                        <h2 class="mb-0">{{ $counts['cotizaciones'] }}</h2>
                    </div>
                    <i class="fas fa-file-invoice-dollar fa-3x opacity-50"></i>
                </div>
                <a href="{{ route('admin.cotizaciones.index') }}" class="card-footer text-white text-center text-decoration-none small" style="background:#1abc9c;">Gestionar →</a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm text-white" style="background:#16a085;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Posts / Blog</h6>
                        <h2 class="mb-0">{{ $counts['posts'] }}</h2>
                    </div>
                    <i class="fas fa-blog fa-3x opacity-50"></i>
                </div>
                <a href="{{ route('admin.posts.index') }}" class="card-footer text-white text-center text-decoration-none small" style="background:#16a085;">Gestionar →</a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm text-white" style="background:#0d6efd;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">EXIM Exportaciones</h6>
                        <h2 class="mb-0">{{ $counts['exim_cotizaciones'] }}</h2>
                        <small class="opacity-75">
                            {{ $counts['exim_clientes'] }} clientes · {{ $counts['exim_productos'] }} productos
                        </small>
                    </div>
                    <i class="fas fa-ship fa-3x opacity-50"></i>
                </div>
                <a href="{{ route('admin.exim.dashboard') }}" class="card-footer text-white text-center text-decoration-none small" style="background:#0d6efd;">Gestionar →</a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm text-white" style="background:#2980b9;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Órdenes</h6>
                        <h2 class="mb-0">{{ $counts['ordenes'] }}</h2>
                    </div>
                    <i class="fas fa-shopping-cart fa-3x opacity-50"></i>
                </div>
                <div class="card-footer text-white text-center small" style="background:#2980b9;">Sin CRUD</div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm text-white" style="background:#d35400;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Comentarios</h6>
                        <h2 class="mb-0">{{ $counts['comentarios'] }}</h2>
                    </div>
                    <i class="fas fa-comments fa-3x opacity-50"></i>
                </div>
                <div class="card-footer text-white text-center small" style="background:#d35400;">Sin CRUD</div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm text-white" style="background:#34495e;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Slides</h6>
                        <h2 class="mb-0">{{ $counts['slides'] }}</h2>
                    </div>
                    <i class="fas fa-image fa-3x opacity-50"></i>
                </div>
                <div class="card-footer text-white text-center small" style="background:#34495e;">Sin CRUD</div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm text-white" style="background:#6c3483;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Banners</h6>
                        <h2 class="mb-0">{{ $counts['banners'] }}</h2>
                    </div>
                    <i class="fas fa-ad fa-3x opacity-50"></i>
                </div>
                <div class="card-footer text-white text-center small" style="background:#6c3483;">Sin CRUD</div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm text-white" style="background:#1a5276;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Cabeceras SEO</h6>
                        <h2 class="mb-0">{{ $counts['cabeceras'] }}</h2>
                    </div>
                    <i class="fas fa-heading fa-3x opacity-50"></i>
                </div>
                <div class="card-footer text-white text-center small" style="background:#1a5276;">Sin CRUD</div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm text-white" style="background:#5d4e37;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Compras</h6>
                        <h2 class="mb-0">{{ $counts['compras'] }}</h2>
                    </div>
                    <i class="fas fa-hand-holding-dollar fa-3x opacity-50"></i>
                </div>
                <div class="card-footer text-white text-center small" style="background:#5d4e37;">Sin CRUD</div>
            </div>
        </div>
    </div>
</div>
@endsection
