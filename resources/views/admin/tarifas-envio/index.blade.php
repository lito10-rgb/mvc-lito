@extends('layouts.volt')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Tarifas de Envío</h3>
        <a href="{{ route('admin.tarifas-envio.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nueva Tarifa
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @foreach($tipos as $tipo)
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-dark text-white">
            <strong>{{ $tipo->nombre }}</strong>
            <span class="badge bg-light text-dark ms-2">{{ $tipo->tarifas->count() }} reglas</span>
        </div>
        <div class="card-body p-0">
            @if($tipo->tarifas->isEmpty())
                <p class="text-muted p-3">Sin reglas configuradas</p>
            @else
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Categoría</th><th>Mín.</th><th>Máx.</th><th>Costo</th><th>Estado</th><th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tipo->tarifas as $tarifa)
                        <tr>
                            <td>{{ $tarifa->categoria ? $tarifa->categoria->categoria : '<em>General</em>' }}</td>
                            <td>{{ $tarifa->minimo ? number_format($tarifa->minimo, 2) : '0' }}</td>
                            <td>{{ $tarifa->maximo ? number_format($tarifa->maximo, 2) : '∞' }}</td>
                            <td class="fw-bold">S/ {{ number_format($tarifa->costo, 2) }}</td>
                            <td>
                                @if($tarifa->activo)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.tarifas-envio.edit', $tarifa) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.tarifas-envio.destroy', $tarifa) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection