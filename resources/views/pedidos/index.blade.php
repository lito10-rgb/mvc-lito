@extends('layouts.app')

@section('title', 'Mis Pedidos')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-4">Mis Pedidos</h3>

    @if($pedidos->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Monto</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $pedido)
                        <tr>
                            <td>{{ $pedido->id }}</td>
                            <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                            <td>S/. {{ number_format($pedido->amount, 2) }}</td>
                            <td>
                                @switch($pedido->status)
                                    @case('approved')
                                        <span class="badge bg-success">Aprobado</span>
                                        @break
                                    @case('pending')
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                        @break
                                    @case('rejected')
                                        <span class="badge bg-danger">Rechazado</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $pedido->status }}</span>
                                @endswitch
                            </td>
                            <td>
                                <a href="{{ route('pedidos.show', $pedido->id) }}" class="btn btn-sm btn-outline-primary">Ver detalle</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $pedidos->links() }}
    @else
        <p class="text-muted">No tienes pedidos registrados.</p>
    @endif
</div>
@endsection
