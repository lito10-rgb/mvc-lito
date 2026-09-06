<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Marca;
use App\Models\Proveedor;
use App\Models\Cabecera;
use App\Models\Negocio;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
class ProductoController extends Controller
{
    

    public function index(Request $request)
{
    $query = Producto::with(['categoria', 'subcategoria', 'marca', 'proveedor', 'negocios']);

    if ($buscar = $request->input('buscar')) {
        $query->where(function ($q) use ($buscar) {
            $q->where('titulo', 'like', "%{$buscar}%")
              ->orWhere('descripcion', 'like', "%{$buscar}%")
              ->orWhere('detalles', 'like', "%{$buscar}%")
              ->orWhere('titular', 'like', "%{$buscar}%");
        });
    }

    // filtrar por categoría
    if ($catId = $request->input('categoria_id')) {
        $query->where('categoria_id', $catId);
    }

    // filtrar por subcategoría
    if ($subId = $request->input('subcategoria_id')) {
        $query->where('subcategoria_id', $subId);
    }

    // filtrar por negocio (por defecto solo Equipos y Maquinas)
    $negId = $request->input('negocio_id');
    if ($negId === null) {
        $negId = 1;
    }
    if ($negId !== '' && $negId !== null) {
        $query->whereHas('negocios', function ($q) use ($negId) {
            $q->where('negocio_id', $negId);
        });
    }

    // ordenamiento
    $orden = $request->input('orden', 'reciente');
    switch ($orden) {
        case 'vistas':
            $query->orderBy('vistas', 'desc');
            break;
        case 'ventas':
            $query->orderBy('ventas', 'desc');
            break;
        default:
            $query->orderBy('id', 'desc');
            break;
    }

    $productos = $query->paginate(10)->withQueryString();
    $categorias = Categoria::orderBy('nombre')->get();
    $subcategorias = Subcategoria::orderBy('subcategoria')->get();
    $negocios = Negocio::orderBy('nombre')->get();
    $marcas = Marca::orderBy('nombre')->get();
    return view('admin.productos.index', compact('productos', 'categorias', 'subcategorias', 'negocios', 'marcas'));
}

    public function create()
    {
        $categorias = Categoria::all();
        $subcategorias = Subcategoria::all();
        $marcas = Marca::all();
        $proveedores = Proveedor::all();
        $cabecera = null;
        $negocios = Negocio::all();
        $productoNegocioIds = Negocio::where('dominio', 'equiposymaquinas.com')->pluck('id')->toArray();
        $productoCartaIds = [];

        return view('admin.productos.create', compact('categorias', 'subcategorias', 'marcas', 'proveedores', 'cabecera', 'negocios', 'productoNegocioIds', 'productoCartaIds'));
    }

    

    public function show(Producto $producto)
    {
        return view('admin.productos.show', compact('producto'));
    }

    public function vistaRapida($id)
    {
        $negocioId = negocio_actual_id();
        $producto = Producto::whereHas('negocios', fn($q) => $q->where('negocio_id', $negocioId))
            ->with(['categoria', 'subcategoria', 'marca'])
            ->findOrFail($id);
        return view('productos.detalle', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        $subcategorias = Subcategoria::whereHas('categorias', fn($q) => $q->where('categorias.id', $producto->categoria_id))->get();
        $marcas = Marca::all();
        $proveedores = Proveedor::all();
        $cabecera = null;
        $negocios = Negocio::all();
        $productoNegocioIds = $producto->negocios->pluck('id')->toArray();
        $productoCartaIds = $producto->cartaNegocios->pluck('id')->toArray();

        return view('admin.productos.edit', compact('producto', 'categorias', 'subcategorias', 'marcas', 'proveedores', 'cabecera', 'negocios', 'productoNegocioIds', 'productoCartaIds'));
    }

    

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('admin.productos.index')->with('success', 'Producto eliminado');
    }

