@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Post</h1>

    <form action="{{ route('admin.posts.update', $post->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Título</label>
            <input type="text"
                   name="titulo"
                   class="form-control"
                   value="{{ $post->titulo }}">
        </div>

        <div class="mb-3">
            <label>Contenido</label>
            <textarea name="contenido"
                      class="form-control"
                      rows="6">{{ $post->cuerpo }}</textarea>
        </div>

        <div class="mb-3">
            <label>Estado</label>
            <select name="estado" class="form-control">
    <option value="1" {{ $post->estado == 1 ? 'selected' : '' }}>
        Activo
    </option>
    <option value="0" {{ $post->estado == 0 ? 'selected' : '' }}>
        Inactivo
    </option>
</select>

        </div>

        <button class="btn btn-primary">
            Actualizar
        </button>
    </form>
</div>
@endsection
