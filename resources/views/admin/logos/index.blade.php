@extends('layouts.admin')

@section('title', 'Logos de Empresa')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Logos de Empresa</h3>
        <a href="{{ route('admin.logos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nuevo Logo
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3">
        @forelse($logos as $l)
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <img src="{{ asset('storage/' . $l->ruta) }}" class="card-img-top p-3" alt="{{ $l->nombre }}"
                     style="height:150px;object-fit:contain;">
                <div class="card-body text-center">
                    <h6 class="card-title mb-1">{{ $l->nombre }}</h6>
                    @if($l->por_defecto)
                        <span class="badge bg-success mb-2">Por defecto</span>
                    @endif
                    <div class="mt-2">
                        <a href="{{ route('admin.logos.edit', $l) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.logos.destroy', $l) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar logo?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center text-muted py-4">No hay logos registrados</div>
        @endforelse
    </div>
    <div class="mt-3">{{ $logos->links() }}</div>
</div>
@endsection
