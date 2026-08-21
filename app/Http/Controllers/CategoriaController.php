<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Marca;
use Illuminate\Http\Request;
class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //      $categorias = Categoria::all();
    //     return view('categorias.index', compact('categorias'));
    // }
public function index()
{
    $negocioId = negocio_actual_id();
    $categorias = Categoria::whereHas('negocios', fn($q) => $q->where('negocio_id', $negocioId))
        ->with(['subcategorias' => fn($q) => $q->whereHas('negocios', fn($q2) => $q2->where('negocio_id', $negocioId))])
        ->get();
    return view('categoria.index', compact('categorias'));
}
public function show($id)
{
    $negocioId = negocio_actual_id();
    $categoria = Categoria::whereHas('negocios', fn($q) => $q->where('negocio_id', $negocioId))
        ->with(['subcategorias' => fn($q) => $q->whereHas('negocios', fn($q2) => $q2->where('negocio_id', $negocioId))])
        ->findOrFail($id);
    
    // Cargar productos con paginación
    $productos = \App\Models\Producto::where('categoria_id', $id)
        ->whereHas('negocios', fn($q) => $q->where('negocio_id', $negocioId))
        ->paginate(12);
    
    $categorias = Categoria::whereHas('negocios', fn($q) => $q->where('negocio_id', $negocioId))->get();
    $marcas = Marca::all();
    $subcategorias = Subcategoria::all();
    return view('categoria.show', compact('categoria', 'categorias', 'subcategorias', 'marcas', 'productos'));
}

public function showByRuta($ruta)
{
    $negocioId = negocio_actual_id();
    $categoria = Categoria::where('ruta', $ruta)
        ->whereHas('negocios', fn($q) => $q->where('negocio_id', $negocioId))
        ->with(['subcategorias' => fn($q) => $q->whereHas('negocios', fn($q2) => $q2->where('negocio_id', $negocioId))])
        ->first();
    
    if (!$categoria) {
        abort(404, 'Categoría no encontrada');
    }
    
    // Cargar productos con paginación
    $productos = \App\Models\Producto::where('categoria_id', $categoria->id)
        ->whereHas('negocios', fn($q) => $q->where('negocio_id', $negocioId))
        ->paginate(12);
    
    $categorias = Categoria::whereHas('negocios', fn($q) => $q->where('negocio_id', $negocioId))->get();
    $marcas = Marca::all();
    $subcategorias = Subcategoria::all();
    return view('categoria.show', compact('categoria', 'categorias', 'subcategorias', 'marcas', 'productos'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    // public function show(Categoria $categoria)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        //
    }
     public function subcategorias($id)
    {
        $negocioId = negocio_actual_id();
        $subcats = Subcategoria::whereHas('categorias', fn($q) => $q->where('categorias.id', $id))
            ->whereHas('negocios', fn($q) => $q->where('negocio_id', $negocioId))
            ->orderBy('subcategoria', 'ASC')
            ->get(['id', 'subcategoria']);

        return response()->json($subcats);
    }
}
