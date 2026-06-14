<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Marca;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductoController extends Controller
{
    // public function index()
    // {
    //     $productos = Producto::with(['categoria', 'subcategoria', 'marca', 'proveedor'])->get();
    //     return view('admin.productos.index', compact('productos'));
    // }

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

    // public function store(Request $request)
    // {
    //     $producto = Producto::create($request->all());
    //     return redirect()->route('admin.productos.index')->with('success', 'Producto creado correctamente');
    // }

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

    // public function update(Request $request, Producto $producto)
    // {
    //     $producto->update($request->all());
    //     return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado');
    // }

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
        'multimedia' => 'nullable|string',
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

    // Generar ruta amigable si no viene del formulario (basada en el título)
    if (empty($data['ruta'])) {
        $data['ruta'] = \Str::slug($data['titulo']);
    }

    // Guardar portada
    if ($request->hasFile('portada')) {
        $data['portada'] = $request->file('portada')->store('imagenes/productos', 'public');
    } else {
    $portada = 'defaults/default-portada.jpg'; // ruta relativa en storage/app/public
    }
    // Guardar imgOferta
    if ($request->hasFile('imgOferta')) {
        $data['imgOferta'] = $request->file('imgOferta')->store('imagenes/productos/oferta', 'public');
    }

    // Producto::create($data);
    // $producto = Producto::create($request->all());
    $producto = Producto::create($request->except('palabras_claves'));

    // return redirect()->route('admin.productos.index')->with('success', 'Producto creado correctamente.');
    \App\Models\Cabecera::create([
        'ruta' => $producto->ruta,
        'titulo' => $producto->titulo,
        'descripcion' => $producto->descripcion,
        'palabras_claves' => $request->input('palabras_claves'),
        'portada' => $producto->portada,
        'fecha' => now(),
    ]);
        return response()->json($producto, 201);
}

    /////////////////uupdate
public function update(Request $request, Producto $producto)
{
    $request->validate([
        'tipo' => 'required|in:fisico,servicio',
        'titulo' => 'required|string|max:255',
        'titular' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'multimedia' => 'nullable|string',
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
    $producto->update($request->except('palabras_claves'));

// $producto->update($request->all());
        // Actualizar o crear cabecera
    \App\Models\Cabecera::updateOrCreate(
        ['ruta' => $producto->ruta],
        [
            'titulo' => $producto->titulo,
            'descripcion' => $producto->descripcion,
            'palabras_claves' => $request->input('palabras_claves'),
            'portada' => $producto->portada,
            'fecha' => now(),
        ]
    );
        return response()->json($producto);
    }
   // return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado correctamente.');
}
////////////////////////update
// }
