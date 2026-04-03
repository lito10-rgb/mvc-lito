<!-- <pre>
{{ print_r($user->getAttributes()) }}
</pre> -->
@extends('layouts.volt')
@push('scripts')
    @vite('resources/js/perfil.js')
@endpush

@section('content')
    <h1>Editar usuario</h1>

    @include('admin.usuarios._form')
@endsection
