@csrf

<div class="row">
    {{-- Tipo --}}
    <div class="col-md-4 mb-3">
        <label for="tipo">Tipo</label>
        <select name="tipo" class="form-select">
            <option value="fisico" {{ old('tipo', $producto->tipo ?? '') == 'fisico' ? 'selected' : '' }}>Físico</option>
            <option value="servicio" {{ old('tipo', $producto->tipo ?? '') == 'servicio' ? 'selected' : '' }}>Servicio</option>
        </select>
    </div>

    {{-- Estado --}}
    <div class="col-md-4 mb-3">
        <label for="estado">Estado</label>
        <select name="estado" class="form-select">
            <option value="1" {{ old('estado', $producto->estado ?? 1) == 1 ? 'selected' : '' }}>Activo</option>
            <option value="0" {{ old('estado', $producto->estado ?? 0) == 0 ? 'selected' : '' }}>Inactivo</option>
        </select>
    </div>

    {{-- Título --}}
    <div class="col-md-4 mb-3">
        <label for="titulo">Título</label>
        <input type="text" name="titulo" class="form-control" value="{{ old('titulo', $producto->titulo ?? '') }}" required>
    </div>

    {{-- Titular --}}
    <div class="col-md-6 mb-3">
        <label for="titular">Titular</label>
        <input type="text" name="titular" class="form-control" value="{{ old('titular', $producto->titular ?? '') }}">
    </div>

    {{-- Descripción --}}
    <div class="col-md-6 mb-3">
        <label for="descripcion">Descripción</label>
        <textarea name="descripcion" class="form-control">{{ old('descripcion', $producto->descripcion ?? '') }}</textarea>
    </div>

    {{-- Multimedia (YouTube iframe/link) --}}
    <div class="col-md-12 mb-3">
        <label for="multimedia">Multimedia (YouTube, etc)</label>
        <textarea name="multimedia" class="form-control" rows="2">{{ old('multimedia', $producto->multimedia ?? '') }}</textarea>
    </div>

    {{-- Detalles dinámicos (puedes usar JS para agregar pares clave:valor) --}}
    <div class="col-md-12 mb-3">
        <label for="detalles">Detalles (ej: color: rojo, modelo: cfe01)</label>
        <textarea name="detalles" class="form-control" rows="2">{{ old('detalles', $producto->detalles ?? '') }}</textarea>
    </div>

    {{-- Precio --}}
    <div class="col-md-4 mb-3">
        <label for="precio">Precio</label>
        <input type="number" step="0.01" name="precio" class="form-control" value="{{ old('precio', $producto->precio ?? 0) }}">
    </div>

    {{-- Portada --}}
    <div class="col-md-4 mb-3">
        <label for="portada">Portada</label>
        <input type="file" name="portada" class="form-control">
        @if(isset($producto->portada))
            <img src="{{ asset('storage/' . $producto->portada) }}" class="img-fluid mt-2" style="max-height: 80px;">
        @endif
    </div>

    {{-- Oferta --}}
    <div class="col-md-4 mb-3">
        <label for="oferta">Oferta</label>
        <select name="oferta" class="form-select">
            <option value="">Sin oferta</option>
            @foreach ([5,10,15,20,25,50,80] as $oferta)
                <option value="{{ $oferta }}" {{ old('oferta', $producto->oferta ?? '') == $oferta ? 'selected' : '' }}>{{ $oferta }}% descuento</option>
            @endforeach
        </select>
    </div>

    {{-- Precio y descuento de la oferta --}}
    <div class="col-md-3 mb-3">
        <label for="precioOferta">Precio Oferta</label>
        <input type="number" step="0.01" name="precioOferta" class="form-control" value="{{ old('precioOferta', $producto->precioOferta ?? '') }}">
    </div>

    <div class="col-md-3 mb-3">
        <label for="descuentoOferta">Descuento Oferta</label>
        <input type="number" step="0.01" name="descuentoOferta" class="form-control" value="{{ old('descuentoOferta', $producto->descuentoOferta ?? '') }}">
    </div>

    <div class="col-md-3 mb-3">
        <label for="imgOferta">Imagen Oferta</label>
        <input type="file" name="imgOferta" class="form-control">
        @if(isset($producto->imgOferta))
            <img src="{{ asset('storage/' . $producto->imgOferta) }}" class="img-fluid mt-2" style="max-height: 60px;">
        @endif
    </div>

    <div class="col-md-3 mb-3">
        <label for="finOferta">Fin de la oferta</label>
        <input type="date" name="finOferta" class="form-control" value="{{ old('finOferta', $producto->finOferta ?? '') }}">
    </div>

    {{-- Peso y Entrega --}}
    <div class="col-md-4 mb-3">
        <label for="peso">Peso</label>
        <input type="text" name="peso" class="form-control" value="{{ old('peso', $producto->peso ?? '') }}">
    </div>

    <div class="col-md-4 mb-3">
        <label for="entrega">Entrega</label>
        <input type="text" name="entrega" class="form-control" value="{{ old('entrega', $producto->entrega ?? '') }}">
    </div>

    {{-- Combos relacionados --}}
    <div class="col-md-4 mb-3">
        <label for="categoria_id">Categoría</label>
        <select name="categoria_id" class="form-select">
            <option value="">-- Selecciona --</option>
            @foreach($categorias as $cat)
                <option value="{{ $cat->id }}" {{ old('categoria_id', $producto->categoria_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label for="subcategoria_id">Subcategoría</label>
        <select name="subcategoria_id" class="form-select">
            <option value="">-- Selecciona --</option>
            @foreach($subcategorias as $sub)
                <option value="{{ $sub->id }}" {{ old('subcategoria_id', $producto->subcategoria_id ?? '') == $sub->id ? 'selected' : '' }}>{{ $sub->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label for="marca_id">Marca</label>
        <select name="marca_id" class="form-select">
            <option value="">-- Selecciona --</option>
            @foreach($marcas as $marca)
                <option value="{{ $marca->id }}" {{ old('marca_id', $producto->marca_id ?? '') == $marca->id ? 'selected' : '' }}>{{ $marca->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label for="proveedor_id">Proveedor</label>
        <select name="proveedor_id" class="form-select">
            <option value="">-- Selecciona --</option>
            @foreach($proveedores as $prov)
                <option value="{{ $prov->id }}" {{ old('proveedor_id', $producto->proveedor_id ?? '') == $prov->id ? 'selected' : '' }}>{{ $prov->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label for="fecha">Fecha</label>
        <input type="date" name="fecha" class="form-control" value="{{ old('fecha', $producto->fecha ?? now()->format('Y-m-d')) }}">
    </div>

    {{-- Submit --}}
    <div class="col-12">
        <button class="btn btn-primary">{{ $modo ?? 'Guardar' }}</button>
    </div>
</div>
