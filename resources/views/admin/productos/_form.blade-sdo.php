{{-- Formulario reutilizable para crear/editar productos --}}
<div class="row g-3">

    <div class="col-md-6">
        <label class="form-label">Título</label>
        <input type="text" name="titulo" class="form-control"
            value="{{ old('titulo', $producto->titulo ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Titular</label>
        <input type="text" name="titular" class="form-control"
            value="{{ old('titular', $producto->titular ?? '') }}">
    </div>

    <div class="col-12">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $producto->descripcion ?? '') }}</textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label">Tipo</label>
        <input type="text" name="tipo" class="form-control"
            value="{{ old('tipo', $producto->tipo ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Ruta</label>
        <input type="text" name="ruta" class="form-control"
            value="{{ old('ruta', $producto->ruta ?? '') }}">
    </div>

    <div class="col-12">
        <label class="form-label">Multimedia</label>
        <input type="text" name="multimedia" class="form-control"
            value="{{ old('multimedia', $producto->multimedia ?? '') }}">
    </div>

    <div class="col-12">
        <label class="form-label">Detalles</label>
        <textarea name="detalles" class="form-control" rows="3">{{ old('detalles', $producto->detalles ?? '') }}</textarea>
    </div>

    <div class="col-md-4">
        <label class="form-label">Precio</label>
        <input type="number" name="precio" class="form-control"
            value="{{ old('precio', $producto->precio ?? '') }}" step="0.01">
    </div>

    <div class="col-md-4">
        <label class="form-label">Peso (kg)</label>
        <input type="number" name="peso" class="form-control"
            value="{{ old('peso', $producto->peso ?? '') }}" step="0.01">
    </div>

    <div class="col-md-4">
        <label class="form-label">Entrega (días)</label>
        <input type="number" name="entrega" class="form-control"
            value="{{ old('entrega', $producto->entrega ?? '') }}" step="1">
    </div>

    <div class="col-md-6">
        <label class="form-label">Portada (URL)</label>
        <input type="text" name="portada" class="form-control"
            value="{{ old('portada', $producto->portada ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Imagen de oferta (URL)</label>
        <input type="text" name="imgOferta" class="form-control"
            value="{{ old('imgOferta', $producto->imgOferta ?? '') }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">Oferta (%)</label>
        <input type="number" name="descuentoOferta" class="form-control"
            value="{{ old('descuentoOferta', $producto->descuentoOferta ?? '') }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">Precio oferta</label>
        <input type="number" name="precioOferta" class="form-control"
            value="{{ old('precioOferta', $producto->precioOferta ?? '') }}" step="0.01">
    </div>

    <div class="col-md-4">
        <label class="form-label">Fin oferta</label>
        <input type="datetime-local" name="finOferta" class="form-control"
            value="{{ old('finOferta', isset($producto->finOferta) ? \Carbon\Carbon::parse($producto->finOferta)->format('Y-m-d\TH:i') : '') }}">
    </div>

    {{-- Selects relacionados --}}
    <div class="col-md-6">
        <label class="form-label">Categoría</label>
        <select name="categoria_id" class="form-select" required>
            <option value="">Seleccionar...</option>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}"
                    {{ old('categoria_id', $producto->categoria_id ?? '') == $categoria->id ? 'selected' : '' }}>
                    {{ $categoria->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Subcategoría</label>
        <select name="subcategoria_id" class="form-select" required>
            <option value="">Seleccionar...</option>
            @foreach($subcategorias as $sub)
                <option value="{{ $sub->id }}"
                    {{ old('subcategoria_id', $producto->subcategoria_id ?? '') == $sub->id ? 'selected' : '' }}>
                    {{ $sub->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Marca</label>
        <select name="marca_id" class="form-select">
            <option value="">Seleccionar...</option>
            @foreach($marcas as $marca)
                <option value="{{ $marca->id }}"
                    {{ old('marca_id', $producto->marca_id ?? '') == $marca->id ? 'selected' : '' }}>
                    {{ $marca->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Proveedor</label>
        <select name="proveedor_id" class="form-select">
            <option value="">Seleccionar...</option>
            @foreach($proveedores as $proveedor)
                <option value="{{ $proveedor->id }}"
                    {{ old('proveedor_id', $producto->proveedor_id ?? '') == $proveedor->id ? 'selected' : '' }}>
                    {{ $proveedor->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Estado</label>
        <select name="estado" class="form-select">
            <option value="1" {{ old('estado', $producto->estado ?? 1) == 1 ? 'selected' : '' }}>Activo</option>
            <option value="0" {{ old('estado', $producto->estado ?? 1) == 0 ? 'selected' : '' }}>Inactivo</option>
        </select>
    </div>

</div>
