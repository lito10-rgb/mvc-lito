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
        $subcategorias = \App\Models\Subcategoria::where('estado', 1)->orderBy('subcategoria')->get();
        $productos = Producto::with('categoria', 'subcategoria', 'marca', 'negocios')->where('estado', 1)->orderBy('titulo')->get();
        $ultimoTipoCambio = \App\Models\Cotizacion::whereNotNull('tipo_cambio')->latest()->value('tipo_cambio') ?: 3.75;
        return view('admin.catalogos.index', compact('categorias', 'marcas', 'negocios', 'subcategorias', 'productos', 'ultimoTipoCambio'));
    }

    public function print(Request $request)
    {
        $tipo = $request->input('tipo');
        $id = $request->input('id');
        $negocioId = $request->input('negocio_id');
        $idsSeleccionados = $request->input('productos', []);
        $idsSeleccionados = is_array($idsSeleccionados) ? array_filter(array_map('intval', $idsSeleccionados)) : [];

        $query = Producto::with(['categoria', 'marca', 'negocios'])->where('estado', 1);

        if ($idsSeleccionados) {
            $query->whereIn('id', $idsSeleccionados);
        }

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
            $titulo = $idsSeleccionados ? 'Catálogo Seleccionado' : 'Catálogo General';
        }

        $productos = $query->orderBy('titulo')->get();
        $negocio = $negocioId
            ? Negocio::find($negocioId)
            : Negocio::find(session('negocio_id'));
        $negNombre = $negocio?->nombre ?? 'Tienda';
        $negWeb = $negocio?->dominio ?? '';
        $negEmpresa = $negocio?->empresa ?? '';
        $sinPrecio = $request->boolean('sin_precio');
        $preview = $request->boolean('preview');
        $moneda = $request->input('moneda', 'PEN');
        $moneda = $moneda === 'USD' ? 'USD' : 'PEN';
        $tipoCambio = (float) $request->input('tipo_cambio', 0);
        if ($tipoCambio <= 0) {
            $tipoCambio = (float) \App\Models\Cotizacion::whereNotNull('tipo_cambio')->latest()->value('tipo_cambio') ?: 3.75;
        }

        return view('admin.catalogos.print', compact('productos', 'titulo', 'negNombre', 'negWeb', 'negEmpresa', 'sinPrecio', 'preview', 'moneda', 'tipoCambio'));
    }
}
