<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoritoController extends Controller
{
    //
    public function agregar($productoId)
    {
        // Aquí puedes agregar lógica para guardar el producto en favoritos
        // Ejemplo:
        // auth()->user()->favoritos()->attach($productoId);

        return redirect()->back()->with('success', 'Producto agregado a favoritos.');
    }
}
