@extends('layouts.admin')

@section('title', 'Pallets - EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Pallets</h3>
        <a href="{{ route('admin.exim.pallets.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nuevo Pallet
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
                            <th>Tipo</th>
                            <th>Material</th>
                            <th>Medidas (cm)</th>
                            <th>Peso (kg)</th>
                            <th>Capacidad (kg)</th>
                            <th>Costo Unit.</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pallets as $pallet)
                        <tr>
                            <td>{{ $pallet->id }}</td>
                            <td><span class="badge bg-info text-capitalize">{{ $pallet->tipo }}</span></td>
                            <td><span class="badge bg-secondary text-capitalize">{{ $pallet->material }}</span></td>
                            <td>{{ $pallet->largo_cm }} × {{ $pallet->ancho_cm }} × {{ $pallet->alto_cm }}</td>
                            <td>{{ number_format($pallet->peso_kg, 2) }}</td>
                            <td>{{ number_format($pallet->capacidad_kg, 2) }}</td>
                            <td>S/ {{ number_format($pallet->costo_unitario, 2) }}</td>
                            <td>
                                <a href="{{ route('admin.exim.pallets.edit', $pallet) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.exim.pallets.destroy', $pallet) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar pallet?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No hay pallets registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $pallets->links() }}
    </div>
</div>
@endsection