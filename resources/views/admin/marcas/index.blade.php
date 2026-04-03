@extends('admin.layouts.admin')

@section('title', 'Marcas')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Marcas</h3>
        <a href="{{ route('admin.marcas.create') }}" class="btn btn-primary">Nueva Marca</a>
    </div>

    <meta name="marcas-eliminar-url" content="{{ route('admin.marcas.eliminarMultiple') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <table class="table table-bordered">
        <thead>
            <tr>
                <th><input type="checkbox" id="checkAll"></th>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($marcas as $marca)
            <tr>
                <td><input type="checkbox" class="checkItem" value="{{ $marca->id }}"></td>
                <td>{{ $marca->id }}</td>
                <td>{{ $marca->nombre }}</td>
                <td>
                    <a href="{{ route('admin.marcas.edit', $marca) }}" class="btn btn-sm btn-warning">Editar</a>

                    <form method="POST" action="{{ route('admin.marcas.destroy', $marca) }}" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger"
                                onclick="return confirm('¿Eliminar?')">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $marcas->links() }}

    <button id="btnEliminarSeleccionados" class="btn btn-danger mt-3" disabled>
        Eliminar seleccionados
    </button>
</div>
@endsection

@push('scripts')
@vite(['resources/js/marcas.js'])
@endpush
