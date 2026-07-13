<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Subcategoria;
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
    return view('categoria.show', compact('categoria'));
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
        $subcats = Subcategoria::where('id_categoria', $id)
            ->whereHas('negocios', fn($q) => $q->where('negocio_id', $negocioId))
            ->orderBy('subcategoria', 'ASC')
            ->get(['id', 'subcategoria']);

        return response()->json($subcats);
    }
}
