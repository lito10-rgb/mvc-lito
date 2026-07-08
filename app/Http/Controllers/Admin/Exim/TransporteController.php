<?php

namespace App\Http\Controllers\Admin\Exim;

use App\Http\Controllers\Controller;
use App\Models\Exim\Transporte;
use Illuminate\Http\Request;

class TransporteController extends Controller
{
    public function index()
    {
        $transportes = Transporte::orderBy('id', 'desc')->paginate(20);
        return view('admin.exim.transportes.index', compact('transportes'));
    }

    public function create()
    {
        return view('admin.exim.transportes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo'       => 'required|in:maritimo,aereo,terrestre,courier',
            'nombre'     => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'costo_base' => 'required|numeric|min:0',
            'moneda_id'  => 'nullable|integer|exists:exim_monedas,id',
            'activo'     => 'boolean',
        ]);

        Transporte::create($validated);

        return redirect()->route('admin.exim.transportes.index')->with('success', 'Transporte creado correctamente.');
    }

    public function edit(Transporte $transporte)
    {
        return view('admin.exim.transportes.edit', compact('transporte'));
    }

    public function update(Request $request, Transporte $transporte)
    {
        $validated = $request->validate([
            'tipo'       => 'required|in:maritimo,aereo,terrestre,courier',
            'nombre'     => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'costo_base' => 'required|numeric|min:0',
            'moneda_id'  => 'nullable|integer|exists:exim_monedas,id',
            'activo'     => 'boolean',
        ]);

        $transporte->update($validated);

        return redirect()->route('admin.exim.transportes.index')->with('success', 'Transporte actualizado correctamente.');
    }

    public function destroy(Transporte $transporte)
    {
        $transporte->delete();

        return redirect()->route('admin.exim.transportes.index')->with('success', 'Transporte eliminado.');
    }
}
