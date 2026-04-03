@extends('layouts.volt')

@section('content')
<h1>Asignar Roles y Rubros</h1>

<form method="POST" action="{{ route('admin.usuarios.asignar.store') }}">
@csrf

@include('admin.usuarios._tabla_seleccion')

@include('admin.usuarios._panel_asignacion')

</form>
@endsection
