@extends('layouts.admin')

@section('title', 'Documentos - EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Documentos</h3>
        <a href="{{ route('admin.exim.documentos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nuevo Documento
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
                            <th>Cotización</th>
                            <th>Tipo</th>
                            <th>N° Documento</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documentos as $documento)
                        <tr>
                            <td>{{ $documento->id }}</td>
                            <td>#{{ $documento->cotizacion_id }} - {{ $documento->cotizacion->cliente->empresa ?? '' }}</td>
                            <td><span class="badge bg-info text-capitalize">{{ str_replace('_', ' ', $documento->tipo) }}</span></td>
                            <td>{{ $documento->numero_documento }}</td>
                            <td>
                                <a href="{{ route('admin.exim.documentos.edit', $documento) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.exim.documentos.destroy', $documento) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar documento?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No hay documentos registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $documentos->links() }}
    </div>
</div>
@endsection
