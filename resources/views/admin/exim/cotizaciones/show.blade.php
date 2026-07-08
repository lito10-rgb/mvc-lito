@extends('layouts.admin')

@section('title', 'Detalle de Cotización - EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Cotización #{{ $cotizacione->id }}</h3>
        <div>
            <a href="{{ route('admin.exim.cotizaciones.edit', $cotizacione) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
            <a href="{{ route('admin.exim.cotizaciones.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    @php
        $badges = ['borrador' => 'secondary', 'enviada' => 'info', 'aprobada' => 'success', 'rechazada' => 'danger'];
        $monedaSimbolo = $cotizacione->moneda->simbolo ?? '$';
        $base = ($cotizacione->subtotal ?? 0)
              + ($cotizacione->gastos_operativos_total ?? 0)
              + ($cotizacione->gastos_logisticos_total ?? 0)
              + ($cotizacione->costo_pallets ?? 0)
              + ($cotizacione->costo_contenedor ?? 0)
              + ($cotizacione->seguro_total ?? 0)
              + ($cotizacione->documentacion_total ?? 0);
    @endphp

    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Cliente</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="ps-0" style="width:100px;">Empresa</th>
                            <td>{{ $cotizacione->cliente->empresa ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Contacto</th>
                            <td>{{ $cotizacione->cliente->contacto ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Email</th>
                            <td>{{ $cotizacione->cliente->email ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">País</th>
                            <td>{{ $cotizacione->cliente->pais->nombre ?? '—' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Logística</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="ps-0" style="width:100px;">Incoterm</th>
                            <td>{{ $cotizacione->incoterm->codigo ?? '—' }} ({{ $cotizacione->incoterm->nombre ?? '' }})</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Transporte</th>
                            <td>{{ $cotizacione->transporte->nombre ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Contenedor</th>
                            <td>{{ strtoupper($cotizacione->contenedor->tipo ?? '') }} ({{ $cotizacione->contenedor->largo_cm ?? '—' }}×{{ $cotizacione->contenedor->ancho_cm ?? '—' }}cm)</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Validez</th>
                            <td>{{ $cotizacione->validez_dias ?? '—' }} días</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Resumen</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="ps-0" style="width:100px;">Fecha</th>
                            <td>{{ \Carbon\Carbon::parse($cotizacione->fecha)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Estado</th>
                            <td><span class="badge bg-{{ $badges[$cotizacione->estado] ?? 'secondary' }}">{{ ucfirst($cotizacione->estado) }}</span></td>
                        </tr>
                        <tr>
                            <th class="ps-0">Moneda</th>
                            <td>{{ $cotizacione->moneda->codigo ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Tipo Cambio</th>
                            <td>{{ number_format($cotizacione->tipo_cambio, 4) }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Creado</th>
                            <td>{{ $cotizacione->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if($cotizacione->notas)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Notas</h5>
        </div>
        <div class="card-body">
            <p class="mb-0">{{ $cotizacione->notas }}</p>
        </div>
    </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Detalle de Costos</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered mb-0" style="max-width:600px;">
                    <thead class="table-light">
                        <tr>
                            <th>Concepto</th>
                            <th class="text-end">Importe</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Subtotal (productos)</td>
                            <td class="text-end">{{ $monedaSimbolo }} {{ number_format($cotizacione->subtotal ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Gastos Operativos</td>
                            <td class="text-end">{{ $monedaSimbolo }} {{ number_format($cotizacione->gastos_operativos_total ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Gastos Logísticos</td>
                            <td class="text-end">{{ $monedaSimbolo }} {{ number_format($cotizacione->gastos_logisticos_total ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Pallets</td>
                            <td class="text-end">{{ $monedaSimbolo }} {{ number_format($cotizacione->costo_pallets ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Contenedor</td>
                            <td class="text-end">{{ $monedaSimbolo }} {{ number_format($cotizacione->costo_contenedor ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Seguro</td>
                            <td class="text-end">{{ $monedaSimbolo }} {{ number_format($cotizacione->seguro_total ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Documentación</td>
                            <td class="text-end">{{ $monedaSimbolo }} {{ number_format($cotizacione->documentacion_total ?? 0, 2) }}</td>
                        </tr>
                    </tbody>
                    <tfoot class="table-group-divider">
                        <tr>
                            <th>Costo Base Total</th>
                            <th class="text-end">{{ $monedaSimbolo }} {{ number_format($base, 2) }}</th>
                        </tr>
                        <tr>
                            <th>Utilidad ({{ $cotizacione->utilidad_porcentaje }}%)</th>
                            <th class="text-end">{{ $monedaSimbolo }} {{ number_format($cotizacione->utilidad_monto ?? 0, 2) }}</th>
                        </tr>
                        <tr class="table-active">
                            <th class="text-end">TOTAL</th>
                            <th class="text-end"><strong>{{ $monedaSimbolo }} {{ number_format($cotizacione->total ?? 0, 2) }}</strong></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
