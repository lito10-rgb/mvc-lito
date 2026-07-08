@extends('layouts.admin')

@section('title', 'Monedas - EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Monedas</h3>
        <a href="{{ route('admin.exim.monedas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nueva Moneda
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
                            <th>Símbolo</th>
                            <th>Tipo de Cambio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($monedas as $moneda)
                        <tr>
                            <td>{{ $moneda->id }}</td>
                            <td><strong>{{ $moneda->codigo }}</strong></td>
                            <td>{{ $moneda->nombre }}</td>
                            <td>{{ $moneda->simbolo }}</td>
                            <td>{{ number_format($moneda->tipo_cambio, 4) }}</td>
                            <td>
                                <a href="{{ route('admin.exim.monedas.edit', $moneda) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.exim.monedas.destroy', $moneda) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar moneda?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No hay monedas registradas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $monedas->links() }}
    </div>
</div>
@endsection