@extends('layouts.admin')

@section('title', 'Proveedores para el último producto cotizado')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0"><i class="fas fa-clock me-2 text-warning"></i> Proveedores para el último producto cotizado</h3>
        <a href="{{ route('admin.tareas.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Tareas
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(!$cotizacion)
        <div class="alert alert-secondary">Aún no hay cotizaciones registradas.</div>
    @else
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <h5>Última cotización <span class="badge bg-dark ms-1">#{{ $cotizacion->id }}</span>
                <span class="text-muted small ms-2">{{ $cotizacion->fecha }}</span>
            </h5>
            <div class="text-muted">Cliente: {{ $cotizacion->cliente }}</div>
        </div>
    </div>

    @foreach($cotizacion->items as $item)
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="d-flex align-items-start mb-2">
                @if(!empty($item['portada']))
                    <img src="{{ asset('storage/' . $item['portada']) }}" alt=""
                         class="me-3 rounded" style="width:60px;height:60px;object-fit:cover;"
                         onerror="this.style.display='none'">
                @endif
                <div>
                    <h6 class="fw-bold mb-1">{{ $item['producto'] }}</h6>
                    @if(!empty($item['descripcion']))
                        <div class="text-muted small">{{ $item['descripcion'] }}</div>
                    @endif
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2 mb-2">
                <a href="https://www.google.com/search?q={{ urlencode($item['producto'] . ' proveedor mayorista nacional') }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-globe me-1"></i> Buscar nacional
                </a>
                <a href="https://www.google.com/search?q={{ urlencode($item['producto'] . ' proveedor export wholesale') }}" target="_blank" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-plane me-1"></i> Buscar internacional
                </a>
                <a href="https://www.alibaba.com/trade/search?fsb=y&IndexArea=product_en&keywords={{ urlencode($item['producto']) }}" target="_blank" class="btn btn-sm btn-outline-warning">
                    <i class="fas fa-globe-asia me-1"></i> Alibaba
                </a>
                <a href="https://www.made-in-china.com/products-search/hot-china-products/{{ urlencode($item['producto']) }}.html" target="_blank" class="btn btn-sm btn-outline-success">
                    <i class="fas fa-factory me-1"></i> Made in China
                </a>
            </div>
        </div>
    </div>
    @endforeach
    @endif

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-warning bg-opacity-25 fw-bold">
            <i class="fas fa-list me-2"></i> Proveedores encontrados en internet
            <span class="badge bg-dark ms-1">{{ count($proveedoresWeb) }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Proveedor</th>
                            <th>Origen</th>
                            <th>Producto</th>
                            <th>Detalle</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($proveedoresWeb as $prov)
                        <tr>
                            <td class="fw-bold">{{ $prov['nombre'] }}</td>
                            <td>
                                @if(str_contains($prov['origen'], 'Nacional'))
                                    <span class="badge bg-primary">{{ $prov['origen'] }}</span>
                                @else
                                    <span class="badge bg-danger">{{ $prov['origen'] }}</span>
                                @endif
                            </td>
                            <td class="small">{{ $prov['producto'] }}</td>
                            <td class="small text-muted">{{ $prov['detalle'] }}</td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    @if($prov['web'])
                                        <a href="{{ $prov['web'] }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Abrir sitio">
                                            <i class="fas fa-external-link-alt me-1"></i> Sitio
                                        </a>
                                    @endif
                                    <form method="POST" action="{{ route('admin.tareas.proveedorWeb') }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="nombre" value="{{ $prov['nombre'] }}">
                                        <input type="hidden" name="web" value="{{ $prov['web'] }}">
                                        <input type="hidden" name="email" value="{{ $prov['email'] }}">
                                        <input type="hidden" name="producto" value="{{ $item['producto'] ?? $prov['producto'] }}">
                                        <button class="btn btn-sm btn-success" title="Registrar proveedor">
                                            <i class="fas fa-user-plus me-1"></i> Inscribir
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection