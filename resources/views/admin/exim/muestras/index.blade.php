@extends('layouts.admin')

@section('title', 'Muestras - EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Muestras</h3>
        <a href="{{ route('admin.exim.muestras.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nueva Muestra
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Peso (kg)</th>
                            <th>Courier</th>
                            <th>Costo Envío</th>
                            <th>Costo Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($muestras as $muestra)
                        <tr>
                            <td>{{ $muestra->id }}</td>
                            <td>{{ $muestra->producto->nombre ?? '—' }}</td>
                            <td>{{ $muestra->cantidad }}</td>
                            <td>{{ number_format($muestra->peso_kg, 2) }}</td>
                            <td><span class="badge bg-info">{{ $muestra->courier }}</span></td>
                            <td>{{ number_format($muestra->costo_envio, 2) }}</td>
                            <td>{{ number_format($muestra->costo_total, 2) }}</td>
                            <td>
                                <a href="{{ route('admin.exim.muestras.edit', $muestra) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.exim.muestras.destroy', $muestra) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar muestra?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No hay muestras registradas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $muestras->links() }}
    </div>
</div>
@endsection
