@extends('layouts.app')

@section('content')
<div class="container my-5">

    <h1>{{ $subcategoria->subcategoria }}</h1>

    <h3 class="mb-4 mt-4">Productos de esta subcategoría</h3>

    <div class="row row-cols-1 row-cols-md-4 g-4">
        @foreach($subcategoria->productos as $producto)
            <div class="col">
                <div class="card h-100">
                    <img src="{{ asset('images/' . $producto->portada) }}" class="card-img-top" alt="{{ $producto->titulo }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $producto->titulo }}</h5>
                        <p class="card-text">{{ Str::limit($producto->descripcion, 60) }}</p>
                        <span class="badge bg-success">S/. {{ $producto->precio }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
