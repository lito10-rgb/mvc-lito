@extends('layouts.admin')

@section('title', 'Editar Subcategoría')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Editar Subcategoría</h3>
        <a href="{{ route('admin.subcategorias.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.subcategorias.update', $subcategoria) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Categoría</label>
                    <select name="id_categoria" class="form-control @error('id_categoria') is-invalid @enderror">
                        @foreach($categorias as $c)
                        <option value="{{ $c->id }}" {{ old('id_categoria', $subcategoria->id_categoria) == $c->id ? 'selected' : '' }}>
                            {{ $c->categoria }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_categoria')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="subcategoria" class="form-control @error('subcategoria') is-invalid @enderror"
                           value="{{ old('subcategoria', $subcategoria->subcategoria) }}" required>
                    @error('subcategoria')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Actualizar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
