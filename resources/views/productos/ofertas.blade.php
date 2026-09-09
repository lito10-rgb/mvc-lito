@extends('layouts.app')

@section('content')
<div class="container">

    {{-- Banner --}}
    <div class="text-center my-4">
        <h2 class="fw-bold"><i class="fas fa-tags text-danger me-2"></i>Ofertas Especiales</h2>
        <p class="text-muted">¡Aprovecha nuestros precios especiales por tiempo limitado!</p>
    </div>

    {{-- PRODUCTOS --}}
    <div class="row">
        @forelse($productos as $producto)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">

                    <a href="{{ route('producto.mostrar', $producto->ruta) }}">
                        <img src="{{ asset('storage/'.$producto->portada) }}" 
                             class="card-img-top"
                             style="height:250px; object-fit:cover;">
                    </a>

                    <div class="card-body d-flex flex-column">

                        <small class="text-muted">
                            {{ $producto->categoria->categoria ?? '' }}
                        </small>

                        <h5 class="mt-2">
                            {{ $producto->titulo }}
                        </h5>

                        @if($producto->enOferta)
                            <p class="fw-bold fs-5 mb-0">
                                <span class="text-muted text-decoration-line-through me-1">S/ {{ number_format($producto->precio, 2) }}</span>
                                <span class="text-danger">S/ {{ number_format($producto->precioFinal, 2) }}</span>
                                <span class="badge text-bg-danger">{{ $producto->descuentoEtiqueta }}</span>
                            </p>
                            <x-etiqueta-oferta :producto="$producto" />
                        @else
                            <p class="fw-bold fs-5">S/ {{ number_format($producto->precio, 2) }}</p>
                        @endif

                        @if($producto->envio_gratis)
                            <small class="d-block mb-1"><span class="badge bg-success fs-6"><i class="fa-solid fa-truck-fast me-1"></i>Envío gratuito</span></small>
                        @endif

                        @php($dias = $producto->entrega ?? '2')
                        @if($producto->stock > 0)
                            <small class="text-success d-block mb-1"><i class="fa-solid fa-check-circle"></i> En stock</small>
                        @else
                            <small class="text-warning d-block mb-1"><i class="fa-solid fa-clock"></i> Por encargo — {{ $dias }} días</small>
                        @endif

                        <small class="text-success mb-2">
                            {{ $producto->ventas ?? 0 }} ventas
                        </small>

                        <div class="mt-auto">
                            <a href="{{ route('producto.mostrar', $producto->ruta) }}"
                               class="btn btn-outline-dark w-100 mb-2">
                                Ver detalle
                            </a>
                            @if($producto->tipo !== 'servicio' || $producto->precio > 0)
                                <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-dark w-100">Agregar al carrito</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-muted">No hay productos en oferta en este momento.</p>
        @endforelse
    </div>

    {{ $productos->links() }}
</div>
@endsection
