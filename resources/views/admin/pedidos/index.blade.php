@extends('layouts.volt')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Pedidos</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Usuario</th>
                        <th>Monto</th>
                        <th>Estado</th>
                        <th>Gateway</th>
                        <th>Items</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pedidos as $pedido)
                    <tr>
                        <td>{{ $pedido->id }}</td>
                        <td>{{ $pedido->user?->name ?? '—' }}</td>
                        <td>S/ {{ number_format($pedido->amount, 2) }}</td>
                        <td>
                            @php
                                $badge = match($pedido->status) {
                                    'paid' => 'success',
                                    'approved' => 'info',
                                    'pending' => 'warning',
                                    'failed' => 'danger',
                                    'cancelled' => 'secondary',
                                    'refunded' => 'dark',
                                    default => 'light'
                                };
                            @endphp
                            <span class="badge bg-{{ $badge }}">{{ ucfirst($pedido->status) }}</span>
                        </td>
                        <td>{{ $pedido->gateway ?? '—' }}</td>
                        <td>{{ $pedido->items_count }}</td>
                        <td>{{ $pedido->created_at ? $pedido->created_at->format('d/m/Y H:i') : '—' }}</td>
                        <td>
                            <a href="{{ route('admin.pedidos.show', $pedido) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.pedidos.edit', $pedido) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No hay pedidos registrados</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $pedidos->links() }}
    </div>
</div>
@endSection