    public function duplicar(Producto $producto)
    {
        $categorias = Categoria::all();
        $subcategorias = Subcategoria::whereHas('categorias', fn($q) => $q->where('categorias.id', $producto->categoria_id))->get();
        $marcas = Marca::all();
        $proveedores = Proveedor::all();
        $cabecera = null;
        $negocios = Negocio::all();
        $productoNegocioIds = $producto->negocios->pluck('id')->toArray();
        $productoCartaIds = $producto->cartaNegocios->pluck('id')->toArray();

        $origen = clone $producto;
        $origen->titulo = $producto->titulo . ' (copia)';
        $origen->ruta = '';
        $origen->estado = 1;
        $origen->vistas = rand(10, 500);
        $origen->ventas = rand(1, 100);
        $origen->vistasGratis = rand(0, 50);
        $origen->ventasGratis = rand(0, 20);

        return view('admin.productos.create', compact('categorias', 'subcategorias', 'marcas', 'proveedores', 'cabecera', 'negocios', 'productoNegocioIds', 'productoCartaIds', 'origen'));
    }
    ////////////lito store
   public function store(Request $request)
 {
     $request->validate([
         'tipo' => 'required|in:fisico,no_fisico,servicio',
        'titulo' => 'required|string|max:255',
        'titular' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'multimedia' => 'nullable|array',
        'multimedia.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        'detalles' => 'nullable|string',
        'precio' => 'required|numeric|min:0',
        'portada' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        'vistas' => 'nullable|integer|min:0',
        'ventas' => 'nullable|integer|min:0',
        'vistasGratis' => 'nullable|integer|min:0',
        'ventasGratis' => 'nullable|integer|min:0',
        'ofertadoPorCategoria' => 'nullable|numeric|in:0,5,10,15,20,25,50,80',
        'ofertadoPorSubCategoria' => 'nullable|numeric|in:0,5,10,15,20,25,50,80',
        'oferta' => 'nullable|numeric|in:0,5,10,15,20,25,50,80',
        'precioOferta' => 'nullable|numeric|min:0',
        'descuentoOferta' => 'nullable|numeric|min:0',
        'imgOferta' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        'finOferta' => 'nullable|date',
        'peso' => 'nullable|string|max:50',
        'entrega' => 'nullable|string|max:255',
        'costo_envio' => 'nullable|numeric|min:0',
        'categoria_id' => 'required|exists:categorias,id',
        'subcategoria_id' => 'required|exists:subcategorias,id',
        'marca_id' => 'required|exists:marcas,id',
        'proveedor_id' => 'required|exists:proveedores,id',
        'fecha' => 'nullable|date',
        'estado' => 'required|in:1,0',
        'ruta' => 'nullable|string|max:255',
        'palabras_claves' => 'required|string|max:255',
    ]);

    $data = $request->except(['multimedia', 'palabras_claves', 'titulo_seo', 'descripcion_seo']);

    // Valores por defecto
    $data['categoria_id'] = $request->input('categoria_id', 1);
    $data['subcategoria_id'] = $request->input('subcategoria_id', 1);
    $data['marca_id'] = $request->input('marca_id', 1);
    $data['proveedor_id'] = $request->input('proveedor_id', 1);
    $data['ofertadoPorCategoria'] = $request->input('ofertadoPorCategoria', 0);
    $data['ofertadoPorSubCategoria'] = $request->input('ofertadoPorSubCategoria', 0);
    $data['oferta'] = $request->input('oferta', 0);
    $data['vistas'] = $request->input('vistas', rand(10, 500));
    $data['ventas'] = $request->input('ventas', rand(1, 100));
    $data['vistasGratis'] = $request->input('vistasGratis', rand(0, 50));
    $data['ventasGratis'] = $request->input('ventasGratis', rand(0, 20));
    $data['detalles'] = $request->input('detalles', '');
    $data['stock'] = $request->input('stock', 0);

    // Slug
    if (empty($data['ruta'])) {
        $data['ruta'] = Str::slug($data['titulo']);
    }

    // Multimedia
    $imagenes = [];
    if ($request->hasFile('multimedia')) {
        foreach ($request->file('multimedia') as $file) {
            $imagenes[] = $file->store('imagenes/productos', 'public');
        }
    }
    $data['multimedia'] = json_encode($imagenes);

    // Portada
    if ($request->hasFile('portada')) {
        $data['portada'] = $request->file('portada')->store('imagenes/productos', 'public');
    } else {
        $data['portada'] = 'defaults/default-portada.jpg';
    }

    // Imagen de oferta
    if ($request->hasFile('imgOferta')) {
        $data['imgOferta'] = $request->file('imgOferta')->store('imagenes/productos/oferta', 'public');
    }

    // Crear producto
    $producto = Producto::create($data);

    // Negocios
    $producto->negocios()->sync($request->input('negocios', []));
    $producto->cartaNegocios()->sync($request->input('carta_negocios', []));
        DB::table('carta_excluidos')->where('producto_id', $producto->id)->delete();

    // Cabecera
    Cabecera::create([
        'ruta' => $producto->ruta,
        'titulo' => $request->input('titulo_seo', $producto->titulo),
        'descripcion' => $request->input('descripcion_seo', $producto->descripcion),
        'palabras_claves' => $request->input('palabras_claves'),
        'portada' => $producto->portada,
        'fecha' => now(),
    ]);

    return redirect()->route('admin.productos.index')->with('success', 'Producto creado correctamente.');
}

//////lito multiplr
// public function eliminarMultiple(Request $request)
// {
//     $ids = $request->ids;

//     if (!is_array($ids) || count($ids) === 0) {
//         return response()->json(['message' => 'No se recibieron IDs.'], 400);
//     }

//     // BORRA LAS IMÁGENES TAMBIÉN (opcional)
//     $productos = Producto::whereIn('id', $ids)->get();

//     foreach ($productos as $producto) {
//         if ($producto->portada) {
//             Storage::disk('public')->delete($producto->portada);
//         }
//         $producto->delete();
//     }

//     return response()->json([
//         'message' => 'Productos eliminados correctamente.',
//         'eliminados' => $ids
//     ]);
// }
public function eliminarMultiple(Request $request)
{
    $ids = $request->ids;

    if (!is_array($ids) || empty($ids)) {
        return response()->json(['message' => 'No se enviaron IDs.'], 400);
    }

    Producto::whereIn('id', $ids)->delete();

    return response()->json([
        'message' => 'Productos eliminados correctamente.'
    ]);
}


////lito
public function update(Request $request, Producto $producto)
{
    $request->validate([
        'tipo' => 'required|in:fisico,no_fisico,servicio',
        'titulo' => 'required|string|max:255',
        'titular' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'multimedia' => 'nullable|array',
        'multimedia.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        'imagenes_actuales' => 'nullable|json',
        'detalles' => 'nullable|string',
        'precio' => 'required|numeric|min:0',
        'portada' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        'vistas' => 'nullable|integer|min:0',
        'ventas' => 'nullable|integer|min:0',
        'vistasGratis' => 'nullable|integer|min:0',
        'ventasGratis' => 'nullable|integer|min:0',
        'ofertadoPorCategoria' => 'nullable|numeric|in:0,5,10,15,20,25,50,80',
        'ofertadoPorSubCategoria' => 'nullable|numeric|in:0,5,10,15,20,25,50,80',
        'oferta' => 'nullable|numeric|in:0,5,10,15,20,25,50,80',
        'precioOferta' => 'nullable|numeric|min:0',
        'descuentoOferta' => 'nullable|numeric|min:0',
        'imgOferta' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        'finOferta' => 'nullable|date',
        'peso' => 'nullable|string|max:50',
        'entrega' => 'nullable|string|max:255',
        'costo_envio' => 'nullable|numeric|min:0',
        'categoria_id' => 'required|exists:categorias,id',
        'subcategoria_id' => 'required|exists:subcategorias,id',
        'marca_id' => 'required|exists:marcas,id',
        'proveedor_id' => 'required|exists:proveedores,id',
        'fecha' => 'nullable|date',
        'estado' => 'required|in:1,0',
        'ruta' => 'nullable|string|max:255',
        'palabras_claves' => 'required|string|max:255',
    ]);

    $data = $request->except(['multimedia', 'imagenes_actuales', 'palabras_claves', 'titulo_seo', 'descripcion_seo']);

    $data['ofertadoPorCategoria'] = $request->input('ofertadoPorCategoria', 0);
    $data['ofertadoPorSubCategoria'] = $request->input('ofertadoPorSubCategoria', 0);
    $data['oferta'] = $request->input('oferta', 0);
    $data['detalles'] = $request->input('detalles', '');

    if (empty($data['ruta'])) {
        $data['ruta'] = Str::slug($data['titulo']);
    }

    // MULTIMEDIA
    $imagenesActuales = json_decode($request->input('imagenes_actuales'), true) ?? [];
    $imagenesOriginales = json_decode($producto->multimedia, true) ?? [];

    // Detectar eliminadas
    $imagenesEliminadas = array_diff($imagenesOriginales, $imagenesActuales);
    foreach ($imagenesEliminadas as $img) {
        if (\Storage::disk('public')->exists($img)) {
            \Storage::disk('public')->delete($img);
        }
    }

    // Subir nuevas
    if ($request->hasFile('multimedia')) {
        foreach ($request->file('multimedia') as $file) {
            $imagenesActuales[] = $file->store('imagenes/productos', 'public');
        }
    }

    $data['multimedia'] = json_encode($imagenesActuales);

    // PORTADA
    if ($request->input('remove_portada') == "1") {
        if ($producto->portada && \Storage::disk('public')->exists($producto->portada)) {
            \Storage::disk('public')->delete($producto->portada);
        }
        $data['portada'] = null;
    } elseif ($request->hasFile('portada')) {
        if ($producto->portada && \Storage::disk('public')->exists($producto->portada)) {
            \Storage::disk('public')->delete($producto->portada);
        }
        $data['portada'] = $request->file('portada')->store('imagenes/productos', 'public');
    } else {
        $data['portada'] = $producto->portada;
    }

    // IMG OFERTA
    if ($request->hasFile('imgOferta')) {
        if ($producto->imgOferta && \Storage::disk('public')->exists($producto->imgOferta)) {
            \Storage::disk('public')->delete($producto->imgOferta);
        }
        $data['imgOferta'] = $request->file('imgOferta')->store('imagenes/productos/oferta', 'public');
    }

    // ACTUALIZAR PRODUCTO
    $producto->update($data);

    // Negocios
    $producto->negocios()->sync($request->input('negocios', []));
    $producto->cartaNegocios()->sync($request->input('carta_negocios', []));
        DB::table('carta_excluidos')->where('producto_id', $producto->id)->delete();

    // CABECERA
    Cabecera::updateOrCreate(
        ['ruta' => $producto->ruta],
        [
            'titulo' => $request->input('titulo_seo', $producto->titulo),
            'descripcion' => $request->input('descripcion_seo', $producto->descripcion),
            'palabras_claves' => $request->input('palabras_claves'),
            'portada' => $producto->portada,
            'fecha' => now(),
        ]
    );

    return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado correctamente.');
}
    public function quickUpdate(Request $request, Producto $producto)
    {
        $request->validate([
            'titulo'          => 'required|string|max:255',
            'titular'         => 'nullable|string|max:255',
            'precio'          => 'required|numeric|min:0',
            'categoria_id'    => 'required|exists:categorias,id',
            'subcategoria_id' => 'required|exists:subcategorias,id',
            'portada'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'ruta'            => 'nullable|string|max:255',
            'palabras_claves' => 'nullable|string|max:255',
            'descripcion'     => 'nullable|string',
            'detalles'        => 'nullable|string',
            'stock'           => 'nullable|integer|min:0',
            'entrega'         => 'nullable|numeric|min:0',
            'costo_envio'     => 'nullable|numeric|min:0',
            'multimedia'      => 'nullable|array',
            'multimedia.*'    => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'imagenes_actuales' => 'nullable|json',
        ]);

        $data = $request->only(['titulo', 'titular', 'precio', 'categoria_id', 'subcategoria_id', 'ruta', 'palabras_claves', 'descripcion']);
        $data['detalles'] = $request->input('detalles', '');
        $data['stock'] = $request->filled('stock') ? $request->integer('stock') : 0;
        $data['entrega'] = $request->filled('entrega') ? $request->input('entrega') : 2;
        $data['costo_envio'] = $request->filled('costo_envio') ? $request->input('costo_envio') : null;

        if ($request->hasFile('portada')) {
            if ($producto->portada && \Storage::disk('public')->exists($producto->portada)) {
                \Storage::disk('public')->delete($producto->portada);
            }
            $data['portada'] = $request->file('portada')->store('imagenes/productos', 'public');
        }

        // manejo de multimedia (imágenes del producto)
        $imagenesActuales = json_decode($request->input('imagenes_actuales'), true) ?? [];
        $imagenesOriginales = json_decode($producto->multimedia, true) ?? [];
        $imagenesEliminadas = array_diff($imagenesOriginales, $imagenesActuales);
        foreach ($imagenesEliminadas as $img) {
            if (\Storage::disk('public')->exists($img)) {
                \Storage::disk('public')->delete($img);
            }
        }
        if ($request->hasFile('multimedia')) {
            foreach ($request->file('multimedia') as $file) {
                $imagenesActuales[] = $file->store('imagenes/productos', 'public');
            }
        }
        $data['multimedia'] = json_encode($imagenesActuales);

        $producto->update($data);

        $portadaUrl = $producto->portada ? asset('storage/' . $producto->portada) : '';

        return response()->json([
            'success' => true,
            'message' => 'Producto actualizado.',
            'portada_url' => $portadaUrl,
            'producto' => $producto,
        ]);
    }

