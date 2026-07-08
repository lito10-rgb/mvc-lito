@extends('layouts.app')

@section('content')
<div class="container">

   @include('partials.buscador')

    {{-- PRODUCTOS --}}
    <div class="row">
        @forelse($productos as $producto)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">

                    {{-- 🖼 IMAGEN CLICKEABLE --}}
                    <a href="{{ route('producto.mostrar', $producto->ruta) }}">
                        <img src="{{ asset('storage/'.$producto->portada) }}" 
                             class="card-img-top"
                             style="height:250px; object-fit:cover;">
                    </a>

                    <div class="card-body d-flex flex-column">

                        {{-- Categoría --}}
                        <small class="text-muted">
                            {{ $producto->categoria->nombre ?? '' }}
                        </small>

                        {{-- Nombre --}}
                        <h5 class="mt-2">
                            {{ $producto->titulo }}
                        </h5>

                        {{-- Precio --}}
                        <p class="fw-bold fs-5">
                            S/ {{ number_format($producto->precio, 2) }}
                        </p>

                        {{-- Ventas --}}
                        <small class="text-success mb-2">
                            {{ $producto->ventas ?? 0 }} ventas
                        </small>

                        {{-- Botones --}}
                        <div class="mt-auto">

                            {{-- Ver detalle --}}
                            <a href="{{ route('producto.mostrar', $producto->ruta) }}"
                               class="btn btn-outline-dark w-100 mb-2">
                                Ver detalle
                            </a>

                            {{-- Agregar carrito --}}
                            <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-dark w-100">
                                    Agregar al carrito
                                </button>
                            </form>

                        </div>

                    </div>
                </div>
            </div>
        @empty
            <p>No se encontraron productos.</p>
        @endforelse
    </div>

    {{ $productos->links() }}
</div>
@endsection