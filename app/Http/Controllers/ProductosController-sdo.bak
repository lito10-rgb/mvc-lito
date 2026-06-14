<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Cabecera;
use Illuminate\Http\Request;

class ProductosController extends Controller
{
    // Listar todos los productos con relaciones
    // public function index()
    // {
    //     $productos = Producto::with(['categoria', 'subcategoria', 'marca', 'proveedor'])->get();
    //     return response()->json($productos);
    // }
// public function index()
// {
//     $productos = Producto::with(['categoria', 'subcategoria', 'marca', 'proveedor'])->paginate(10);
//     return view('admin.productos.index', compact('productos'));
// }
    public function index()
{
    $productos = Producto::with(['categoria', 'subcategoria', 'marca', 'proveedor'])->paginate(10);
    return view('admin.productos.index', compact('productos'));
}
    // Crear un nuevo producto
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
            'imagen' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            'subcategoria_id' => 'nullable|exists:subcategorias,id',
            'marca_id' => 'nullable|exists:marcas,id',
            'proveedor_id' => 'nullable|exists:proveedores,id',
             'palabrasClaves' => 'nullable|string',
        ]);

        $producto = Producto::create($request->all());
        // Crear cabecera
    \App\Models\Cabecera::create([
        'ruta' => $producto->ruta,
        'titulo' => $producto->titulo,
        'descripcion' => $producto->descripcion,
        'palabrasClaves' => $request->input('palabrasClaves'),
        'portada' => $producto->portada,
        'fecha' => now(),
    ]);
        return response()->json($producto, 201);
    }

    // Mostrar un solo producto
    public function show($id)
    {
        $producto = Producto::with(['categoria', 'subcategoria', 'marca', 'proveedor'])->findOrFail($id);
        return response()->json($producto);
    }

    // Actualizar un producto
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'nullable|numeric',
            'imagen' => 'nullable|string',
            'categoria_id' => 'nullable|exists:categorias,id',
            'subcategoria_id' => 'nullable|exists:subcategorias,id',
            'marca_id' => 'nullable|exists:marcas,id',
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'palabrasClaves' => 'nullable|string',
        ]);

        $producto->update($request->all());
        
        return response()->json($producto);
    }

    // Eliminar un producto
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return response()->json(['mensaje' => 'Producto eliminado correctamente']);
    }
    ////busqueda
    // public function buscar(Request $request)
    // {
    //     $query = Producto::query();

    //     if ($request->filled('tipo')) {
    //         $query->where('tipo', $request->tipo);
    //     }

    //     if ($request->filled('marca')) {
    //         $query->where('marca', $request->marca);
    //     }

    //     if ($request->filled('capacidad')) {
    //         $query->where('capacidad', 'like', '%' . $request->capacidad . '%');
    //     }

    //     if ($request->filled('precio_min')) {
    //         $query->where('precio', '>=', $request->precio_min);
    //     }

    //     if ($request->filled('precio_max')) {
    //         $query->where('precio', '<=', $request->precio_max);
    //     }

    //     $productos = $query->paginate(8);

    //     return view('productos.buscar', compact('productos'));
    // }
    public function buscar(Request $request)
{
    $query = Producto::with(['categoria', 'subcategoria', 'marca', 'proveedor']);

    // Filtro por nombre de categoría (relación con tabla categorias)
    if ($request->filled('categoria')) {
        $query->whereHas('categoria', function ($q) use ($request) {
            $q->where('nombre', $request->categoria);
        });
    }

    // Aquí puedes seguir agregando otros filtros
    if ($request->filled('marca')) {
        $query->whereHas('marca', function ($q) use ($request) {
            $q->where('nombre', $request->marca);
        });
    }

    if ($request->filled('capacidad')) {
        $query->where('capacidad', 'like', '%' . $request->capacidad . '%');
    }

    if ($request->filled('precio_min')) {
        $query->where('precio', '>=', $request->precio_min);
    }

    if ($request->filled('precio_max')) {
        $query->where('precio', '<=', $request->precio_max);
    }

    $productos = $query->paginate(4);

    return view('productos.buscar', compact('productos'));
}
// lito
// public function mostrarProducto($ruta)
// {
//     $producto = Producto::where('ruta', $ruta)->firstOrFail();
//     $cabecera = Cabecera::where('ruta', $ruta)->first();

//     return view('productos.detalle', compact('producto', 'cabecera'));
// }
// lito
public function mostrarProducto($ruta)
{
    $producto = Producto::where('ruta', $ruta)
        ->with(['categoria', 'subcategoria', 'marca', 'proveedor'])
        ->firstOrFail();

    $cabecera = Cabecera::where('ruta', $ruta)->first();

    // Obtener relacionados por categoría y subcategoría
    $relacionados = Producto::where('categoria_id', $producto->categoria_id)
        ->where('subcategoria_id', $producto->subcategoria_id)
        ->where('id', '!=', $producto->id)
        ->take(6)
        ->get();

    return view('productos.detalle', compact('producto', 'cabecera', 'relacionados'));
}

// public function show($id)
// {
//     $producto = Producto::with(['categoria', 'subcategoria', 'marca', 'proveedor'])
//         ->findOrFail($id);

//     // Buscar productos relacionados por categoría y subcategoría
//     $relacionados = Producto::where('id', '!=', $producto->id)
//         ->where(function($query) use ($producto) {
//             $query->where('categoria_id', $producto->categoria_id)
//                   ->orWhere('subcategoria_id', $producto->subcategoria_id);
//         })
//         ->take(6) // máximo 6 productos relacionados
//         ->get();

//     return view('detalle', compact('producto', 'relacionados'));
// }


}
