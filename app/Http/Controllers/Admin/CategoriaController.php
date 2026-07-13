<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Negocio;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        $query = Categoria::with('negocios', 'subcategorias');

        $negId = $request->input('negocio_id');
        if ($negId === null) {
            $negId = 1;
        }
        if ($negId !== '' && $negId !== null) {
            $query->whereHas('negocios', function ($q) use ($negId) {
                $q->where('negocio_id', $negId);
            });
        }

        $categorias = $query->paginate(10);
        $negocios = Negocio::orderBy('nombre')->get();

        return view('admin.categorias.index', compact('categorias', 'negocios'));
    }

    public function create()
    {
        $negocios = Negocio::all();
        $categoriaNegocioIds = Negocio::where('dominio', 'equiposymaquinas.com')->pluck('id')->toArray();

        return view('admin.categorias.create', compact('negocios', 'categoriaNegocioIds'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'categoria' => 'required|string|max:255',
            'ruta' => 'nullable|string|max:255',
            'estado' => 'nullable|integer',
        ]);

        $data['ruta'] = $data['ruta'] ?? Str::slug($data['categoria']);
        $data['estado'] = $data['estado'] ?? 1;

        $categoria = Categoria::create($data);
        $categoria->negocios()->sync($request->input('negocios', []));

        return redirect()->route('admin.categorias.index')->with('success', 'Categoría creada');
    }

    public function edit(Categoria $categoria)
    {
        $negocios = Negocio::all();
        $categoriaNegocioIds = $categoria->negocios->pluck('id')->toArray();

        return view('admin.categorias.edit', compact('categoria', 'negocios', 'categoriaNegocioIds'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        $data = $request->validate([
            'categoria' => 'required|string|max:255',
            'ruta' => 'nullable|string|max:255',
            'estado' => 'nullable|integer',
        ]);

        $data['ruta'] = $data['ruta'] ?? Str::slug($data['categoria']);

        $categoria->update($data);
        $categoria->negocios()->sync($request->input('negocios', []));

        return redirect()->route('admin.categorias.index')->with('success', 'Categoría actualizada');
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return redirect()->route('admin.categorias.index')->with('success', 'Categoría eliminada');
    }
}
