@extends('layouts.volt')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold"><i class="fas fa-ticket me-2 text-success"></i>Cupones de Descuento</h3>
        <a href="{{ route('admin.cupones.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nuevo Cupón
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="text" name="buscar" class="form-control form-control-sm" placeholder="Buscar código..." value="{{ request('buscar') }}">
        </div>
        <div class="col-md-2">
            <select name="estado" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Todos</option>
                <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activos</option>
                <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivos</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter me-1"></i> Filtrar</button>
        </div>
    </form>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Tipo</th>
                        <th>Valor</th>
                        <th>Mín. Compra</th>
                        <th>Usos</th>
                        <th>Negocio</th>
                        <th>Vigencia</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cupones as $cupon)
                    <tr>
                        <td><strong class="text-success">{{ $cupon->codigo }}</strong></td>
                        <td>
                            <span class="badge bg-{{ $cupon->tipo === 'porcentaje' ? 'primary' : 'info' }}">
                                {{ $cupon->tipo === 'porcentaje' ? '%' : 'S/' }}
                            </span>
                            {{ $cupon->tipo === 'porcentaje' ? 'Porcentaje' : 'Monto fijo' }}
                        </td>
                        <td>
                            <strong>
                                {{ $cupon->tipo === 'porcentaje' ? $cupon->valor . '%' : 'S/ ' . number_format($cupon->valor, 2) }}
                            </strong>
                        </td>
                        <td>S/ {{ number_format($cupon->min_compra, 2) }}</td>
                        <td>
                            {{ $cupon->usos_actuales }}
                            @if($cupon->max_usos)
                                / {{ $cupon->max_usos }}
                            @else
                                / &infin;
                            @endif
                        </td>
                        <td>
                            @if($cupon->negocio)
                                <span class="badge bg-info">{{ $cupon->negocio->nombre }}</span>
                            @else
                                <span class="text-muted">Todos</span>
                            @endif
                        </td>
                        <td>
                            <small>
                                @if($cupon->fecha_inicio)
                                    {{ $cupon->fecha_inicio->format('d/m/Y') }}
                                @else
                                    Sin inicio
                                @endif
                                <br>
                                @if($cupon->fecha_fin)
                                    {{ $cupon->fecha_fin->format('d/m/Y') }}
                                @else
                                    Sin fin
                                @endif
                            </small>
                        </td>
                        <td>
                            <form action="{{ route('admin.cupones.toggle', $cupon) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-{{ $cupon->activo ? 'success' : 'secondary' }} border-0">
                                    <i class="fas fa-{{ $cupon->activo ? 'toggle-on' : 'toggle-off' }}"></i>
                                    {{ $cupon->activo ? 'Activo' : 'Inactivo' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('admin.cupones.edit', $cupon) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.cupones.destroy', $cupon) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este cupón?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="fas fa-ticket fa-2x mb-2 d-block"></i>
                            No hay cupones registrados
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $cupones->links() }}
    </div>
</div>
@endsection
