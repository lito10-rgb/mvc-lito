@extends('layouts.admin')

@section('title', 'Cotizaciones')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Cotizaciones</h3>
        <a href="{{ route('admin.cotizaciones.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nueva Cotización
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Buscar por cliente</label>
                    <input type="text" name="cliente" class="form-control" placeholder="Nombre del cliente..." value="{{ request('cliente') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Buscar
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.cotizaciones.index') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-times me-1"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Producto</th>
                            <th>Cant.</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cotizaciones as $c)
                        <tr>
                            <td>{{ $c->id }}</td>
                            <td>{{ $c->fecha->format('d/m/Y') }}</td>
                            <td>{{ $c->cliente }}</td>
                            <td>{{ $c->producto }}</td>
                            <td>{{ $c->cantidad }}</td>
                            <td>S/ {{ number_format($c->total, 2) }}</td>
                            <td>
                                @php
                                    $badges = ['pendiente' => 'warning', 'aprobada' => 'success', 'rechazada' => 'danger', 'completada' => 'info'];
                                @endphp
                                <span class="badge bg-{{ $badges[$c->estado] ?? 'secondary' }}">{{ ucfirst($c->estado) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.cotizaciones.show', $c) }}" class="btn btn-info btn-sm text-white">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.cotizaciones.edit', $c) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.cotizaciones.destroy', $c) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar cotización?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No hay cotizaciones registradas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $cotizaciones->links() }}
    </div>
</div>
@endsection
