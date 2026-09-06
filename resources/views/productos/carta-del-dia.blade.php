@extends('layouts.app')

@section('title', 'Carta del Día')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <h3 class="fw-bold text-theme-accent">Carta del Día</h3>
        <p class="text-theme-accent opacity-75">Productos destacados de hoy</p>
        @include('partials.buscador')
        @if(isset($productos) && $productos->count() > 0)
            <a href="{{ route('carta.del.dia.pdf') }}" class="btn btn-theme-accent mt-3" target="_blank" rel="noopener">
                <i class="fa-solid fa-file-pdf me-2"></i> Descargar carta en PDF
            </a>
        @endif
    </div>

    @if(isset($productos) && $productos->count() > 0)
        <div class="row row-cols-1 row-cols-md-4 g-4">
            @foreach($productos as $producto)
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
                            @elseif($producto->enOferta)
                                <span class="badge text-decoration-line-through" style="background-color: var(--theme-accent); color: #000;">S/. {{ $producto->precio }}</span>
                                <span class="badge" style="background-color: #dc3545; color: #fff;">S/. {{ number_format($producto->precioFinal, 2) }} ({{ $producto->descuentoEtiqueta }})</span>
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
        
        {{-- Paginador --}}
        <div class="mt-4">
            {{ $productos->withQueryString()->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fa-solid fa-calendar-xmark fa-3x text-theme-accent opacity-50 mb-3"></i>
            <h4 class="text-theme-accent">No hay productos destacados hoy</h4>
            <p class="text-theme-accent opacity-75">Vuelve más tarde para ver nuestras ofertas especiales</p>
            <a href="{{ route('productos.index') }}" class="btn btn-theme-accent mt-3">
                <i class="fa-solid fa-arrow-left me-2"></i> Ver todos los productos
            </a>
        </div>
    @endif
</div>
@endsection