    public function inlineUpdate(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $request->validate([
            'field' => 'required|in:precio,costo_envio,entrega,estado',
            'value' => 'nullable|numeric',
        ]);

        $field = $request->input('field');
        $value = $request->input('value');

        if ($value === '' || $value === null) {
            $value = match($field) {
                'precio' => 0,
                'costo_envio' => null,
                'entrega' => null,
                'estado' => 0,
                default => $value,
            };
        }

        $producto->$field = $value;
        $producto->save();

        $display = match($field) {
            'precio' => 'S/. ' . number_format($producto->precio, 2),
            'costo_envio' => $producto->costo_envio !== null ? 'S/. ' . number_format($producto->costo_envio, 2) : '—',
            'entrega' => $producto->entrega !== null ? $producto->entrega . ' días' : '—',
            'estado' => '<span class="badge bg-' . ($producto->estado ? 'success' : 'secondary') . '">' . ($producto->estado ? 'Activo' : 'Inactivo') . '</span>',
            default => $producto->$field,
        };

        return response()->json([
            'success' => true,
            'value' => $producto->$field,
            'display' => $display,
        ]);
    }

    public function bulkUpdatePrecio(Request $request)
    {
        $precios = $request->input('precios');
        if (!is_array($precios)) {
            return response()->json(['success' => false, 'message' => 'Formato inválido.'], 422);
        }

        $ids = array_keys($precios);
        $errores = 0;
        foreach ($precios as $id => $precio) {
            if (!is_numeric($precio) || $precio < 0) continue;
            Producto::where('id', $id)->update(['precio' => $precio]);
            $errores++;
        }

        return response()->json([
            'success' => true,
            'message' => "Precio actualizado en {$errores} producto(s).",
        ]);
    }

