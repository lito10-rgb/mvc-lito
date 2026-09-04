@extends('layouts.app')

@section('content')
<div class="container my-5">

    <h1 class="text-theme-accent">{{ $subcategoria->subcategoria }}</h1>

    <h3 class="mb-4 mt-4 text-theme-accent">Productos de esta subcategoría</h3>

    <div class="row row-cols-1 row-cols-md-4 g-4">
        @foreach($subcategoria->productos as $producto)
            <div class="col">
                <div class="card h-100" style="background-color: var(--theme-secondary); color: var(--theme-accent-light);">
                    <a href="{{ route('producto.mostrar', $producto->ruta) }}">
                        <img src="{{ asset('storage/' . $producto->portada) }}" class="card-img-top" alt="{{ $producto->titulo }}" style="height:200px; object-fit:cover;">
                    </a>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-theme-accent">{{ $producto->titulo }}</h5>
                        <p class="card-text" style="color: var(--theme-accent-light);">{{ Str::limit(strip_tags(html_entity_decode($producto->descripcion)), 60) }}</p>
                        @if($producto->tipo === 'servicio' && $producto->precio == 0)
                            <span class="badge" style="background-color: var(--theme-accent); color: #000;">Consultar precio</span>
                        @else
                            <span class="badge" style="background-color: var(--theme-accent); color: #000;">S/. {{ $producto->precio }}</span>
                        @endif
                        <div class="mt-auto pt-3">
                            <a href="{{ route('producto.mostrar', $producto->ruta) }}" class="btn btn-outline-dark btn-sm w-100 mb-2">
                                <i class="fa-solid fa-eye"></i> Ver detalle
                            </a>
                            @if($producto->tipo === 'servicio' && $producto->precio == 0)
                                <a href="{{ route('cotizacion.solicitar', $producto->id) }}" class="btn btn-sm btn-outline-warning w-100">
                                    <i class="fa-solid fa-file-invoice-dollar"></i> Solicitar cotización
                                </a>
                            @else
                                <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-dark w-100">
                                        <i class="fa-solid fa-cart-plus"></i> Agregar al carrito
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
