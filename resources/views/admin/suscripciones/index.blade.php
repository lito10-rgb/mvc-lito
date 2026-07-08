@extends('layouts.admin')
@section('content')
<div class="container">
    <h3>Suscripciones al Boletín</h3>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Nombre</th>
                <th>Activo</th>
                <th>Suscrito</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suscripciones as $s)
            <tr>
                <td>{{ $s->id }}</td>
                <td>{{ $s->email }}</td>
                <td>{{ $s->nombre ?? '-' }}</td>
                <td>{{ $s->activo ? 'Sí' : 'No' }}</td>
                <td>{{ $s->created_at->format('Y-m-d') }}</td>
                <td>
                    <form action="{{ route('admin.suscripciones.destroy', $s->id) }}" method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $suscripciones->links() }}
</div>
@endsection
