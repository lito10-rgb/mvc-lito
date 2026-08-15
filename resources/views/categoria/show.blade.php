@extends('layouts.app')

@section('title', $categoria->categoria ?? $categoria->nombre ?? 'Categoría')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <h3 class="fw-bold text-theme-accent">{{ $categoria->categoria ?? $categoria->nombre ?? '' }}</h3>
        @include('partials.buscador')
    </div>

    @if($categoria->subcategorias->count() > 0)
        <div class="row">
            @foreach($categoria->subcategorias as $sub)
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm h-100" style="background-color: var(--theme-secondary); color: var(--theme-accent-light);">
                        <div class="card-body">
                            <h5><a href="{{ route('subcategoria.show', $sub->ruta) }}" class="text-decoration-none text-theme-accent">{{ $sub->subcategoria }}</a></h5>
                            <p class="small" style="color: var(--theme-accent-light);">{{ Str::limit($sub->detalle ?? '', 120) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p style="color: var(--theme-accent-light);">No hay subcategorías en esta categoría.</p>
    @endif

    {{-- Mostrar productos de la categoría --}}
    @if($categoria->productos->count() > 0)
        <div class="mt-5">
            <h4 class="fw-bold text-theme-accent mb-4">Productos de esta categoría</h4>
            <div class="row row-cols-1 row-cols-md-4 g-4">
                @foreach($categoria->productos as $producto)
                    <div class="col">
                        <div class="card h-100" style="background-color: var(--theme-secondary); color: var(--theme-accent-light);">
                            <a href="{{ route('producto.mostrar', $producto->ruta) }}">
                                <img src="{{ asset('storage/' . $producto->portada) }}" class="card-img-top" alt="{{ $producto->titulo }}" style="height:200px; object-fit:cover;">
                            </a>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-theme-accent">{{ $producto->titulo }}</h5>
                                <p class="card-text" style="color: var(--theme-accent-light);">{{ Str::limit($producto->descripcion, 60) }}</p>
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
    @else
        <p class="text-muted mt-4">No hay productos en esta categoría.</p>
    @endif
</div>
@endsection
