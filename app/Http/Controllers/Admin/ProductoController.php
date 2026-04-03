<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Marca;
use App\Models\Proveedor;
use App\Models\Cabecera;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
class ProductoController extends Controller
{
    

    public function index()
{
    $productos = Producto::with(['categoria', 'subcategoria', 'marca', 'proveedor'])
                         ->paginate(5); // Puedes ajustar el número
    return view('admin.productos.index', compact('productos'));
}

    public function create()
    {
        $categorias = Categoria::all();
        $subcategorias = Subcategoria::all();
        $marcas = Marca::all();
        $proveedores = Proveedor::all();
         $cabecera = null; // para evitar error en la vista

        return view('admin.productos.create', compact('categorias', 'subcategorias', 'marcas', 'proveedores','cabecera'));
    }

    

    public function show(Producto $producto)
    {
        return view('admin.productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        //$subcategorias = Subcategoria::all();
        $subcategorias = Subcategoria::where('id_categoria', $producto->categoria_id)->get();
        $marcas = Marca::all();
        $proveedores = Proveedor::all();
         $cabecera = null; // para evitar error en la vista
          // dd($subcategorias->pluck('id')->toArray());
             //dd($producto->subcategoria_id);

        return view('admin.productos.edit', compact('producto', 'categorias', 'subcategorias', 'marcas', 'proveedores','cabecera'));
    }

    

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('admin.productos.index')->with('success', 'Producto eliminado');
    }
    ////////////lito store
   public function store(Request $request)
{
    $request->validate([
        'tipo' => 'required|in:fisico,servicio',
        'titulo' => 'required|string|max:255',
        'titular' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'multimedia' => 'nullable|array',
        'multimedia.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'detalles' => 'nullable|string',
        'precio' => 'required|numeric|min:0',
        'portada' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'vistas' => 'nullable|integer|min:0',
        'ventas' => 'nullable|integer|min:0',
        'vistasGratis' => 'nullable|integer|min:0',
        'ventasGratis' => 'nullable|integer|min:0',
        'ofertadoPorCategoria' => 'nullable|numeric|in:0,5,10,15,20,25,50,80',
        'ofertadoPorSubCategoria' => 'nullable|numeric|in:0,5,10,15,20,25,50,80',
        'oferta' => 'nullable|numeric|in:0,5,10,15,20,25,50,80',
        'precioOferta' => 'nullable|numeric|min:0',
        'descuentoOferta' => 'nullable|numeric|min:0|max:100',
        'imgOferta' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'finOferta' => 'nullable|date',
        'peso' => 'nullable|string|max:50',
        'entrega' => 'nullable|string|max:255',
        'categoria_id' => 'required|exists:categorias,id',
        'subcategoria_id' => 'required|exists:subcategorias,id',
        'marca_id' => 'required|exists:marcas,id',
        'proveedor_id' => 'required|exists:proveedores,id',
        'fecha' => 'nullable|date',
        'estado' => 'required|in:1,0',
        'ruta' => 'nullable|string|max:255',
        'palabras_claves' => 'required|string|max:255',
    ]);

    $data = $request->except(['multimedia', 'palabras_claves']);

    // Valores por defecto
    $data['categoria_id'] = $request->input('categoria_id', 1);
    $data['subcategoria_id'] = $request->input('subcategoria_id', 1);
    $data['marca_id'] = $request->input('marca_id', 1);
    $data['proveedor_id'] = $request->input('proveedor_id', 1);
    $data['ofertadoPorCategoria'] = $request->input('ofertadoPorCategoria', 0);
    $data['ofertadoPorSubCategoria'] = $request->input('ofertadoPorSubCategoria', 0);
    $data['oferta'] = $request->input('oferta', 0);

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

    // Cabecera
    Cabecera::create([
        'ruta' => $producto->ruta,
        'titulo' => $producto->titulo,
        'descripcion' => $producto->descripcion,
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
        'tipo' => 'required|in:fisico,servicio',
        'titulo' => 'required|string|max:255',
        'titular' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'multimedia' => 'nullable|array',
        'multimedia.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        'imagenes_actuales' => 'nullable|json',
        'detalles' => 'nullable|string',
        'precio' => 'required|numeric|min:0',
        'portada' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'vistas' => 'nullable|integer|min:0',
        'ventas' => 'nullable|integer|min:0',
        'vistasGratis' => 'nullable|integer|min:0',
        'ventasGratis' => 'nullable|integer|min:0',
        'ofertadoPorCategoria' => 'nullable|numeric|in:0,5,10,15,20,25,50,80',
        'ofertadoPorSubCategoria' => 'nullable|numeric|in:0,5,10,15,20,25,50,80',
        'oferta' => 'nullable|numeric|in:0,5,10,15,20,25,50,80',
        'precioOferta' => 'nullable|numeric|min:0',
        'descuentoOferta' => 'nullable|numeric|min:0|max:100',
        'imgOferta' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'finOferta' => 'nullable|date',
        'peso' => 'nullable|string|max:50',
        'entrega' => 'nullable|string|max:255',
        'categoria_id' => 'required|exists:categorias,id',
        'subcategoria_id' => 'required|exists:subcategorias,id',
        'marca_id' => 'required|exists:marcas,id',
        'proveedor_id' => 'required|exists:proveedores,id',
        'fecha' => 'nullable|date',
        'estado' => 'required|in:1,0',
        'ruta' => 'nullable|string|max:255',
        'palabras_claves' => 'required|string|max:255',
    ]);

    $data = $request->except(['multimedia', 'imagenes_actuales', 'palabras_claves']);

    $data['ofertadoPorCategoria'] = $request->input('ofertadoPorCategoria', 0);
    $data['ofertadoPorSubCategoria'] = $request->input('ofertadoPorSubCategoria', 0);
    $data['oferta'] = $request->input('oferta', 0);

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

    // CABECERA
    Cabecera::updateOrCreate(
        ['ruta' => $producto->ruta],
        [
            'titulo' => $producto->titulo,
            'descripcion' => $producto->descripcion,
            'palabras_claves' => $request->input('palabras_claves'),
            'portada' => $producto->portada,
            'fecha' => now(),
        ]
    );

    return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado correctamente.');
}
}
////////////////////////update
// }
