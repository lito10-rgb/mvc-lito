@extends('layouts.admin')

@section('title', 'Detalle de Cliente - EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">{{ $cliente->empresa }}</h3>
        <div>
            <a href="{{ route('admin.exim.clientes.edit', $cliente) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
            <a href="{{ route('admin.exim.clientes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Información del Cliente</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="ps-0" style="width:140px;">Empresa</th>
                            <td>{{ $cliente->empresa }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Contacto</th>
                            <td>{{ $cliente->contacto }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Cargo</th>
                            <td>{{ $cliente->cargo ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">País</th>
                            <td>{{ $cliente->pais->nombre ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Ciudad</th>
                            <td>{{ $cliente->ciudad ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Dirección</th>
                            <td>{{ $cliente->direccion ?? '—' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Contacto</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="ps-0" style="width:140px;">Email</th>
                            <td><a href="mailto:{{ $cliente->email }}">{{ $cliente->email }}</a></td>
                        </tr>
                        <tr>
                            <th class="ps-0">WhatsApp</th>
                            <td>
                                @if($cliente->whatsapp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $cliente->whatsapp) }}" target="_blank">
                                        {{ $cliente->whatsapp }}
                                    </a>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="ps-0">Teléfono</th>
                            <td>{{ $cliente->telefono ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Idioma</th>
                            <td>{{ $cliente->idioma ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Moneda Preferida</th>
                            <td>{{ $cliente->monedaPreferida->codigo ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Creado</th>
                            <td>{{ $cliente->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
