<?php

namespace App\Http\Controllers\Admin\Exim;

use App\Http\Controllers\Controller;
use App\Models\Exim\GastoLogistico;
use Illuminate\Http\Request;

class GastoLogisticoController extends Controller
{
    public function index()
    {
        $gastosLogisticos = GastoLogistico::orderBy('id', 'desc')->paginate(20);
        return view('admin.exim.gastos-logisticos.index', compact('gastosLogisticos'));
    }

    public function create()
    {
        return view('admin.exim.gastos-logisticos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'costo'       => 'required|numeric|min:0',
            'moneda_id'   => 'required|integer|exists:exim_monedas,id',
            'activo'      => 'nullable|boolean',
        ]);

        $validated['activo'] = $request->boolean('activo');

        GastoLogistico::create($validated);

        return redirect()->route('admin.exim.gastos-logisticos.index')->with('success', 'Gasto logístico creado correctamente.');
    }

    public function edit(GastoLogistico $gastosLogistico)
    {
        return view('admin.exim.gastos-logisticos.edit', compact('gastosLogistico'));
    }

    public function update(Request $request, GastoLogistico $gastosLogistico)
    {
        $validated = $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'costo'       => 'required|numeric|min:0',
            'moneda_id'   => 'required|integer|exists:exim_monedas,id',
            'activo'      => 'nullable|boolean',
        ]);

        $validated['activo'] = $request->boolean('activo');

        $gastosLogistico->update($validated);

        return redirect()->route('admin.exim.gastos-logisticos.index')->with('success', 'Gasto logístico actualizado correctamente.');
    }

    public function destroy(GastoLogistico $gastosLogistico)
    {
        $gastosLogistico->delete();

        return redirect()->route('admin.exim.gastos-logisticos.index')->with('success', 'Gasto logístico eliminado.');
    }
}
