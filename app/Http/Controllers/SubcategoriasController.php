<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subcategoria;

class SubcategoriasController extends Controller
{
    //
    public function show($id)
    {
        $subcategoria = Subcategoria::with('productos')->findOrFail($id);
        return view('subcategoria.show', compact('subcategorias'));
    }
//     public function porCategoria($categoria_id)
// {
//     $subcategorias = \App\Models\Subcategoria::where('categoria_id', $categoria_id)->get();
//     return response()->json($subcategorias);
// }
// public function porCategoria($categoria_id)
// {
//     $subcategorias = \App\Models\Subcategoria::where('categoria_id', $categoria_id)->get();

//     return response()->json($subcategorias);
// }
public function porCategoria($id_categoria)
    {
        return Subcategoria::where('id_categoria', $id_categoria)->get();
    }
}
