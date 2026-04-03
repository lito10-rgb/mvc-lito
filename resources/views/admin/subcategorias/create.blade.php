@extends('admin')

@section('content')
<h3>Nueva Subcategoría</h3>

<form method="POST" action="{{ route('admin.subcategorias.store') }}">
    @csrf

    <div class="mb-3">
        <label>Categoría</label>
        <select name="categoria_id" class="form-control">
            @foreach($categorias as $c)
            <option value="{{ $c->id }}">{{ $c->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control">
    </div>

    <button class="btn btn-primary">Guardar</button>
</form>
@endsection
