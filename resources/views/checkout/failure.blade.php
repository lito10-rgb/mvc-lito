@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card shadow-sm">
                <div class="card-body py-5">
                    <i class="fas fa-times-circle text-danger" style="font-size: 4rem;"></i>
                    <h2 class="mt-3">Pago Fallido</h2>
                    <p class="text-muted">El pago no pudo completarse. Intenta nuevamente.</p>
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-arrow-left me-1"></i> Volver al checkout
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
