<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subcategoria;
use App\Models\Categoria;
use App\Models\Negocio;
use Illuminate\Http\Request;

class SubcategoriaController extends Controller
{
    public function index(Request $request)
    {
        $query = Subcategoria::with('categoria', 'negocios');

        $negId = $request->input('negocio_id');
        if ($negId === null) {
            $negId = 1;
        }
        if ($negId !== '' && $negId !== null) {
            $query->whereHas('negocios', function ($q) use ($negId) {
                $q->where('negocio_id', $negId);
            });
        }

        $subcategorias = $query->paginate(10);
        $negocios = Negocio::orderBy('nombre')->get();

        return view('admin.subcategorias.index', compact('subcategorias', 'negocios'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        $negocios = Negocio::all();
        $subcategoriaNegocioIds = Negocio::where('dominio', 'equiposymaquinas.com')->pluck('id')->toArray();

        return view('admin.subcategorias.create', compact('categorias', 'negocios', 'subcategoriaNegocioIds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_categoria' => 'required|exists:categorias,id',
            'subcategoria' => 'required|string|max:255',
        ]);

        $subcategoria = Subcategoria::create([
            'subcategoria' => $request->subcategoria,
            'id_categoria' => $request->id_categoria,
            'ruta' => \Str::slug($request->subcategoria),
            'estado' => 1,
        ]);

        $subcategoria->negocios()->sync($request->input('negocios', []));

        return redirect()->route('admin.subcategorias.index')->with('success', 'Subcategoría creada.');
    }

    public function edit(Subcategoria $subcategoria)
    {
        $categorias = Categoria::all();
        $negocios = Negocio::all();
        $subcategoriaNegocioIds = $subcategoria->negocios->pluck('id')->toArray();

        return view('admin.subcategorias.edit', compact('subcategoria', 'categorias', 'negocios', 'subcategoriaNegocioIds'));
    }

    public function update(Request $request, Subcategoria $subcategoria)
    {
        $request->validate([
            'id_categoria' => 'required|exists:categorias,id',
            'subcategoria' => 'required|string|max:255',
        ]);

        $subcategoria->update([
            'subcategoria' => $request->subcategoria,
            'id_categoria' => $request->id_categoria,
            'ruta' => \Str::slug($request->subcategoria),
        ]);

        $subcategoria->negocios()->sync($request->input('negocios', []));

        return redirect()->route('admin.subcategorias.index')->with('success', 'Actualizado correctamente.');
    }

    public function destroy(Subcategoria $subcategoria)
    {
        $subcategoria->delete();
        return back()->with('success', 'Subcategoría eliminada.');
    }

    public function eliminarMultiple(Request $request)
    {
        Subcategoria::whereIn('id', $request->ids)->delete();
        return response()->json(['ok' => true]);
    }
}
