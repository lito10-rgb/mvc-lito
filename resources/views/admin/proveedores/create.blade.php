@extends('layouts.admin')

@section('title', 'Nuevo Proveedor')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Nuevo Proveedor</h3>
        <a href="{{ route('admin.proveedores.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.proveedores.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nombre del contacto</label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Empresa</label>
                        <input type="text" name="empresa" class="form-control @error('empresa') is-invalid @enderror"
                               value="{{ old('empresa') }}">
                        @error('empresa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">RUC</label>
                        <input type="text" name="ruc" class="form-control @error('ruc') is-invalid @enderror"
                               value="{{ old('ruc') }}">
                        @error('ruc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
                               value="{{ old('telefono') }}">
                        @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Celular</label>
                        <input type="text" name="celular" class="form-control @error('celular') is-invalid @enderror"
                               value="{{ old('celular') }}">
                        @error('celular')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email 2</label>
                        <input type="email" name="email2" class="form-control @error('email2') is-invalid @enderror"
                               value="{{ old('email2') }}">
                        @error('email2')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Web</label>
                        <input type="url" name="web" class="form-control @error('web') is-invalid @enderror"
                               value="{{ old('web') }}" placeholder="https://">
                        @error('web')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror"
                               value="{{ old('direccion') }}">
                        @error('direccion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">País</label>
                        <select id="pais" name="pais_id" class="form-select @error('pais_id') is-invalid @enderror">
                            <option value="">Seleccionar</option>
                            @foreach($paises as $pais)
                                <option value="{{ $pais->id }}" {{ old('pais_id') == $pais->id ? 'selected' : '' }}>
                                    {{ $pais->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('pais_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Estado / Departamento</label>
                        <select id="estado" name="departamento_id" class="form-select @error('departamento_id') is-invalid @enderror">
                            <option value="">Seleccionar</option>
                            @foreach($departamentos as $dep)
                                <option value="{{ $dep->id }}" {{ old('departamento_id') == $dep->id ? 'selected' : '' }}>
                                    {{ $dep->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('departamento_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Provincia</label>
                        <select id="provincia" name="provincia_id" class="form-select @error('provincia_id') is-invalid @enderror">
                            <option value="">Seleccionar</option>
                            @foreach($provincias as $prov)
                                <option value="{{ $prov->id }}" {{ old('provincia_id') == $prov->id ? 'selected' : '' }}>
                                    {{ $prov->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('provincia_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Distrito</label>
                        <select id="distrito" name="distrito_id" class="form-select @error('distrito_id') is-invalid @enderror">
                            <option value="">Seleccionar</option>
                            @foreach($distritos as $dis)
                                <option value="{{ $dis->id }}" {{ old('distrito_id') == $dis->id ? 'selected' : '' }}>
                                    {{ $dis->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('distrito_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Facebook</label>
                        <input type="url" name="facebook" class="form-control @error('facebook') is-invalid @enderror"
                               value="{{ old('facebook') }}" placeholder="https://facebook.com/...">
                        @error('facebook')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Instagram</label>
                        <input type="url" name="instagram" class="form-control @error('instagram') is-invalid @enderror"
                               value="{{ old('instagram') }}" placeholder="https://instagram.com/...">
                        @error('instagram')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Categoría</label>
                        <select id="categoria" name="categoria_id" class="form-select @error('categoria_id') is-invalid @enderror">
                            <option value="">Seleccionar</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->categoria }}
                                </option>
                            @endforeach
                        </select>
                        @error('categoria_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Subcategoría</label>
                        <select id="subcategoria" name="subcategoria_id" class="form-select @error('subcategoria_id') is-invalid @enderror">
                            <option value="">Seleccionar</option>
                            @foreach($subcategorias as $sub)
                                <option value="{{ $sub->id }}" {{ old('subcategoria_id') == $sub->id ? 'selected' : '' }}>
                                    {{ $sub->subcategoria }}
                                </option>
                            @endforeach
                        </select>
                        @error('subcategoria_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror"
                                  rows="4">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Código Postal</label>
                        <input type="text" name="codigo_postal" class="form-control @error('codigo_postal') is-invalid @enderror"
                               value="{{ old('codigo_postal') }}">
                        @error('codigo_postal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Guardar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/perfil.js')
@endpush
