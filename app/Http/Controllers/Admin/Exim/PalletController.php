<?php

namespace App\Http\Controllers\Admin\Exim;

use App\Http\Controllers\Controller;
use App\Models\Exim\Pallet;
use Illuminate\Http\Request;

class PalletController extends Controller
{
    public function index()
    {
        $pallets = Pallet::orderBy('id', 'desc')->paginate(20);
        return view('admin.exim.pallets.index', compact('pallets'));
    }

    public function create()
    {
        return view('admin.exim.pallets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo'           => 'required|in:estandar,euro,industrial,personalizado',
            'material'       => 'required|in:madera,plastico,metal',
            'largo_cm'       => 'required|numeric|min:0',
            'ancho_cm'       => 'required|numeric|min:0',
            'alto_cm'        => 'required|numeric|min:0',
            'peso_kg'        => 'required|numeric|min:0',
            'capacidad_kg'   => 'required|numeric|min:0',
            'costo_unitario' => 'required|numeric|min:0',
        ]);

        Pallet::create($validated);

        return redirect()->route('admin.exim.pallets.index')->with('success', 'Pallet creado correctamente.');
    }

    public function edit(Pallet $pallet)
    {
        return view('admin.exim.pallets.edit', compact('pallet'));
    }

    public function update(Request $request, Pallet $pallet)
    {
        $validated = $request->validate([
            'tipo'           => 'required|in:estandar,euro,industrial,personalizado',
            'material'       => 'required|in:madera,plastico,metal',
            'largo_cm'       => 'required|numeric|min:0',
            'ancho_cm'       => 'required|numeric|min:0',
            'alto_cm'        => 'required|numeric|min:0',
            'peso_kg'        => 'required|numeric|min:0',
            'capacidad_kg'   => 'required|numeric|min:0',
            'costo_unitario' => 'required|numeric|min:0',
        ]);

        $pallet->update($validated);

        return redirect()->route('admin.exim.pallets.index')->with('success', 'Pallet actualizado correctamente.');
    }

    public function destroy(Pallet $pallet)
    {
        $pallet->delete();

        return redirect()->route('admin.exim.pallets.index')->with('success', 'Pallet eliminado.');
    }
}
