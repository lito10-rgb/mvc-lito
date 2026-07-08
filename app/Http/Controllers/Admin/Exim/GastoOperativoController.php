<?php

namespace App\Http\Controllers\Admin\Exim;

use App\Http\Controllers\Controller;
use App\Models\Exim\GastoOperativo;
use Illuminate\Http\Request;

class GastoOperativoController extends Controller
{
    public function index()
    {
        $gastosOperativos = GastoOperativo::orderBy('id', 'desc')->paginate(20);
        return view('admin.exim.gastos_operativos.index', compact('gastosOperativos'));
    }

    public function create()
    {
        return view('admin.exim.gastos_operativos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'        => 'required|string|max:100',
            'descripcion'   => 'nullable|string',
            'costo'         => 'required|numeric|min:0',
            'tipo_calculo'  => 'required|in:por_kg,por_saco,por_lote,fijo',
            'moneda_id'     => 'nullable|integer|exists:exim_monedas,id',
            'activo'        => 'boolean',
        ]);

        GastoOperativo::create($validated);

        return redirect()->route('admin.exim.gastos_operativos.index')->with('success', 'Gasto operativo creado correctamente.');
    }

    public function edit(GastoOperativo $gastoOperativo)
    {
        return view('admin.exim.gastos_operativos.edit', compact('gastoOperativo'));
    }

    public function update(Request $request, GastoOperativo $gastoOperativo)
    {
        $validated = $request->validate([
            'nombre'        => 'required|string|max:100',
            'descripcion'   => 'nullable|string',
            'costo'         => 'required|numeric|min:0',
            'tipo_calculo'  => 'required|in:por_kg,por_saco,por_lote,fijo',
            'moneda_id'     => 'nullable|integer|exists:exim_monedas,id',
            'activo'        => 'boolean',
        ]);

        $gastoOperativo->update($validated);

        return redirect()->route('admin.exim.gastos_operativos.index')->with('success', 'Gasto operativo actualizado correctamente.');
    }

    public function destroy(GastoOperativo $gastoOperativo)
    {
        $gastoOperativo->delete();

        return redirect()->route('admin.exim.gastos_operativos.index')->with('success', 'Gasto operativo eliminado.');
    }
}
