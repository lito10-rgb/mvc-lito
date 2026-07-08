@extends('layouts.admin')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Detalle de Visita Técnica</h3>
        <a href="{{ route('admin.visitas-tecnicas.index') }}" class="btn btn-secondary">Volver</a>
    </div>
    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Nombre</dt>
                <dd class="col-sm-9">{{ $visita->nombre }}</dd>
                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9">{{ $visita->email }}</dd>
                <dt class="col-sm-3">Teléfono</dt>
                <dd class="col-sm-9">{{ $visita->telefono ?? '-' }}</dd>
                <dt class="col-sm-3">Empresa</dt>
                <dd class="col-sm-9">{{ $visita->empresa ?? '-' }}</dd>
                <dt class="col-sm-3">Fecha Visita</dt>
                <dd class="col-sm-9">{{ $visita->fecha_visita }}</dd>
                <dt class="col-sm-3">Estado</dt>
                <dd class="col-sm-9">{{ $visita->estado }}</dd>
                <dt class="col-sm-3">Mensaje</dt>
                <dd class="col-sm-9">{{ $visita->mensaje ?? 'Sin mensaje' }}</dd>
                <dt class="col-sm-3">Solicitado</dt>
                <dd class="col-sm-9">{{ $visita->created_at }}</dd>
            </dl>
        </div>
    </div>
</div>
@endsection
