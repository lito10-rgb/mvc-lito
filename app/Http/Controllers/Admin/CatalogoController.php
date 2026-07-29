<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\Negocio;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function index()
    {
        $categorias = Categoria::where('estado', 1)->with('negocios')->orderBy('nombre')->get();
        $marcas = Marca::where('estado', 1)->with('negocios')->orderBy('nombre')->get();
        $negocios = Negocio::orderBy('nombre')->get();
        return view('admin.catalogos.index', compact('categorias', 'marcas', 'negocios'));
    }

    public function print(Request $request)
    {
        $tipo = $request->input('tipo');
        $id = $request->input('id');
        $negocioId = $request->input('negocio_id');

        $query = Producto::with(['categoria', 'marca', 'negocios'])->where('estado', 1);

        if ($negocioId) {
            $query->whereHas('negocios', fn($q) => $q->where('negocio_id', $negocioId));
        }

        if ($tipo === 'categoria') {
            $query->where('categoria_id', $id);
            $titulo = Categoria::find($id)?->nombre ?? 'Catálogo';
        } elseif ($tipo === 'marca') {
            $query->where('marca_id', $id);
            $titulo = Marca::find($id)?->nombre ?? 'Catálogo';
        } else {
            $titulo = 'Catálogo General';
        }

        $productos = $query->orderBy('titulo')->get();
        $negocio = $negocioId
            ? Negocio::find($negocioId)
            : Negocio::find(session('negocio_id'));
        $negNombre = $negocio?->nombre ?? 'Tienda';
        $negWeb = $negocio?->dominio ?? '';
        $negEmpresa = $negocio?->empresa ?? '';
        $sinPrecio = $request->boolean('sin_precio');

        return view('admin.catalogos.print', compact('productos', 'titulo', 'negNombre', 'negWeb', 'negEmpresa', 'sinPrecio'));
    }
}
