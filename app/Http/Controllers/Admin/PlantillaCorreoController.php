<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlantillaCorreo;
use Illuminate\Http\Request;

class PlantillaCorreoController extends Controller
{
    public function index()
    {
        $plantillas = PlantillaCorreo::orderBy('nombre')->paginate(20);
        return view('admin.plantillas.index', compact('plantillas'));
    }

    public function create()
    {
        return view('admin.plantillas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'asunto' => 'required|string|max:255',
            'contenido' => 'required|string',
            'activo' => 'nullable|boolean',
        ]);

        PlantillaCorreo::create([
            'nombre' => $validated['nombre'],
            'asunto' => $validated['asunto'],
            'contenido' => $validated['contenido'],
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()->route('admin.plantillas.index')
            ->with('success', 'Plantilla creada correctamente.');
    }

    public function edit(PlantillaCorreo $plantilla)
    {
        return view('admin.plantillas.edit', compact('plantilla'));
    }

    public function update(Request $request, PlantillaCorreo $plantilla)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'asunto' => 'required|string|max:255',
            'contenido' => 'required|string',
            'activo' => 'nullable|boolean',
        ]);

        $plantilla->update([
            'nombre' => $validated['nombre'],
            'asunto' => $validated['asunto'],
            'contenido' => $validated['contenido'],
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()->route('admin.plantillas.index')
            ->with('success', 'Plantilla actualizada correctamente.');
    }

    public function destroy(PlantillaCorreo $plantilla)
    {
        $plantilla->delete();
        return back()->with('success', 'Plantilla eliminada.');
    }
}
