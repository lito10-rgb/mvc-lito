<form action="{{ $route }}" method="POST" enctype="multipart/form-data">
    
    @csrf
    @if(strtoupper($method) === 'PUT')
    @method('PUT')
    @endif
<!-- @csrf -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mb-3">
    <label for="tipo" class="form-label">Tipo</label>
    <select name="tipo" id="tipo" class="form-select" required>
        <option value="">-- Seleccione --</option>
        <option value="fisico" {{ old('tipo', $producto->tipo ?? 'fisico') == 'fisico' ? 'selected' : '' }}>Físico</option>
        <option value="servicio" {{ old('tipo', $producto->tipo ?? '') == 'servicio' ? 'selected' : '' }}>Servicio</option>
    </select>
</div>

<div class="mb-3">
    <label for="titulo" class="form-label">Título</label>
    <input type="text" name="titulo" id="titulo" class="form-control" value="{{ old('titulo', $producto->titulo ?? '') }}" required>
</div>

<!-- <div class="mb-3">
    <label for="ruta" class="form-label">Ruta Amigable</label>
    <input type="text" name="ruta" id="ruta" class="form-control" value="{{ old('ruta', $producto->ruta ?? '') }}" readonly>
    <small class="form-text text-muted">Se genera automáticamente desde el título.</small>
</div> -->
<div class="mb-3">
    <label for="ruta" class="form-label">Ruta (URL amigable)</label>
    <input type="text" name="ruta" id="ruta" class="form-control" value="{{ old('ruta', $producto->ruta ?? '') }}">
    <small class="form-text text-muted">Puedes editar la URL si lo deseas. Si la dejas vacía se generará automáticamente.</small>
</div>
{{-- Campos para SEO (se usarán en la tabla cabeceras) value="{{ old('palabrasClaves', $producto->palabrasClaves ?? '') }}">--}}
<div class="mb-3">
    <label for="palabras_claves" class="form-label">Palabras Clave (SEO)</label>
    <input type="text" name="palabras_claves" id="palabras_claves" class="form-control" 
    value="{{ old('palabras_claves', $producto->cabecera?->palabras_claves ?? '') }}">
    <small class="form-text text-muted">Separadas por comas, ejemplo: café, especialidad, orgánico</small>
</div>
<!-- {{-- Campos para SEO (se usarán en la tabla cabeceras) --}}
<div class="mb-3">
    <label for="palabras_claves" class="form-label">Palabras Clave (SEO)</label>
    <input type="text" name="palabras_claves" id="palabras_claves" class="form-control" 
    value="{{ old('palabras_claves', $cabecera->palabras_claves ?? '') }}">
    <small class="form-text text-muted">Separadas por comas, ejemplo: café, especialidad, orgánico</small>  
</div> -->
<div class="mb-3">
    <label for="estado" class="form-label">Estado</label>
    <select name="estado" id="estado" class="form-select" required>
        <option value="1" {{ old('estado', $producto->estado ?? '') == 1 ? 'selected' : '' }}>Activo</option>
        <option value="0" {{ old('estado', $producto->estado ?? '') == 0 ? 'selected' : '' }}>Inactivo</option>
    </select>
</div>

<div class="mb-3">
    <label for="titular" class="form-label">Titular</label>
    <input type="text" name="titular" id="titular" class="form-control" value="{{ old('titular', $producto->titular ?? '') }}">
</div>

<div class="mb-3">
    <label for="descripcion" class="form-label">Descripción</label>
    <textarea name="descripcion" id="descripcion" class="form-control" rows="3">{{ old('descripcion', $producto->descripcion ?? '') }}</textarea>
</div>


