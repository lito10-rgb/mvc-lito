@extends('admin')

@section('content')
<h3>Editar Subcategoría</h3>

<form method="POST" action="{{ route('admin.subcategorias.update', $subcategoria) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Categoría</label>
        <select name="categoria_id" class="form-control">
            @foreach($categorias as $c)
            <option value="{{ $c->id }}" @selected($c->id == $subcategoria->categoria_id)>
                {{ $c->nombre }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control" value="{{ $subcategoria->nombre }}">
    </div>

    <button class="btn btn-primary">Actualizar</button>
</form>
@endsection
