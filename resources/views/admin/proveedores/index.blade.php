@extends('layouts.admin')

@section('title', 'Proveedores')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Proveedores</h3>
        <a href="{{ route('admin.proveedores.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nuevo Proveedor
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre o empresa..."
                   value="{{ request('buscar') }}">
        </div>
        <div class="col-md-2">
            <select name="pais_id" class="form-select">
                <option value="">Todos los países</option>
                @foreach($paises as $pais)
                    <option value="{{ $pais->id }}" {{ request('pais_id') == $pais->id ? 'selected' : '' }}>
                        {{ $pais->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="categoria_id" class="form-select">
                <option value="">Todas las categorías</option>
                @foreach($categorias as $cat)
                    <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->categoria }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="subcategoria_id" class="form-select">
                <option value="">Todas las subcategorías</option>
                @foreach($subcategorias as $sub)
                    <option value="{{ $sub->id }}" {{ request('subcategoria_id') == $sub->id ? 'selected' : '' }}>
                        {{ $sub->subcategoria }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Filtrar</button>
        </div>
    </form>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <button id="btnEliminarProveedores" class="btn btn-danger" disabled>
                    <i class="fas fa-trash me-1"></i> Eliminar seleccionados
                </button>
                @if(request()->anyFilled(['buscar', 'pais_id', 'categoria_id', 'subcategoria_id']))
                    <a href="{{ route('admin.proveedores.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times me-1"></i> Limpiar filtros
                    </a>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th><input type="checkbox" id="checkAllProveedores"></th>
                            <th>ID</th>
                            <th>Contacto</th>
                            <th>Empresa</th>
                            <th>RUC</th>
                            <th>Teléfono</th>
                            <th>Celular</th>
                            <th>Email</th>
                            <th>País</th>
                            <th>Departamento</th>
                            <th>Provincia</th>
                            <th>Distrito</th>
                            <th>Código Postal</th>
                            <th>Categoría</th>
                            <th>Subcategoría</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proveedores as $p)
                        <tr>
                            <td><input type="checkbox" class="checkProveedor" value="{{ $p->id }}"></td>
                            <td>{{ $p->id }}</td>
                            <td>{{ $p->nombre }}</td>
                            <td>{{ $p->empresa }}</td>
                            <td>{{ $p->ruc }}</td>
                            <td>{{ $p->telefono }}</td>
                            <td>{{ $p->celular }}</td>
                            <td>{{ $p->email }}</td>
                            <td>{{ $p->pais?->nombre }}</td>
                            <td>{{ $p->departamento?->nombre }}</td>
                            <td>{{ $p->provincia?->nombre }}</td>
                            <td>{{ $p->distrito?->nombre }}</td>
                            <td>{{ $p->codigo_postal }}</td>
                            <td>{{ $p->categoria?->categoria }}</td>
                            <td>{{ $p->subcategoria?->subcategoria }}</td>
                            <td>{{ Str::limit($p->descripcion, 60) }}</td>
                            <td>
                                <a href="{{ route('admin.proveedores.edit', $p) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.proveedores.destroy', $p) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar proveedor?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="17" class="text-center text-muted py-4">No hay proveedores registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $proveedores->links() }}
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="proveedores-eliminar-url" content="{{ route('admin.proveedores.eliminarMultiple') }}">
@endsection
