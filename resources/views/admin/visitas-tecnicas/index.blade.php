@extends('layouts.admin')
@section('content')
<div class="container">
    <h3>Visitas Técnicas</h3>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Empresa</th>
                <th>Fecha Visita</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($visitas as $v)
            <tr>
                <td>{{ $v->id }}</td>
                <td>{{ $v->nombre }}</td>
                <td>{{ $v->email }}</td>
                <td>{{ $v->telefono ?? '-' }}</td>
                <td>{{ $v->empresa ?? '-' }}</td>
                <td>{{ $v->fecha_visita }}</td>
                <td>{{ $v->estado }}</td>
                <td>
                    <a href="{{ route('admin.visitas-tecnicas.show', $v->id) }}" class="btn btn-sm btn-info">Ver</a>
                    <form action="{{ route('admin.visitas-tecnicas.destroy', $v->id) }}" method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $visitas->links() }}
</div>
@endsection
