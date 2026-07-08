@extends('layouts.admin')

@section('title', 'Gastos Operativos - EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Gastos Operativos</h3>
        <a href="{{ route('admin.exim.gastos-operativos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nuevo Gasto Operativo
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
                            <th>Descripción</th>
                            <th>Costo</th>
                            <th>Tipo Cálculo</th>
                            <th>Activo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gastosOperativos as $gasto)
                        <tr>
                            <td>{{ $gasto->id }}</td>
                            <td>{{ $gasto->nombre }}</td>
                            <td>{{ Str::limit($gasto->descripcion, 40) }}</td>
                            <td>
                                @if($gasto->moneda)
                                    {{ $gasto->moneda->simbolo }} {{ number_format($gasto->costo, 2) }}
                                @else
                                    {{ number_format($gasto->costo, 2) }}
                                @endif
                            </td>
                            <td><span class="badge bg-secondary text-capitalize">{{ str_replace('_', ' ', $gasto->tipo_calculo) }}</span></td>
                            <td>
                                @if($gasto->activo)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.exim.gastos-operativos.edit', $gasto) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.exim.gastos-operativos.destroy', $gasto) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar gasto operativo?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No hay gastos operativos registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $gastosOperativos->links() }}
    </div>
</div>
@endsection