    public function bulkUpdateCostoEnvio(Request $request)
    {
        $costos = $request->input('costos_envio');
        if (!is_array($costos)) {
            return response()->json(['success' => false, 'message' => 'Formato inválido.'], 422);
        }

        $errores = 0;
        foreach ($costos as $id => $costo) {
            $valor = ($costo === '' || $costo === null) ? null : $costo;
            if ($valor !== null && (!is_numeric($valor) || $valor < 0)) continue;
            Producto::where('id', $id)->update(['costo_envio' => $valor]);
            $errores++;
        }

        return response()->json([
            'success' => true,
            'message' => "Costo de envío actualizado en {$errores} producto(s).",
        ]);
    }

    public function bulkUpdateEntrega(Request $request)
    {
        $entregas = $request->input('entregas');
        if (!is_array($entregas)) {
            return response()->json(['success' => false, 'message' => 'Formato inválido.'], 422);
        }

        $errores = 0;
        foreach ($entregas as $id => $entrega) {
            if ($entrega === '' || $entrega === null) continue;
            if (!is_numeric($entrega) || $entrega < 0) continue;
            Producto::where('id', $id)->update(['entrega' => $entrega]);
            $errores++;
        }

        return response()->json([
            'success' => true,
            'message' => "Entrega actualizada en {$errores} producto(s).",
        ]);
    }

