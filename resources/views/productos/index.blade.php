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
                        @if($producto->tipo === 'servicio' && $producto->precio == 0)
                            <p class="fw-bold fs-5 text-theme-accent">Consultar precio</p>
                        @else
                            <p class="fw-bold fs-5">S/ {{ number_format($producto->precio, 2) }}</p>
                        @endif

                        @php($dias = $producto->entrega ?? '2')
                        @if($producto->tipo === 'servicio')
                            <small class="text-info d-block mb-1"><i class="fa-solid fa-gear"></i> Servicio — {{ $dias == 0 ? 'Entrega inmediata' : 'Coordinar en ' . $dias . ' días' }}</small>
                        @elseif($producto->tipo === 'digital' || $producto->tipo === 'planos')
                            <small class="text-success d-block mb-1"><i class="fa-solid fa-download"></i> Descarga digital</small>
                        @elseif($producto->stock > 0)
                            <small class="text-success d-block mb-1"><i class="fa-solid fa-check-circle"></i> {{ $dias == 0 ? 'Entrega inmediata' : 'En stock — ' . $dias . ' días' }}</small>
                        @else
                            <small class="text-warning d-block mb-1"><i class="fa-solid fa-clock"></i> {{ $dias == 0 ? 'Disponibilidad — Entrega inmediata' : 'Por encargo — ' . $dias . ' días (aprox)' }}</small>
                        @endif

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

                            @if($producto->tipo === 'servicio' && $producto->precio == 0)
                            <a href="{{ route('cotizacion.solicitar', $producto->id) }}" class="btn btn-outline-warning w-100"><i class="fa-solid fa-file-invoice-dollar"></i> Solicitar cotización</a>
                        @else
                            <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-dark w-100">
                                    Agregar al carrito
                                </button>
                            </form>
                        @endif

                        </div>

                    </div>
                </div>
            </div>
        @empty
            <p>No se encontraron productos.</p>
        @endforelse
    </div>

    {{ $productos->withQueryString()->links() }}
</div>
@endsection