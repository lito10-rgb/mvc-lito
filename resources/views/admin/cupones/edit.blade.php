@extends('layouts.volt')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold"><i class="fas fa-edit me-2 text-warning"></i>Editar Cupón: {{ $cupon->codigo }}</h3>
        <a href="{{ route('admin.cupones.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.cupones.update', $cupon) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.cupones._form')
            </form>
        </div>
    </div>
</div>
@endsection
