<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CondicionesComerciale;
use Illuminate\Http\Request;

class CondicionesComercialeController extends Controller
{
    public function index()
    {
        $condiciones = CondicionesComerciale::orderBy('titulo')->paginate(20);
        return view('admin.condiciones.index', compact('condiciones'));
    }

    public function create()
    {
        return view('admin.condiciones.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'activo' => 'nullable|boolean',
        ]);

        CondicionesComerciale::create($validated);

        return redirect()->route('admin.condiciones.index')
            ->with('success', 'Condición creada correctamente.');
    }

    public function edit(CondicionesComerciale $condicionesComerciale)
    {
        return view('admin.condiciones.edit', compact('condicionesComerciale'));
    }

    public function update(Request $request, CondicionesComerciale $condicionesComerciale)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'activo' => 'nullable|boolean',
        ]);

        $condicionesComerciale->update($validated);

        return redirect()->route('admin.condiciones.index')
            ->with('success', 'Condición actualizada correctamente.');
    }

    public function destroy(CondicionesComerciale $condicionesComerciale)
    {
        $condicionesComerciale->delete();
        return back()->with('success', 'Condición eliminada.');
    }
}
