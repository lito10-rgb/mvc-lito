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
        $subcategorias = Subcategoria::all();
        $marcas = Marca::all();
        $proveedores = Proveedor::all();
         $cabecera = null; // para evitar error en la vista

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
        // 'multimedia' => 'nullable|string',
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
        // 'palabras_claves' => 'nullable|string|max:255',
    ]);
   // dd($request->all());
      // $data = $request->all();
      $data = $request->except('palabras_claves');
    // $data = $request->except('multimedia');
    // $data = $request->except('palabras_claves'); // base
    // Si no se seleccionó nada, se guarda 1 y 0 por defecto
    $data['categoria_id'] = $request->input('categoria_id', 1);
    $data['subcategoria_id'] = $request->input('subcategoria_id', 1);
    $data['marca_id'] = $request->input('marca_id', 1);
    $data['proveedor_id'] = $request->input('proveedor_id', 1);
    // esto de 
    $data['ofertadoPorCategoria'] = $request->input('ofertadoPorCategoria', 0);
   $data['ofertadoPorSubCategoria'] = $request->input('ofertadoPorSubCategoria', 0);
    $data['oferta'] = $request->input('oferta', 0);
    // $multimedia = [];
///////////lito
    // if ($request->hasFile('multimedia')) {
    // foreach ($request->file('multimedia') as $archivo) {
    //     $ruta = $archivo->store('imagenes/productos', 'public');
    //     $multimedia[] = $ruta;
    // }
    // $data['multimedia'] = implode(',', $multimedia); // Guardar como texto
    // } else {
    //     $data['multimedia'] = ''; // Si no hay, aseguramos que sea string vacío
    // }
    if ($request->hasFile('multimedia')) {
    $multimedia = [];

    foreach ($request->file('multimedia') as $archivo) {
        $ruta = $archivo->store('imagenes/productos', 'public');
        $multimedia[] = $ruta;
    }

        $data['multimedia'] = json_encode($multimedia); // ✅ Guardar como JSON real
    } else {
        $data['multimedia'] = json_encode([]); // ✅ Array vacío como JSON
    }

///////
    // Generar ruta amigable si no viene del formulario (basada en el título)
    if (empty($data['ruta'])) {
        $data['ruta'] = \Str::slug($data['titulo']);
    }

    // Guardar portada

    // Guardar portada
if ($request->hasFile('portada')) {
    $data['portada'] = $request->file('portada')->store('imagenes/productos', 'public');
} else {
    $data['portada'] = 'defaults/default-portada.jpg'; // ruta relativa en storage/app/public
}




    // Guardar imgOferta
    if ($request->hasFile('imgOferta')) {
        $data['imgOferta'] = $request->file('imgOferta')->store('imagenes/productos/oferta', 'public');
    }

    // Producto::create($data);
    // $producto = Producto::create($request->all());
    // dd($request->all());
    // dd($request->input('palabras_claves'));
    // $palabras = $request->input('palabras_claves');
     $palabras = $request->input('palabras_claves') ?? '';
// dd($palabras);// aqui captura  ya el valor
    // dd($data['portada']);
 // $producto = Producto::create($request->except('palabras_claves'));
 $producto = Producto::create($data);
// dd([
//     'palabras_claves' => $palabras,
//     'producto' => $producto->toArray(),
// ]);
    // dd($palabras);// aqui no captura  ya el valor
    Cabecera::create([
        'ruta' => $producto->ruta,
        'titulo' => $producto->titulo,
        'descripcion' => $producto->descripcion,
        'palabras_claves' => $palabras,// aaqui no se  q pasa  dice que no hay valor
        'portada' => $producto->portada,
        'fecha' => now(),
    ]);
    //     // return response()->json($producto, 201);
    return redirect()->route('admin.productos.index')->with('success', 'Producto creado correctamente.');
}

    /////////////////uupdate
public function update(Request $request, Producto $producto)
{
    $request->validate([
        'tipo' => 'required|in:fisico,servicio',
        'titulo' => 'required|string|max:255',
        'titular' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        // 'multimedia' => 'nullable|string',
        'multimedia' => 'nullable|array',
    'multimedia.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ////lito
        'imagenes_actuales' => 'nullable|json',
        'detalles' => 'nullable|string',
        'precio' => 'required|numeric|min:0',
        'portada' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'vistas' => 'nullable|integer|min:0',
        'ventas' => 'nullable|integer|min:0',
        'vistasGratis' => 'nullable|integer|min:0',
        'ventasGratis' => 'nullable|integer|min:0',
        'ofertadoPorCategoria' => 'nullable|numeric|in:5,10,15,20,25,50,80',
        'ofertadoPorSubCategoria' => 'nullable|numeric|in:5,10,15,20,25,50,80',
        'oferta' => 'nullable|numeric|in:5,10,15,20,25,50,80',
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

    $data = $request->all();

    if (empty($data['ruta'])) {
        $data['ruta'] = \Str::slug($data['titulo']);
    }
    //multimedia de lito q elimare
    // 1. Obtener imágenes actuales que quedaron después de eliminar
    $imagenesActuales = json_decode($request->input('imagenes_actuales'), true) ?? [];

    // 2. Obtener imágenes originales del producto
    $imagenesOriginales = json_decode($producto->multimedia, true) ?? [];

    // 3. Detectar las que fueron eliminadas
    $imagenesEliminadas = array_diff($imagenesOriginales, $imagenesActuales);
     // 4. Eliminar físicamente las imágenes eliminadas
    foreach ($imagenesEliminadas as $img) {
        if (\Storage::disk('public')->exists($img)) {
            \Storage::disk('public')->delete($img);
        }
    }
    // 5. Si sube nuevas imágenes, guardarlas
    if ($request->hasFile('multimedia')) {
        foreach ($request->file('multimedia') as $file) {
            $path = $file->store('imagenes/productos', 'public');
            $imagenesActuales[] = $path;
        }
    }
    // 6. Actualizar el campo multimedia en la BD
    $producto->multimedia = json_encode($imagenesActuales);
    // 7. Guardar el resto de campos
//     $producto->fill($request->except(['multimedia', 'imagenes_actuales']));
//     $producto->save();

//     return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado correctamente.');
// }
    /////////////////
    // Si sube nueva portada, eliminar la anterior y guardar la nueva
    if ($request->hasFile('portada')) {
        if ($producto->portada && \Storage::disk('public')->exists($producto->portada)) {
            \Storage::disk('public')->delete($producto->portada);
        }
        $data['portada'] = $request->file('portada')->store('imagenes/productos', 'public');
    }

    // Si sube nueva imgOferta, eliminar la anterior y guardar la nueva
    if ($request->hasFile('imgOferta')) {
        if ($producto->imgOferta && \Storage::disk('public')->exists($producto->imgOferta)) {
            \Storage::disk('public')->delete($producto->imgOferta);
        }
        $data['imgOferta'] = $request->file('imgOferta')->store('imagenes/productos/oferta', 'public');
    }

    // $producto->update($data);
    $palabras = $request->input('palabras_claves');
    $producto->update($request->except('palabras_claves'));

// $producto->update($request->all());
        // Actualizar o crear cabecera
    Cabecera::updateOrCreate(
        ['ruta' => $producto->ruta],
        [
            'titulo' => $producto->titulo,
            'descripcion' => $producto->descripcion,
            'palabras_claves' =>$palabras,
            'portada' => $producto->portada,
            'fecha' => now(),
        ]
    );
        // return response()->json($producto);
    return redirect()->route('admin.productos.index')->with('success', 'Producto Actualizado correctamente.');
    }
   // return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado correctamente.');
}
////////////////////////update
// }
