@extends('layouts.admin')

@section('title', 'Incoterms - EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Incoterms</h3>
        <a href="{{ route('admin.exim.incoterms.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nuevo Incoterm
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
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Cobertura</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($incoterms as $incoterm)
                        <tr>
                            <td>{{ $incoterm->id }}</td>
                            <td><strong>{{ $incoterm->codigo }}</strong></td>
                            <td>{{ $incoterm->nombre }}</td>
                            <td>{{ Str::limit($incoterm->descripcion, 50) }}</td>
                            <td>
                                @if($incoterm->incluye_transporte_interno) <span class="badge bg-info" title="Transp. Interno">TI</span> @endif
                                @if($incoterm->incluye_flete_maritimo) <span class="badge bg-primary" title="Flete Marítimo">FM</span> @endif
                                @if($incoterm->incluye_seguro) <span class="badge bg-success" title="Seguro">SG</span> @endif
                                @if($incoterm->incluye_aduanas_origen) <span class="badge bg-warning" title="Aduanas Origen">AO</span> @endif
                                @if($incoterm->incluye_aduanas_destino) <span class="badge bg-danger" title="Aduanas Destino">AD</span> @endif
                                @if($incoterm->incluye_transporte_destino) <span class="badge bg-secondary" title="Transp. Destino">TD</span> @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.exim.incoterms.edit', $incoterm) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.exim.incoterms.destroy', $incoterm) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar incoterm?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No hay incoterms registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $incoterms->links() }}
    </div>
</div>
@endsection