<div class="mb-3">
    <label for="multimedia" class="form-label">Imágenes del producto</label>

    <!-- Campo para subir múltiples imágenes -->
    <input type="file" name="multimedia[]" id="multimedia" class="form-control" accept="image/*" multiple>

    <!-- Mostrar las imágenes si existen -->
  <!--   @if(isset($producto) && $producto->multimedia)
        @php
            $imagenes = explode(',', $producto->multimedia);
        @endphp

        <div class="row mt-3">
            @foreach($imagenes as $imagen)
                <div class="col-6 col-md-3 mb-3">
                    <img src="{{ asset('storage/' . $imagen) }}" alt="Imagen" class="img-fluid rounded border shadow-sm">
                </div>
            @endforeach
        </div>
    @endif -->

    @if(isset($producto) && $producto->multimedia)
    @php
        $imagenes = json_decode($producto->multimedia, true) ?? [];
        $imagenes = array_map(fn($img) => is_array($img) ? ($img['foto'] ?? '') : $img, $imagenes);
        $imagenes = array_values(array_filter($imagenes));
    @endphp
    <!-- <input type="hidden" name="imagenes_actuales" id="imagenes_actuales" value='@json($imagenes)'> -->

    <!-- <div class="row mt-3">
        @foreach($imagenes as $imagen)
            <div class="col-6 col-md-3 mb-3">
                <img src="{{ asset('storage/' . $imagen) }}" alt="Imagen" class="img-fluid rounded border shadow-sm">
            </div>
        @endforeach
    </div> -->
    <input type="hidden" name="imagenes_actuales" id="imagenes_actuales" value='@json($imagenes)'>

<div class="row mt-3">
    @foreach($imagenes as $imagen)
        <div class="col-6 col-md-3 mb-3 image-wrapper">
            <img src="{{ asset('storage/' . $imagen) }}" alt="Imagen" class="img-fluid rounded border shadow-sm mb-2">
            <button type="button" class="btn btn-sm btn-danger btn-remove-image" data-imagen="{{ $imagen }}">Eliminar</button>
        </div>
    @endforeach
</div>


    @endif

</div>


