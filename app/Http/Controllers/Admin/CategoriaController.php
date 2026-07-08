<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::with('subcategorias')->paginate(10);
        return view('admin.categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('admin.categorias.create');
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

        Categoria::create($data);

        return redirect()->route('admin.categorias.index')->with('success', 'Categoría creada');
    }

    public function edit(Categoria $categoria)
    {
        return view('admin.categorias.edit', compact('categoria'));
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

        return redirect()->route('admin.categorias.index')->with('success', 'Categoría actualizada');
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return redirect()->route('admin.categorias.index')->with('success', 'Categoría eliminada');
    }
}
