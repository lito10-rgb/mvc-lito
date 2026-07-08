<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rubro;
use Illuminate\Http\Request;

class RubroController extends Controller
{
    public function index()
    {
        $rubros = Rubro::paginate(10);
        return view('admin.rubros.index', compact('rubros'));
    }

    public function create()
    {
        return view('admin.rubros.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        Rubro::create([
            'nombre' => $request->nombre,
        ]);

        return redirect()->route('admin.rubros.index')
                         ->with('success', 'Rubro creado correctamente.');
    }

    public function edit(Rubro $rubro)
    {
        return view('admin.rubros.edit', compact('rubro'));
    }

    public function update(Request $request, Rubro $rubro)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $rubro->update([
            'nombre' => $request->nombre,
        ]);

        return redirect()->route('admin.rubros.index')
                         ->with('success', 'Rubro actualizado.');
    }

    public function destroy(Rubro $rubro)
    {
        $rubro->delete();
        return redirect()->route('admin.rubros.index')
                         ->with('success', 'Rubro eliminado.');
    }

    public function eliminarMultiple(Request $request)
    {
        $ids = $request->ids ?? [];

        if (empty($ids)) {
            return response()->json(['error' => 'No se enviaron IDs'], 400);
        }

        Rubro::whereIn('id', $ids)->delete();

        return response()->json([
            'message' => 'Rubros eliminados correctamente',
            'ids' => $ids,
        ]);
    }
}
