@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="alert alert-success">
        <h4>Pago completado</h4>
        <p>Gracias por tu compra. Recibirás información por correo cuando el pago sea confirmado.</p>
    </div>

    <a href="{{ url('/') }}" class="btn btn-primary">Volver al inicio</a>
</div>
@endsection
