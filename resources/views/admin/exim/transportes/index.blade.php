@extends('layouts.admin')

@section('title', 'Transportes - EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Transportes</h3>
        <a href="{{ route('admin.exim.transportes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nuevo Transporte
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
                            <th>Tipo</th>
                            <th>Costo Base</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transportes as $transporte)
                        <tr>
                            <td>{{ $transporte->id }}</td>
                            <td>{{ $transporte->nombre }}</td>
                            <td><span class="badge bg-info text-capitalize">{{ $transporte->tipo }}</span></td>
                            <td>
                                @if($transporte->moneda)
                                    {{ $transporte->moneda->simbolo }} {{ number_format($transporte->costo_base, 2) }}
                                @else
                                    S/ {{ number_format($transporte->costo_base, 2) }}
                                @endif
                            </td>
                            <td>
                                @if($transporte->activo)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.exim.transportes.edit', $transporte) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.exim.transportes.destroy', $transporte) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar transporte?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No hay transportes registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $transportes->links() }}
    </div>
</div>
@endsection