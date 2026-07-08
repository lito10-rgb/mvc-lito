@extends('layouts.app')

@section('title', 'Resultados de búsqueda')
@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-0">Resultados de búsqueda</h3>
            <small class="text-muted">Mostrando {{ $productos->total() }} producto(s)</small>
        </div>
        <div>
            <!-- Botón volver a búsqueda (opcional) -->
            <a href="{{ url()->previous() }}" class="btn btn-light">Volver</a>
        </div>
    </div>

    @if ($productos->count() === 0)
        <div class="alert alert-warning">No se encontraron productos con esos filtros.</div>
    @else
        <div class="row g-3">
            @foreach ($productos as $producto)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm">
                        @php
                            // intenta obtener la primera imagen del campo multimedia
                            $img = null;
                            if (!empty($producto->multimedia)) {
                                $raw = $producto->multimedia;
                                $decoded = json_decode($raw, true);
                                if (is_array($decoded) && count($decoded)) $img = $decoded[0];
                                elseif (strpos($raw, ',') !== false) $img = explode(',', $raw)[0];
                                else $img = $raw;
                            }
                        @endphp

                        @if ($img)
                            <img src="{{ asset('storage/' . trim($img)) }}" class="card-img-top" style="height:170px; object-fit:cover;" alt="{{ $producto->titulo }}">
                        @else
                            <img src="{{ asset('images/placeholder.png') }}" class="card-img-top" style="height:170px; object-fit:cover;" alt="sin imagen">
                        @endif

                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title mb-1 text-truncate" title="{{ $producto->titulo ?? $producto->nombre }}">
                                <a href="{{ url('producto/' . ($producto->slug ?? $producto->id)) }}" class="text-decoration-none text-dark">
                                    {{ Str::limit($producto->titulo ?? $producto->nombre, 48) }}
                                </a>
                            </h6>

                            <p class="mb-2 text-warning fw-bold">S/ {{ number_format($producto->precio ?? 0, 2) }}</p>

                            <div class="mt-auto d-flex gap-2">
                                <a href="{{ url('producto/' . ($producto->slug ?? $producto->id)) }}" class="btn btn-sm btn-outline-secondary flex-fill">Ver</a>

                                <!-- Botón añadir carrito: usa data-url o data-product-id según tu implementación -->
                                <button
                                    class="btn btn-sm btn-primary btn-agregar-carrito"
                                    data-product-id="{{ $producto->id }}"
                                    data-url="{{ url('carrito/agregar/' . $producto->id) }}"
                                    data-qty="1"
                                    type="button">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            <!-- Paginación (mantiene los filtros en la query string) -->
            {{ $productos->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
