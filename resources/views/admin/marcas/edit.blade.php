@extends('admin.layouts.admin')

@section('title', 'Editar Marca')

@section('content')
<div class="container">
    <h3>Editar Marca</h3>

    <form method="POST" action="{{ route('admin.marcas.update', $marca) }}">
        @csrf @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nombre:</label>
            <input type="text" name="nombre" value="{{ $marca->nombre }}" class="form-control" required>
        </div>

        <button class="btn btn-success">Actualizar</button>
    </form>
</div>
@endsection
