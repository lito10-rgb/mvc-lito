@extends('layouts.admin')

@section('title', 'Dashboard EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="fas fa-ship me-2"></i> Módulo EXIM</h3>
        <span class="text-muted">{{ now()->format('d/m/Y H:i') }}</span>
    </div>

    <div class="row g-4">
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm text-white" style="background:#0d6efd;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Monedas</h6>
                        <h2 class="mb-0">{{ $counts['monedas'] ?? 0 }}</h2>
                    </div>
                    <i class="fas fa-coins fa-3x opacity-50"></i>
                </div>
                <a href="{{ route('admin.exim.monedas.index') }}" class="card-footer text-white text-center text-decoration-none small" style="background:rgba(0,0,0,.1);">Gestionar →</a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm text-white" style="background:#e67e22;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Incoterms</h6>
                        <h2 class="mb-0">{{ $counts['incoterms'] ?? 0 }}</h2>
                    </div>
                    <i class="fas fa-file-contract fa-3x opacity-50"></i>
                </div>
                <a href="{{ route('admin.exim.incoterms.index') }}" class="card-footer text-white text-center text-decoration-none small" style="background:rgba(0,0,0,.1);">Gestionar →</a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm text-white" style="background:#27ae60;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Transportes</h6>
                        <h2 class="mb-0">{{ $counts['transportes'] ?? 0 }}</h2>
                    </div>
                    <i class="fas fa-truck fa-3x opacity-50"></i>
                </div>
                <a href="{{ route('admin.exim.transportes.index') }}" class="card-footer text-white text-center text-decoration-none small" style="background:rgba(0,0,0,.1);">Gestionar →</a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm text-white" style="background:#8e44ad;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Seguros</h6>
                        <h2 class="mb-0">{{ $counts['seguros'] ?? 0 }}</h2>
                    </div>
                    <i class="fas fa-shield-halved fa-3x opacity-50"></i>
                </div>
                <a href="{{ route('admin.exim.seguros.index') }}" class="card-footer text-white text-center text-decoration-none small" style="background:rgba(0,0,0,.1);">Gestionar →</a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm text-white" style="background:#c0392b;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Pallets</h6>
                        <h2 class="mb-0">{{ $counts['pallets'] ?? 0 }}</h2>
                    </div>
                    <i class="fas fa-boxes-stacked fa-3x opacity-50"></i>
                </div>
                <a href="{{ route('admin.exim.pallets.index') }}" class="card-footer text-white text-center text-decoration-none small" style="background:rgba(0,0,0,.1);">Gestionar →</a>
            </div>
        </div>
    </div>
</div>
@endsection