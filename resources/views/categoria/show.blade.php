@extends('layouts.app')

@section('title', $categoria->categoria ?? $categoria->nombre ?? 'Categoría')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <h3 class="fw-bold">{{ $categoria->categoria ?? $categoria->nombre ?? '' }}</h3>
        @include('partials.buscador')
    </div>

    @if($categoria->subcategorias->count() > 0)
        <div class="row">
            @foreach($categoria->subcategorias as $sub)
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5>{{ $sub->subcategoria }}</h5>
                            <p class="text-muted small">{{ Str::limit($sub->detalle ?? '', 120) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-muted">No hay subcategorías en esta categoría.</p>
    @endif
</div>
@endsection
