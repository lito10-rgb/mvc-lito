<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class FavoritoController extends Controller
{
    public function agregar($productoId)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->back()->with('error', 'Debes iniciar sesión para agregar favoritos.');
        }

        $producto = Producto::findOrFail($productoId);
        $user->favoritos()->toggle($producto->id);

        return redirect()->back()->with('success', 'Producto agregado a favoritos.');
    }
}
