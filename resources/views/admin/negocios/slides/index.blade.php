@extends('layouts.volt')
@section('title', 'Slides - ' . $negocio->nombre)
@section('content')

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.negocios.index') }}">Negocios</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.negocios.edit', $negocio) }}">{{ $negocio->nombre }}</a></li>
            <li class="breadcrumb-item active">Slides / Banners</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Slides de {{ $negocio->nombre }}</h1>
        <a href="{{ route('admin.negocios.slides.create', $negocio) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Slide
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($slides->count() == 0)
        <div class="alert alert-info">No hay slides. Agrega uno para mostrar en el banner de inicio.</div>
    @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Orden</th>
                        <th>Imagen</th>
                        <th>Título</th>
                        <th>Botón</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($slides as $slide)
                        <tr>
                            <td>{{ $slide->orden }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $slide->imagen) }}" style="height:60px;" alt="">
                            </td>
                            <td>{{ $slide->titulo }}</td>
                            <td>
                                @if($slide->boton_texto)
                                    <span class="badge bg-theme-accent">{{ $slide->boton_texto }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.negocios.slides.edit', [$negocio, $slide]) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('admin.negocios.slides.destroy', [$negocio, $slide]) }}" method="POST" class="d-inline" onsubmit="return confirm('Eliminar slide?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
