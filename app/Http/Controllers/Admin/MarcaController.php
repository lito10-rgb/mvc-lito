<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use App\Models\Negocio;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function index()
    {
        $marcas = Marca::paginate(10);
        return view('admin.marcas.index', compact('marcas'));
    }

    public function create()
    {
        $negocios = Negocio::orderBy('nombre')->get();
        return view('admin.marcas.create', compact('negocios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'negocios' => 'nullable|array',
            'negocios.*' => 'exists:negocios,id',
        ]);

        $marca = Marca::create([
            'nombre' => $request->nombre,
            'ruta' => \Str::slug($request->nombre),
            'descripcion' => $request->nombre,
            'detalle' => $request->nombre,
            'estado' => 1,
            'imgMarca' => 'sin-marca.jpg',
            'fecha' => now(),
        ]);

        if ($request->negocios) {
            $marca->negocios()->sync($request->negocios);
        }

        return redirect()->route('admin.marcas.index')
                         ->with('success', 'Marca creada correctamente.');
    }

    public function edit(Marca $marca)
    {
        $negocios = Negocio::orderBy('nombre')->get();
        $marca->load('negocios');
        return view('admin.marcas.edit', compact('marca', 'negocios'));
    }

    public function update(Request $request, Marca $marca)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'negocios' => 'nullable|array',
            'negocios.*' => 'exists:negocios,id',
        ]);

        $marca->update([
            'nombre' => $request->nombre,
            'ruta' => \Str::slug($request->nombre),
        ]);

        $marca->negocios()->sync($request->negocios ?? []);

        return redirect()->route('admin.marcas.index')
                         ->with('success', 'Marca actualizada.');
    }

    public function destroy(Marca $marca)
    {
        $marca->negocios()->detach();
        $marca->delete();
        return redirect()->route('admin.marcas.index')
                         ->with('success', 'Marca eliminada.');
    }

    public function eliminarMultiple(Request $request)
    {
        $ids = $request->ids ?? [];

        if (empty($ids)) {
            return response()->json(['error' => 'No se enviaron IDs'], 400);
        }

        Marca::whereIn('id', $ids)->each(fn($m) => $m->negocios()->detach());
        Marca::whereIn('id', $ids)->delete();

        return response()->json([
            'message' => 'Marcas eliminadas correctamente',
            'ids' => $ids
        ]);
    }
}
