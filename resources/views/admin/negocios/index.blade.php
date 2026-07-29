@extends('layouts.volt')

@section('title', 'Negocios')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Negocios / Sitios</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Dominio</th>
                            <th>Logo</th>
                            <th>Colores</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($negocios as $negocio)
                        <tr>
                            <td>{{ $negocio->id }}</td>
                            <td class="fw-bold">{{ $negocio->nombre }}</td>
                            <td>{{ $negocio->dominio }}</td>
                            <td>
                                @if($negocio->logo)
                                    <img src="{{ asset('storage/' . $negocio->logo) }}" style="height:35px;" alt="Logo">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($negocio->color_primary)
                                    <span class="d-inline-block rounded" style="width:20px;height:20px;background:{{ $negocio->color_primary }};border:1px solid #ccc;" title="Primary"></span>
                                @endif
                                @if($negocio->color_secondary)
                                    <span class="d-inline-block rounded" style="width:20px;height:20px;background:{{ $negocio->color_secondary }};border:1px solid #ccc;" title="Secondary"></span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.negocios.edit', $negocio) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-pen me-1"></i> Configurar
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
