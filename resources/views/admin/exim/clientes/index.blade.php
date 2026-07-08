@extends('layouts.admin')

@section('title', 'Clientes - EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Clientes</h3>
        <a href="{{ route('admin.exim.clientes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nuevo Cliente
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
                            <th>Empresa</th>
                            <th>Contacto</th>
                            <th>País</th>
                            <th>Email</th>
                            <th>WhatsApp</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->id }}</td>
                            <td>{{ $cliente->empresa }}</td>
                            <td>{{ $cliente->contacto }}</td>
                            <td>{{ $cliente->pais->nombre ?? '—' }}</td>
                            <td>{{ $cliente->email }}</td>
                            <td>{{ $cliente->whatsapp }}</td>
                            <td>
                                <a href="{{ route('admin.exim.clientes.show', $cliente) }}" class="btn btn-info btn-sm text-white">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.exim.clientes.edit', $cliente) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.exim.clientes.destroy', $cliente) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar cliente?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No hay clientes registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $clientes->links() }}
    </div>
</div>
@endsection
