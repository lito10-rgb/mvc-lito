@extends('admin.layouts.admin')

@section('title', 'Nueva Marca')

@section('content')
<div class="container">
    <h3>Nueva Marca</h3>

    <form method="POST" action="{{ route('admin.marcas.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nombre:</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>

        <button class="btn btn-primary">Guardar</button>
    </form>
</div>
@endsection
