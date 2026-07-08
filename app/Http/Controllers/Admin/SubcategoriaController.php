<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subcategoria;
use App\Models\Categoria;
use Illuminate\Http\Request;

class SubcategoriaController extends Controller
{
    public function index()
    {
        $subcategorias = Subcategoria::with('categoria')->paginate(10);
        return view('admin.subcategorias.index', compact('subcategorias'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        return view('admin.subcategorias.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_categoria' => 'required|exists:categorias,id',
            'subcategoria' => 'required|string|max:255',
        ]);

        Subcategoria::create([
            'subcategoria' => $request->subcategoria,
            'id_categoria' => $request->id_categoria,
            'ruta' => \Str::slug($request->subcategoria),
            'estado' => 1,
        ]);
        return redirect()->route('admin.subcategorias.index')->with('success', 'Subcategoría creada.');
    }

    public function edit(Subcategoria $subcategoria)
    {
        $categorias = Categoria::all();
        return view('admin.subcategorias.edit', compact('subcategoria', 'categorias'));
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
