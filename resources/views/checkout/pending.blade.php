@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card shadow-sm">
                <div class="card-body py-5">
                    <i class="fas fa-clock text-warning" style="font-size: 4rem;"></i>
                    <h2 class="mt-3">Pago Pendiente</h2>
                    <p class="text-muted">Tu pago está siendo procesado. Te notificaremos cuando se confirme.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-home me-1"></i> Volver al inicio
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
