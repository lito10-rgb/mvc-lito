@extends('layouts.admin')

@section('title', 'Contenedores - EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Contenedores</h3>
        <a href="{{ route('admin.exim.contenedores.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nuevo Contenedor
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
                            <th>Dimensiones (cm)</th>
                            <th>Cap. Máx. (kg)</th>
                            <th>Pallets</th>
                            <th>Sacos</th>
                            <th>Flete Marítimo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contenedores as $contenedor)
                        <tr>
                            <td>{{ $contenedor->id }}</td>
                            <td><span class="badge bg-info text-uppercase">{{ $contenedor->tipo }}</span></td>
                            <td>{{ $contenedor->largo_cm }} × {{ $contenedor->ancho_cm }} × {{ $contenedor->alto_cm }}</td>
                            <td>{{ number_format($contenedor->capacidad_max_kg, 0) }}</td>
                            <td>{{ $contenedor->pallets_max }}</td>
                            <td>{{ $contenedor->sacos_max }}</td>
                            <td>{{ number_format($contenedor->flete_maritimo, 2) }}</td>
                            <td>
                                <a href="{{ route('admin.exim.contenedores.edit', $contenedor) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.exim.contenedores.destroy', $contenedor) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar contenedor?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No hay contenedores registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $contenedores->links() }}
    </div>
</div>
@endsection
