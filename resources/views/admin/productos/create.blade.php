@extends('layouts.volt')

@section('title', 'Crear Producto')

@section('content')
<div class="container-fluid px-4">
    <div class="card border-0 shadow mb-4">
        <div class="card-header">
            <h5 class="mb-0">Nuevo Producto</h5>
        </div>

        <div class="card-body">
            @include('admin.productos._form', ['route' => route('admin.productos.store'), 'method' => 'POST', 'producto' => null])
        </div>
    </div>
</div>
@endsection
