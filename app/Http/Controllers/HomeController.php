<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Marca;

class HomeController extends Controller
{
    public function menu()
    {
        $negocioId = negocio_actual_id();

        $categorias = Categoria::whereHas('negocios', fn($q) => $q->where('negocio_id', $negocioId))
            ->with(['subcategorias' => fn($q) => $q->whereHas('negocios', fn($q2) => $q2->where('negocio_id', $negocioId))])
            ->get();

        $productos = Producto::whereHas('negocios', fn($q) => $q->where('negocio_id', $negocioId))
            ->orderBy('vistas', 'desc')
            ->paginate(12)
            ->through(function ($producto) {
                $producto->imagen_url = asset('storage/' . $producto->portada);
                return $producto;
            });

        $marcas = Marca::all();

        return view('home', compact('categorias', 'productos', 'marcas'));
    }
}
