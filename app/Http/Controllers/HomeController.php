<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Producto;

class HomeController extends Controller
{
    public function menu()
    {
        $categorias = Categoria::with('subcategorias')->get();

        $productos = Producto::orderBy('vistas', 'desc') // ✅ orden por vistas
            ->paginate(12)                               // ✅ con paginación
            ->through(function ($producto) {
                $producto->imagen_url = asset('storage/' . $producto->portada);
                return $producto;
            });

        return view('home', compact('categorias', 'productos'));
    }
}
