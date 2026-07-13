@extends('layouts.admin')

@section('title', 'Plantillas de Correo')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Plantillas de Correo</h3>
        <a href="{{ route('admin.plantillas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nueva Plantilla
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Asunto</th>
                        <th>Estado</th>
                        <th style="width:120px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plantillas as $p)
                    <tr>
                        <td>{{ $p->nombre }}</td>
                        <td>{{ $p->asunto }}</td>
                        <td>
                            @if($p->activo)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.plantillas.edit', $p) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.plantillas.destroy', $p) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar plantilla?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No hay plantillas registradas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $plantillas->links() }}</div>
</div>
@endsection