    public function bulkUpdateMarca(Request $request)
    {
        $marcas = $request->input('marcas');
        if (!is_array($marcas)) {
            return response()->json(['success' => false, 'message' => 'Formato inválido.'], 422);
        }

        $actualizados = 0;
        foreach ($marcas as $id => $marcaId) {
            if ($marcaId === '' || $marcaId === null || $marcaId === '0') continue;
            if (!is_numeric($marcaId)) continue;
            Producto::where('id', $id)->update(['marca_id' => $marcaId]);
            $actualizados++;
        }

        return response()->json([
            'success' => true,
            'message' => "Marca actualizada en {$actualizados} producto(s).",
        ]);
    }

    public function bulkUpdateImagen(Request $request)
    {
        $portadas = $request->file('portadas');
        $galerias = $request->file('galerias');

        if ((!is_array($portadas) || !count($portadas)) && (!is_array($galerias) || !count($galerias))) {
            return response()->json(['success' => false, 'message' => 'No se subió ninguna imagen.'], 422);
        }

        $actualizados = 0;
        $errores = [];

        // IDs involucrados (de portadas y/o galerías)
        $ids = array_unique(array_merge(
            is_array($portadas) ? array_keys($portadas) : [],
            is_array($galerias) ? array_keys($galerias) : []
        ));

        foreach ($ids as $id) {
            if (!is_numeric($id) || (int) $id === 0) continue;

            $producto = Producto::find($id);
            if (!$producto) {
                $errores[] = "El producto #{$id} no existe.";
                continue;
            }

            $cambios = [];

            // Portada
            if (isset($portadas[$id]) && $portadas[$id] && $portadas[$id]->isValid()) {
                $ruta = $portadas[$id]->store('imagenes/productos', 'public');

                if ($producto->portada && $producto->portada !== 'defaults/default-portada.jpg'
                    && Storage::disk('public')->exists($producto->portada)) {
                    Storage::disk('public')->delete($producto->portada);
                }

                $cambios['portada'] = $ruta;
            }

            // Galería multimedia (se agregan a las existentes, sin borrar)
            if (isset($galerias[$id])) {
                $imagenes = json_decode($producto->multimedia, true) ?? [];
                $filas = is_array($galerias[$id]) ? $galerias[$id] : [$galerias[$id]];
                foreach ($filas as $file) {
                    if ($file && $file->isValid()) {
                        $imagenes[] = $file->store('imagenes/productos', 'public');
                    }
                }
                if (count($imagenes)) {
                    $cambios['multimedia'] = json_encode(array_values($imagenes));
                }
            }

            if ($cambios) {
                $producto->update($cambios);
                $actualizados++;
            }
        }

        $mensaje = "Imágenes actualizadas en {$actualizados} producto(s).";
        if ($errores) {
            $mensaje .= ' ' . implode(' ', $errores);
        }

        return response()->json([
            'success' => true,
            'message' => $mensaje,
        ]);
    }

