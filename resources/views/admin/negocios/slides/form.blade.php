@extends('layouts.volt')
@section('title', (isset($slide) ? 'Editar' : 'Nuevo') . ' Slide')
@section('content')

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.negocios.index') }}">Negocios</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.negocios.edit', $negocio) }}">{{ $negocio->nombre }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.negocios.slides.index', $negocio) }}">Slides</a></li>
            <li class="breadcrumb-item active">{{ isset($slide) ? 'Editar' : 'Nuevo' }}</li>
        </ol>
    </nav>

    <h1>{{ isset($slide) ? 'Editar' : 'Nuevo' }} Slide</h1>

    <form action="{{ isset($slide) ? route('admin.negocios.slides.update', [$negocio, $slide]) : route('admin.negocios.slides.store', $negocio) }}" method="POST" enctype="multipart/form-data" class="mt-3">
        @csrf
        @if(isset($slide)) @method('PUT') @endif

        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <label for="imagen" class="form-label">Imagen del slide</label>
                    @if(isset($slide) && $slide->imagen)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $slide->imagen) }}" class="img-fluid rounded" style="max-height:200px;" alt="">
                        </div>
                    @endif
                    <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*" {{ isset($slide) ? '' : 'required' }}>
                    <div class="form-text">Recomendado: 1920x400px o similar</div>
                </div>

                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" name="titulo" id="titulo" class="form-control" value="{{ old('titulo', $slide->titulo ?? '') }}" placeholder="Ej: El verdadero sabor del Perú">
                </div>

                <div class="mb-3">
                    <label for="subtitulo" class="form-label">Subtítulo</label>
                    <textarea name="subtitulo" id="subtitulo" class="form-control" rows="2" placeholder="Texto secundario opcional">{{ old('subtitulo', $slide->subtitulo ?? '') }}</textarea>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label for="boton_texto" class="form-label">Texto del botón</label>
                    <input type="text" name="boton_texto" id="boton_texto" class="form-control" value="{{ old('boton_texto', $slide->boton_texto ?? '') }}" placeholder="Ej: Ver Productos">
                </div>

                <div class="mb-3">
                    <label for="categoria_id" class="form-label">O enlazar a categoría</label>
                    <select name="categoria_id" id="categoria_id" class="form-select">
                        <option value="">-- Ninguna (usar URL manual) --</option>
                        @foreach($categorias ?? [] as $cat)
                            <option value="{{ $cat->id }}" {{ old('categoria_id', $slide->categoria_id ?? '') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->categoria ?? $cat->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Si eliges categoría, el botón enlazará a esa categoría.</small>
                </div>

                <div class="mb-3">
                    <label for="boton_url" class="form-label">O URL personalizada</label>
                    <input type="text" name="boton_url" id="boton_url" class="form-control" value="{{ old('boton_url', $slide->boton_url ?? '') }}" placeholder="Ej: /productos o https://...">
                    <small class="text-muted">Si pones URL manual, esto tiene prioridad sobre la categoría.</small>
                </div>

                <div class="mb-3">
                    <label for="orden" class="form-label">Orden</label>
                    <input type="number" name="orden" id="orden" class="form-control" value="{{ old('orden', $slide->orden ?? 0) }}" min="0">
                </div>
            </div>
        </div>

        <hr>
        <h5>Estilos del slide</h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="color_texto" class="form-label">Color del texto</label>
                <div class="input-group">
                    <input type="color" id="color_texto_picker" class="form-control form-control-color" value="{{ old('color_texto', $slide->color_texto ?? '#ffffff') }}" oninput="document.getElementById('color_texto').value=this.value">
                    <input type="text" name="color_texto" id="color_texto" class="form-control" value="{{ old('color_texto', $slide->color_texto ?? '') }}" placeholder="#ffffff" oninput="document.getElementById('color_texto_picker').value=this.value">
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <label for="color_boton_fondo" class="form-label">Color de fondo del botón</label>
                <div class="input-group">
                    <input type="color" id="color_boton_fondo_picker" class="form-control form-control-color" value="{{ old('color_boton_fondo', $slide->color_boton_fondo ?? '#D4A373') }}" oninput="document.getElementById('color_boton_fondo').value=this.value">
                    <input type="text" name="color_boton_fondo" id="color_boton_fondo" class="form-control" value="{{ old('color_boton_fondo', $slide->color_boton_fondo ?? '') }}" placeholder="#D4A373" oninput="document.getElementById('color_boton_fondo_picker').value=this.value">
                </div>
                <small class="text-muted">Vacío = usa el color acento del tema</small>
            </div>
            <div class="col-md-4 mb-3">
                <label for="color_boton_texto" class="form-label">Color del texto del botón</label>
                <div class="input-group">
                    <input type="color" id="color_boton_texto_picker" class="form-control form-control-color" value="{{ old('color_boton_texto', $slide->color_boton_texto ?? '#000000') }}" oninput="document.getElementById('color_boton_texto').value=this.value">
                    <input type="text" name="color_boton_texto" id="color_boton_texto" class="form-control" value="{{ old('color_boton_texto', $slide->color_boton_texto ?? '') }}" placeholder="#000000" oninput="document.getElementById('color_boton_texto_picker').value=this.value">
                </div>
                <small class="text-muted">Vacío = #000 (negro)</small>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="posicion" class="form-label">Posición del texto</label>
                <select name="posicion" id="posicion" class="form-select">
                    <option value="left" {{ old('posicion', $slide->posicion ?? '') == 'left' ? 'selected' : '' }}>Izquierda</option>
                    <option value="center" {{ old('posicion', $slide->posicion ?? '') == 'center' ? 'selected' : '' }}>Centrado</option>
                    <option value="right" {{ old('posicion', $slide->posicion ?? '') == 'right' ? 'selected' : '' }}>Derecha</option>
                </select>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">{{ isset($slide) ? 'Actualizar' : 'Crear' }} Slide</button>
            <a href="{{ route('admin.negocios.slides.index', $negocio) }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>

@endsection
