<?php

namespace App\Http\Controllers\Admin\Exim;

use App\Http\Controllers\Controller;
use App\Models\Exim\Moneda;
use Illuminate\Http\Request;

class MonedaController extends Controller
{
    public function index()
    {
        $monedas = Moneda::orderBy('id', 'desc')->paginate(20);
        return view('admin.exim.monedas.index', compact('monedas'));
    }

    public function create()
    {
        return view('admin.exim.monedas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo'      => 'required|string|max:3|unique:exim_monedas,codigo',
            'nombre'      => 'required|string|max:100',
            'simbolo'     => 'required|string|max:10',
            'tipo_cambio' => 'required|numeric|min:0',
        ]);

        Moneda::create($validated);

        return redirect()->route('admin.exim.monedas.index')->with('success', 'Moneda creada correctamente.');
    }

    public function edit(Moneda $moneda)
    {
        return view('admin.exim.monedas.edit', compact('moneda'));
    }

    public function update(Request $request, Moneda $moneda)
    {
        $validated = $request->validate([
            'codigo'      => 'required|string|max:3|unique:exim_monedas,codigo,' . $moneda->id,
            'nombre'      => 'required|string|max:100',
            'simbolo'     => 'required|string|max:10',
            'tipo_cambio' => 'required|numeric|min:0',
        ]);

        $moneda->update($validated);

        return redirect()->route('admin.exim.monedas.index')->with('success', 'Moneda actualizada correctamente.');
    }

    public function destroy(Moneda $moneda)
    {
        $moneda->delete();

        return redirect()->route('admin.exim.monedas.index')->with('success', 'Moneda eliminada.');
    }
}