    public function bulkUpdateCarta(Request $request)
    {
        $ids = $request->input('ids');
        if (!is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'Formato inválido.'], 422);
        }
        $actualizados = 0;
        foreach ($ids as $id) {
            if (!is_numeric($id) || (int) $id === 0) continue;
            $producto = Producto::find($id);
            if (!$producto) continue;

            // Marcar en la carta de cada negocio al que ya pertenece el producto
            foreach ($producto->negocios()->pluck('negocio_id') as $negocioId) {
                $producto->cartaNegocios()->syncWithoutDetaching([$negocioId]);
            }

            // Re-activar: anular cualquier exclusión previa de la carta
            DB::table('carta_excluidos')->where('producto_id', $producto->id)->delete();
            $actualizados++;
        }

        return response()->json([
            'success' => true,
            'message' => "Productos agregados a la Carta / Catálogo: {$actualizados}.",
        ]);
    }

    public function bulkRemoveCarta(Request $request)
    {
        $ids = $request->input('ids');
        if (!is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'Formato inválido.'], 422);
        }

        $actualizados = 0;
        foreach ($ids as $id) {
            if (!is_numeric($id) || (int) $id === 0) continue;
            $producto = Producto::find($id);
            if (!$producto) continue;

            // Quitar de la carta de todos los negocios
            $producto->cartaNegocios()->detach();

            // Excluir: anula también la inclusión automática por subcategoría
            foreach ($producto->negocios()->pluck('negocio_id') as $negocioId) {
                DB::table('carta_excluidos')->updateOrInsert(
                    ['negocio_id' => $negocioId, 'producto_id' => $producto->id],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
            $actualizados++;
        }

        return response()->json([
            'success' => true,
            'message' => "Productos quitados de la Carta / Catálogo: {$actualizados}.",
        ]);
    }

    public function cartaLista()
    {
        $ctrl = app(\App\Http\Controllers\ProductosController::class);

        $map = [];
        foreach (\App\Models\Negocio::orderBy('id')->get(['id', 'nombre']) as $negocio) {
            foreach ($ctrl->productosEnCarta((int) $negocio->id) as $p) {
                if (!isset($map[$p->id])) {
                    $map[$p->id] = ['id' => $p->id, 'titulo' => $p->titulo, 'negocios' => []];
                }
                $map[$p->id]['negocios'][] = $negocio->nombre;
            }
        }

        return response()->json([
            'success' => true,
            'productos' => array_values($map),
        ]);
    }
}