<div class="mb-3">
    <label for="detalles" class="form-label">Detalles (Ej: color: rojo; modelo: cfe01)</label>
    <textarea name="detalles" id="detalles" class="form-control" rows="3" placeholder="color: rojo; modelo: cfe01;">{{ old('detalles', $producto->detalles ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label for="precio" class="form-label">Precio</label>
    <div class="input-group">
        <button type="button" class="btn btn-outline-secondary" id="precio-minus">-</button>
        <input type="number" step="0.01" name="precio" id="precio" class="form-control text-end" value="{{ old('precio', $producto->precio ?? 0) }}" required>
        <button type="button" class="btn btn-outline-secondary" id="precio-plus">+</button>
    </div>
</div>

<!-- <div class="mb-3">
    <label for="portada" class="form-label">Portada</label>
    <input type="file" name="portada" id="portada" class="form-control" accept="image/*"> -->
  <!--   @if(isset($producto) && $producto->portada)
        <img src="{{ asset('storage/' . $producto->portada) }}" alt="Portada" class="img-thumbnail mt-2" style="max-width: 150px;">
    @endif -->
    <!-- @if(isset($producto))
    <img src="{{ asset('storage/' . ($producto->portada ?? 'defaults/default-portada.jpg')) }}" 
         alt="Portada" class="img-thumbnail mt-2" style="max-width: 150px;">
    @endif
</div> -->
<div class="mb-3">
    <label for="portada" class="form-label">Portada</label>
    <input type="file" name="portada" id="portada" class="form-control" accept="image/*">
    @if(isset($producto))  
        <div id="current-portada" class="mt-2">
            <img src="{{ asset('storage/'. ($producto->portada ?? 'defaults/default-portada.jpg')) }}"
                 alt="Portada" class="img-thumbnail" style="max-width: 150px;">

            <button type="button" id="remove-portada" class="btn btn-sm btn-danger ms-2">
                Eliminar
            </button>
        </div>
    @endif

    <!-- Campo oculto para indicar eliminación -->
    <input type="hidden" name="remove_portada" id="remove_portada" value="0">
</div>
<div class="mb-3">
    <label for="vistas" class="form-label">Vistas</label>
    <input type="number" name="vistas" id="vistas" class="form-control" value="{{ old('vistas', $producto->vistas ?? rand(10, 500)) }}">
</div>

<div class="mb-3">
    <label for="ventas" class="form-label">Ventas</label>
    <input type="number" name="ventas" id="ventas" class="form-control" value="{{ old('ventas', $producto->ventas ?? rand(1, 100)) }}">
</div>

<div class="mb-3">
    <label for="vistasGratis" class="form-label">Vistas Gratis</label>
    <input type="number" name="vistasGratis" id="vistasGratis" class="form-control" value="{{ old('vistasGratis', $producto->vistasGratis ?? rand(0, 50)) }}">
</div>

<div class="mb-3">
    <label for="ventasGratis" class="form-label">Ventas Gratis</label>
    <input type="number" name="ventasGratis" id="ventasGratis" class="form-control" value="{{ old('ventasGratis', $producto->ventasGratis ?? rand(0, 20)) }}">
</div>
<!-- <input type="hidden" name="ofertadoPorCategoria" value="0"> -->
<div class="mb-3">
    <label for="ofertadoPorCategoria" class="form-label">Oferta por Categoría (%)</label>
    <select name="ofertadoPorCategoria" id="ofertadoPorCategoria" class="form-select">
        @php
            $descuentos = [0,5,10,15,20,25,50,80];
        @endphp
        <option value="0">-- Sin descuento --</option>
        @foreach($descuentos as $desc)
            <option value="{{ $desc }}" {{ old('ofertadoPorCategoria', $producto->ofertadoPorCategoria ?? '0') == $desc ? 'selected' : '' }}>
                {{ $desc }} %
            </option>
        @endforeach
    </select>
</div>
<!-- <input type="hidden" name="ofertadoPorSubCategoria" value="0"> -->
<div class="mb-3">
    <label for="ofertadoPorSubCategoria" class="form-label">Oferta por Subcategoría (%)</label>
    <select name="ofertadoPorSubCategoria" id="ofertadoPorSubCategoria" class="form-select">
        <option value="0">-- Sin descuento --</option>
        @foreach($descuentos as $desc)
            <option value="{{ $desc }}" {{ old('ofertadoPorSubCategoria', $producto->ofertadoPorSubCategoria ?? '0') == $desc ? 'selected' : '' }}>
                {{ $desc }} %
            </option>
        @endforeach
    </select>
</div>


<!-- Campo oculto: valor por defecto -->
<!-- <input type="hidden" name="oferta" value="0"> -->

<div class="mb-3">
    <label for="oferta" class="form-label">Oferta (%)</label>
    <select name="oferta" id="oferta" class="form-select">
        <option value="0">-- Sin descuento --</option>
        @foreach($descuentos as $desc)
            <option value="{{ $desc }}" {{ old('oferta', $producto->oferta ?? '0') == $desc ? 'selected' : '' }}>
                {{ $desc }} %
            </option>
        @endforeach
    </select>
</div>

<!-- 
<div class="mb-3">
    <label for="oferta" class="form-label">Oferta (%)</label>
    <select name="oferta" id="oferta" class="form-select">
        <option value="">-- Sin descuento --</option>
        @foreach($descuentos as $desc)
            <option value="{{ $desc }}" {{ old('oferta', $producto->oferta ?? '') == $desc ? 'selected' : '' }}>
                {{ $desc }} %
            </option>
        @endforeach
    </select>
</div> -->

<div class="mb-3">
    <label for="precioOferta" class="form-label">Precio Oferta</label>
    <input type="number" step="0.01" name="precioOferta" id="precioOferta" class="form-control" value="{{ old('precioOferta', $producto->precioOferta ?? 0) }}">
</div>

<div class="mb-3">
    <label for="descuentoOferta" class="form-label">Descuento Oferta</label>
    <input type="number" step="0.01" name="descuentoOferta" id="descuentoOferta" class="form-control" value="{{ old('descuentoOferta', $producto->descuentoOferta ?? 0) }}">
</div>

<div class="mb-3">
    <label for="imgOferta" class="form-label">Imagen Oferta</label>
    <input type="file" name="imgOferta" id="imgOferta" class="form-control" accept="image/*">
    @if(isset($producto) && $producto->imgOferta)
        <img src="{{ asset('storage/' . $producto->imgOferta) }}" alt="Imagen Oferta" class="img-thumbnail mt-2" style="max-width: 150px;">
    @endif
</div>

<div class="mb-3">
    <label for="finOferta" class="form-label">Fin de Oferta</label>
    <input type="date" name="finOferta" id="finOferta" class="form-control" value="{{ old('finOferta', isset($producto->finOferta) ? \Carbon\Carbon::parse($producto->finOferta)->format('Y-m-d') : '') }}">
</div>

<div class="mb-3">
    <label for="peso" class="form-label">Peso</label>
    <input type="text" name="peso" id="peso" class="form-control" value="{{ old('peso', $producto->peso ?? '') }}">
</div>

<div class="mb-3">
    <label for="entrega" class="form-label">Entrega</label>
    <input type="text" name="entrega" id="entrega" class="form-control" value="{{ old('entrega', $producto->entrega ?? 2) }}">
</div>

<!-- <div class="mb-3">
    <label for="categoria_id" class="form-label">Categoría</label>
    <select name="categoria_id" id="categoria_id" class="form-select" required>
        <option value="">-- Seleccione Categoría --</option>
        @foreach($categorias as $cat)
            <option value="{{ $cat->id }}" {{ old('categoria_id', $producto->categoria_id ?? '') == $cat->id ? 'selected' : '' }}>
                {{ $cat->categoria ?? $cat->nombre }}
            </option>
        @endforeach
    </select>
</div> -->
<div class="mb-3">
    <label for="categoria_id" class="form-label">Categoría</label>
    <select name="categoria_id" id="categoria_id" class="form-select">
        @foreach($categorias as $cat)
            <option value="{{ $cat->id }}"
                {{ old('categoria_id', $producto->categoria_id ?? 1) == $cat->id ? 'selected' : '' }}>
                {{ $cat->categoria ?? $cat->nombre }}
            </option>
        @endforeach
    </select>
</div>

<!-- <div class="mb-3">
    <label for="subcategoria_id" class="form-label">Subcategorías</label>
    <select name="subcategoria_id" id="subcategoria_id" class="form-select" required>
        <option value="">-- Seleccione Subcategorías --</option>
        @foreach($subcategorias as $subcat)
            <option value="{{ $subcat->id }}"
                {{ old('subcategoria_id', $producto->subcategoria_id ?? 2) == $subcat->id ? 'selected' : '' }}>
                {{ $subcat->subcategoria ?? $subcat->nombre }}
            </option>
        @endforeach
    </select>
</div> -->
<div class="mb-3">
    <label for="subcategoria_id" class="form-label">Subcategoría</label>
    <select name="subcategoria_id" id="subcategoria_id" class="form-select" >
        <option value="0">-- Seleccione Subcategoría --</option>
        @foreach($subcategorias as $subcat)
            <option value="{{ $subcat->id }}"
                {{ old('subcategoria_id', $producto->subcategoria_id ?? '') == $subcat->id ? 'selected' : '' }}>
                {{ $subcat->subcategoria }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="marca_id" class="form-label">Marca</label>
    <select name="marca_id" id="marca_id" class="form-select">
        <option value="1">-- Seleccione Marca --</option>
        @foreach($marcas as $marca)
            <option value="{{ $marca->id }}" 
                {{ old('marca_id', $producto->marca_id ?? 1) == $marca->id ? 'selected' : '' }}>
                {{ $marca->nombre }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="proveedor_id" class="form-label">Proveedor</label>
    <select name="proveedor_id" id="proveedor_id" class="form-select" >
        <option value="1">-- Seleccione Proveedor --</option>
        @foreach($proveedores as $prov)
            <option value="{{ $prov->id }}" 
                {{ old('proveedor_id', $producto->proveedor_id ?? 1) == $prov->id ? 'selected' : '' }}>
                {{ $prov->nombre }}
            </option>
        @endforeach
    </select>
</div>

<!-- <div class="mb-3">
    <label for="fecha" class="form-label">Fecha</label>
    <input type="date" name="fecha" id="fecha" class="form-control" value="{{ old('fecha', isset($producto->fecha) ? \Carbon\Carbon::parse($producto->fecha)->format('Y-m-d') : '') }}" required>
</div> -->
<div class="mb-3">
    <label for="fecha" class="form-label">Fecha</label>
    <input type="date" name="fecha" id="fecha" class="form-control"
        value="{{ old('fecha', isset($producto->fecha) ? \Carbon\Carbon::parse($producto->fecha)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d')) }}"
        required>
</div>

<div class="mb-3">
    <label class="form-label">Negocios (donde se publica)</label>
    <div class="row">
        @foreach($negocios as $neg)
        <div class="col-md-4">
            <div class="form-check">
                <input type="checkbox" name="negocios[]" value="{{ $neg->id }}" class="form-check-input"
                    id="neg_{{ $neg->id }}"
                    {{ in_array($neg->id, old('negocios', $productoNegocioIds ?? [])) ? 'checked' : '' }}>
                <label class="form-check-label" for="neg_{{ $neg->id }}">{{ $neg->nombre }}</label>
            </div>
        </div>
        @endforeach
    </div>
</div>

<button type="submit" class="btn btn-primary">Guardar</button>
</form>
<!-- <script>
    // Auto genera la ruta amigable basada en el título
    document.getElementById('titulo').addEventListener('input', function() {
        let texto = this.value.toLowerCase().trim()
            .replace(/[\s\W-]+/g, '-');
        document.getElementById('ruta').value = texto;
    });

    // Botones + y - para precio
    document.getElementById('precio-plus').addEventListener('click', function(){
        let input = document.getElementById('precio');
        input.stepUp();
    });
    document.getElementById('precio-minus').addEventListener('click', function(){
        let input = document.getElementById('precio');
        input.stepDown();
    });
</script>
 -->
 <script>
   document.addEventListener('DOMContentLoaded', function () {
    const nombreInput = document.getElementById('titulo');
    const rutaInput = document.getElementById('ruta');
    const categoriaSelect = document.getElementById('categoria_id');
    const subcategoriaSelect = document.getElementById('subcategoria_id');

    // ======== Generar slug automático ========
    let rutaEditadaManualmente = false;
    let titularEditadoManualmente = false;
    let palabrasEditadoManualmente = false;

    const titularInput = document.getElementById('titular');
    const palabrasInput = document.getElementById('palabras_claves');

    rutaInput.addEventListener('input', function () {
        rutaEditadaManualmente = true;
    });
    titularInput.addEventListener('input', function () {
        titularEditadoManualmente = true;
    });
    palabrasInput.addEventListener('input', function () {
        palabrasEditadoManualmente = true;
    });

    const esCreacion = {{ $producto ? 'false' : 'true' }};

    function autoCompletar() {
        const title = nombreInput.value.trim();
        if (!title) return;
        if (!rutaEditadaManualmente) {
            const slug = title.toLowerCase().trim().replace(/[^\w\s-]/g, '').replace(/\s+/g, '-');
            rutaInput.value = slug;
        }
        if (esCreacion && !titularEditadoManualmente) {
            titularInput.value = title;
        }
        if (esCreacion && !palabrasEditadoManualmente) {
            palabrasInput.value = title;
        }
    }

    nombreInput.addEventListener('input', autoCompletar);

    // ======== Evento al cambiar categoría ========
    categoriaSelect.addEventListener('change', function () {
        const categoriaId = this.value;

        // Limpiar subcategorías actuales
        subcategoriaSelect.innerHTML = '<option value="">-- Cargando... --</option>';

        if (categoriaId) {
            fetch(`/mvc-lito/public/subcategoria/${categoriaId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Error en la respuesta de la red');
                    return response.json();
                })
                .then(data => {
                    let opciones = '<option value="2">-- Seleccione Subcategoría --</option>';
                    data.forEach(sub => {
                        opciones += `<option value="${sub.id}">${sub.subcategoria}</option>`;
                    });

                    subcategoriaSelect.innerHTML = opciones;

                    // 🧠 Selecciona subcategoría si está en modo edición
                    const selectedSubcategoria = subcategoriaSelect.getAttribute('data-selected');
                    if (selectedSubcategoria) {
                        subcategoriaSelect.value = selectedSubcategoria;
                    }
                })
                .catch(error => {
                    console.error('Error al cargar subcategorías:', error);
                    subcategoriaSelect.innerHTML = '<option value="">-- Error al cargar --</option>';
                });
        } else {
            subcategoriaSelect.innerHTML = '<option value="">-- Seleccione Subcategoría --</option>';
        }
    });

    // ======== Ejecutar cambio en carga si ya hay una categoría seleccionada ========
    // if (categoriaSelect.value) {
    //     categoriaSelect.dispatchEvent(new Event('change'));
    // }
});

    // Botones + y - para precio
    document.getElementById('precio-plus').addEventListener('click', function(){
        let input = document.getElementById('precio');
        input.stepUp();
    });
    document.getElementById('precio-minus').addEventListener('click', function(){
        let input = document.getElementById('precio');
        input.stepDown();
    });
</script>
