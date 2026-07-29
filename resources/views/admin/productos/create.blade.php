@extends('layouts.volt')

@section('title', isset($origen) ? 'Duplicar Producto' : 'Crear Producto')

@section('content')
<div class="container-fluid px-4">
    <div class="card border-0 shadow mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                @if(isset($origen))
                    <i class="fas fa-copy me-2 text-success"></i>Duplicar Producto
                @else
                    Nuevo Producto
                @endif
            </h5>
        </div>

        @if(isset($origen))
        <div class="alert alert-success mx-3 mt-3 mb-0">
            <i class="fas fa-info-circle me-1"></i>
            Se creará un <strong>nuevo producto</strong> basado en <strong>{{ $origen->titulo }}</strong>. El título tendrá "(copia)" y la ruta se regenerará.
        </div>
        @endif

        <div class="card-body">
            @include('admin.productos._form', ['route' => route('admin.productos.store'), 'method' => 'POST', 'producto' => $origen ?? null])
        </div>
    </div>
</div>
@endsection
