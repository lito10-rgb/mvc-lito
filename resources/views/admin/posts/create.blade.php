@extends('layouts.admin')

@section('content')
<div class="container">

    <h3>Nuevo Contenido</h3>

    @if(session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.posts.store') }}">

        @csrf

        <div class="mb-3">
            <label>Título</label>
            <input name="titulo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Contenido</label>
            <textarea name="contenido" class="form-control" rows="4"></textarea>
        </div>

        <div class="mb-3">
            <label>Compartir con usuarios</label>
            <select name="users[]" class="form-control" multiple>
                @foreach($users as $u)
                    <option value="{{ $u->id }}">
                        {{ $u->nombre }} {{ $u->apellidos }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Plataformas</label>
            <select name="platforms[]" class="form-control" multiple>
                @foreach($platforms as $p)
                    <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Guardar</button>
    </form>

</div>
@endsection
