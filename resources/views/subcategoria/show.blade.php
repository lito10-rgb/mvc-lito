@extends('layouts.app')

@section('content')
<div class="container my-5">

    <h1 class="text-theme-accent">{{ $subcategoria->subcategoria }}</h1>

    <h3 class="mb-4 mt-4 text-theme-accent">Productos de esta subcategoría</h3>

    <div class="row row-cols-1 row-cols-md-4 g-4">
        @foreach($subcategoria->productos as $producto)
            <div class="col">
                <div class="card h-100" style="background-color: var(--theme-secondary); color: var(--theme-accent-light);">
                    <img src="{{ asset('images/' . $producto->portada) }}" class="card-img-top" alt="{{ $producto->titulo }}">
                    <div class="card-body">
                        <h5 class="card-title text-theme-accent">{{ $producto->titulo }}</h5>
                        <p class="card-text" style="color: var(--theme-accent-light);">{{ Str::limit($producto->descripcion, 60) }}</p>
                        @if($producto->tipo === 'servicio' && $producto->precio == 0)
                            <span class="badge" style="background-color: var(--theme-accent); color: #000;">Consultar precio</span>
                        @else
                            <span class="badge" style="background-color: var(--theme-accent); color: #000;">S/. {{ $producto->precio }}</span>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
