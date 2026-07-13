<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subcategoria;

class SubcategoriasController extends Controller
{
    //
    public function show($ruta)
    {
        $negocioId = negocio_actual_id();
        $subcategoria = Subcategoria::whereHas('negocios', fn($q) => $q->where('negocio_id', $negocioId))
            ->with(['productos' => fn($q) => $q->whereHas('negocios', fn($q2) => $q2->where('negocio_id', $negocioId))])
            ->where('ruta', $ruta)->firstOrFail();
        return view('subcategoria.show', compact('subcategoria'));
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
        $negocioId = negocio_actual_id();
        return Subcategoria::where('id_categoria', $id_categoria)
            ->whereHas('negocios', fn($q) => $q->where('negocio_id', $negocioId))
            ->get();
    }
}
