@extends('layouts.app')

@section('content')
<div class="container my-5">

    {{-- Imagen de fondo de la subcategoría --}}
    <div class="mb-5 p-5 text-white text-center" style="background-image: url('{{ asset('images/' . $subcategoria->imagen) }}'); background-size: cover; background-position: center;">
        <h1 class="display-4 bg-dark bg-opacity-75 p-3 rounded">{{ $subcategoria->subcategoria }}</h1>
    </div>

    <h3 class="mb-4">Productos de esta subcategoría</h3>

    <div class="row row-cols-1 row-cols-md-4 g-4">
        @foreach($subcategoria->productos as $producto)
            <div class="col">
                <div class="card h-100">
                    <img src="{{ asset('images/' . $producto->imagen) }}" class="card-img-top" alt="{{ $producto->nombre }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $producto->nombre }}</h5>
                        <p class="card-text">{{ Str::limit($producto->descripcion, 60) }}</p>
                        <span class="badge bg-success">S/. {{ $producto->precio }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
