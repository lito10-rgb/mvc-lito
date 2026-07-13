@extends('layouts.volt')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Editar Pedido #{{ $pedido->id }}</h3>
        <a href="{{ route('admin.pedidos.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.pedidos.update', $pedido) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Estado del pedido</label>
                    <select name="status" class="form-control @error('status') is-invalid @enderror">
                        <option value="pending" {{ $pedido->status == 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="paid" {{ $pedido->status == 'paid' ? 'selected' : '' }}>Pagado</option>
                        <option value="approved" {{ $pedido->status == 'approved' ? 'selected' : '' }}>Aprobado</option>
                        <option value="failed" {{ $pedido->status == 'failed' ? 'selected' : '' }}>Fallido</option>
                        <option value="cancelled" {{ $pedido->status == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                        <option value="refunded" {{ $pedido->status == 'refunded' ? 'selected' : '' }}>Reembolsado</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-1"></i>
                    Monto: <strong>S/ {{ number_format($pedido->amount, 2) }}</strong> |
                    Gateway: <strong>{{ $pedido->gateway ?? '—' }}</strong> |
                    Cliente: <strong>{{ $pedido->user?->name ?? '—' }}</strong>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Actualizar Estado
                </button>
            </form>
        </div>
    </div>
</div>
@endSection
