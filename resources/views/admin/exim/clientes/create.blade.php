@extends('layouts.admin')

@section('title', 'Nuevo Cliente - EXIM')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Nuevo Cliente</h3>
        <a href="{{ route('admin.exim.clientes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.exim.clientes.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Empresa</label>
                        <input type="text" name="empresa" class="form-control @error('empresa') is-invalid @enderror"
                               value="{{ old('empresa') }}" required>
                        @error('empresa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contacto</label>
                        <input type="text" name="contacto" class="form-control @error('contacto') is-invalid @enderror"
                               value="{{ old('contacto') }}" required>
                        @error('contacto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Cargo</label>
                        <input type="text" name="cargo" class="form-control @error('cargo') is-invalid @enderror"
                               value="{{ old('cargo') }}">
                        @error('cargo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">País</label>
                        <select name="pais_id" class="form-select @error('pais_id') is-invalid @enderror" required>
                            <option value="">Seleccione...</option>
                            @foreach($paises as $pais)
                                <option value="{{ $pais->id }}" {{ old('pais_id') == $pais->id ? 'selected' : '' }}>
                                    {{ $pais->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('pais_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Ciudad</label>
                        <input type="text" name="ciudad" class="form-control @error('ciudad') is-invalid @enderror"
                               value="{{ old('ciudad') }}">
                        @error('ciudad') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror"
                               value="{{ old('direccion') }}">
                        @error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">WhatsApp</label>
                        <input type="text" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror"
                               value="{{ old('whatsapp') }}" placeholder="+51 999 999 999">
                        @error('whatsapp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
                               value="{{ old('telefono') }}">
                        @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Idioma</label>
                        <input type="text" name="idioma" class="form-control @error('idioma') is-invalid @enderror"
                               value="{{ old('idioma') }}" placeholder="Ej: Español">
                        @error('idioma') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Moneda Preferida</label>
                        <select name="moneda_preferida" class="form-select @error('moneda_preferida') is-invalid @enderror">
                            <option value="">Seleccione...</option>
                            @foreach($monedas as $moneda)
                                <option value="{{ $moneda->id }}" {{ old('moneda_preferida') == $moneda->id ? 'selected' : '' }}>
                                    {{ $moneda->codigo }} - {{ $moneda->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('moneda_preferida') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-success mt-4">
                    <i class="fas fa-save me-1"></i> Guardar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
