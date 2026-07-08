@extends('layouts.admin')

@section('title', 'Seguros - EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Seguros</h3>
        <a href="{{ route('admin.exim.seguros.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nuevo Seguro
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
                            <th>Nombre</th>
                            <th>Porcentaje</th>
                            <th>Costo Base</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($seguros as $seguro)
                        <tr>
                            <td>{{ $seguro->id }}</td>
                            <td>{{ $seguro->nombre }}</td>
                            <td>{{ number_format($seguro->porcentaje, 2) }}%</td>
                            <td>
                                @if($seguro->moneda)
                                    {{ $seguro->moneda->simbolo }} {{ number_format($seguro->costo_base, 2) }}
                                @else
                                    S/ {{ number_format($seguro->costo_base, 2) }}
                                @endif
                            </td>
                            <td>
                                @if($seguro->activo)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.exim.seguros.edit', $seguro) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.exim.seguros.destroy', $seguro) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar seguro?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No hay seguros registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $seguros->links() }}
    </div>
</div>
@endsection