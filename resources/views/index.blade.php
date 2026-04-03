<!-- {{-- resources/views/categoria/index.blade.php --}} -->
@extends('layouts.app')

@section('title', 'Categorías')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Todas las Categorías</h1>

    <div class="row">
        @foreach($categorias as $categoria)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('categoria.show', $categoria->id) }}" class="text-dark text-decoration-none">
                                {{ $categoria->categoria }}
                            </a>
                        </h5>

                        @if($categoria->subcategorias->count())
                            <ul class="list-unstyled small mt-3">
                                @foreach($categoria->subcategorias as $sub)
                                    <li>
                                        <a href="{{ route('subcategoria.show', $sub->ruta) }}" class="text-muted">
                                            {{ $sub->subcategoria }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
