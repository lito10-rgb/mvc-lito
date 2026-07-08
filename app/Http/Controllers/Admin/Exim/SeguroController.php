<?php

namespace App\Http\Controllers\Admin\Exim;

use App\Http\Controllers\Controller;
use App\Models\Exim\Seguro;
use Illuminate\Http\Request;

class SeguroController extends Controller
{
    public function index()
    {
        $seguros = Seguro::orderBy('id', 'desc')->paginate(20);
        return view('admin.exim.seguros.index', compact('seguros'));
    }

    public function create()
    {
        return view('admin.exim.seguros.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'      => 'required|string|max:100',
            'porcentaje'  => 'required|numeric|min:0|max:100',
            'costo_base'  => 'required|numeric|min:0',
            'moneda_id'   => 'nullable|integer|exists:exim_monedas,id',
            'activo'      => 'boolean',
        ]);

        Seguro::create($validated);

        return redirect()->route('admin.exim.seguros.index')->with('success', 'Seguro creado correctamente.');
    }

    public function edit(Seguro $seguro)
    {
        return view('admin.exim.seguros.edit', compact('seguro'));
    }

    public function update(Request $request, Seguro $seguro)
    {
        $validated = $request->validate([
            'nombre'      => 'required|string|max:100',
            'porcentaje'  => 'required|numeric|min:0|max:100',
            'costo_base'  => 'required|numeric|min:0',
            'moneda_id'   => 'nullable|integer|exists:exim_monedas,id',
            'activo'      => 'boolean',
        ]);

        $seguro->update($validated);

        return redirect()->route('admin.exim.seguros.index')->with('success', 'Seguro actualizado correctamente.');
    }

    public function destroy(Seguro $seguro)
    {
        $seguro->delete();

        return redirect()->route('admin.exim.seguros.index')->with('success', 'Seguro eliminado.');
    }
}
