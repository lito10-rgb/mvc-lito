@extends('layouts.admin')

@section('title', 'Cotizaciones - EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Cotizaciones</h3>
        <a href="{{ route('admin.exim.cotizaciones.create') }}" class="btn btn-primary">
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
                <div class="col-md-3">
                    <label class="form-label">Buscar</label>
                    <input type="text" name="search" class="form-control" placeholder="Cliente, producto..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="borrador" {{ request('estado') === 'borrador' ? 'selected' : '' }}>Borrador</option>
                        <option value="enviada" {{ request('estado') === 'enviada' ? 'selected' : '' }}>Enviada</option>
                        <option value="aprobada" {{ request('estado') === 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                        <option value="rechazada" {{ request('estado') === 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Buscar
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.exim.cotizaciones.index') }}" class="btn btn-secondary w-100">
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
                            <th>Incoterm</th>
                            <th>Transporte</th>
                            <th>Contenedor</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $badges = ['borrador' => 'secondary', 'enviada' => 'info', 'aprobada' => 'success', 'rechazada' => 'danger'];
                        @endphp
                        @forelse($cotizaciones as $c)
                        <tr>
                            <td>{{ $c->id }}</td>
                            <td>{{ \Carbon\Carbon::parse($c->fecha)->format('d/m/Y') }}</td>
                            <td>{{ $c->cliente->empresa ?? '—' }}</td>
                            <td>{{ $c->incoterm->codigo ?? '—' }}</td>
                            <td>{{ $c->transporte->nombre ?? '—' }}</td>
                            <td>{{ $c->contenedor->tipo ?? '—' }}</td>
                            <td>
                                @if($c->moneda)
                                    {{ $c->moneda->simbolo }} {{ number_format($c->total ?? 0, 2) }}
                                @else
                                    {{ number_format($c->total ?? 0, 2) }}
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $badges[$c->estado] ?? 'secondary' }}">{{ ucfirst($c->estado) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.exim.cotizaciones.show', $c) }}" class="btn btn-info btn-sm text-white">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.exim.cotizaciones.edit', $c) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.exim.cotizaciones.destroy', $c) }}" method="POST" class="d-inline">
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
                            <td colspan="9" class="text-center text-muted py-4">No hay cotizaciones registradas</td>
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
