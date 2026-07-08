<?php

namespace App\Http\Controllers\Admin\Exim;

use App\Http\Controllers\Controller;
use App\Models\Exim\Incoterm;
use Illuminate\Http\Request;

class IncotermController extends Controller
{
    public function index()
    {
        $incoterms = Incoterm::orderBy('id', 'desc')->paginate(20);
        return view('admin.exim.incoterms.index', compact('incoterms'));
    }

    public function create()
    {
        return view('admin.exim.incoterms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo'                     => 'required|string|max:3|unique:exim_incoterms,codigo',
            'nombre'                     => 'required|string|max:100',
            'descripcion'                => 'nullable|string',
            'incluye_transporte_interno' => 'boolean',
            'incluye_flete_maritimo'     => 'boolean',
            'incluye_seguro'             => 'boolean',
            'incluye_aduanas_origen'     => 'boolean',
            'incluye_aduanas_destino'    => 'boolean',
            'incluye_transporte_destino' => 'boolean',
        ]);

        Incoterm::create($validated);

        return redirect()->route('admin.exim.incoterms.index')->with('success', 'Incoterm creado correctamente.');
    }

    public function edit(Incoterm $incoterm)
    {
        return view('admin.exim.incoterms.edit', compact('incoterm'));
    }

    public function update(Request $request, Incoterm $incoterm)
    {
        $validated = $request->validate([
            'codigo'                     => 'required|string|max:3|unique:exim_incoterms,codigo,' . $incoterm->id,
            'nombre'                     => 'required|string|max:100',
            'descripcion'                => 'nullable|string',
            'incluye_transporte_interno' => 'boolean',
            'incluye_flete_maritimo'     => 'boolean',
            'incluye_seguro'             => 'boolean',
            'incluye_aduanas_origen'     => 'boolean',
            'incluye_aduanas_destino'    => 'boolean',
            'incluye_transporte_destino' => 'boolean',
        ]);

        $incoterm->update($validated);

        return redirect()->route('admin.exim.incoterms.index')->with('success', 'Incoterm actualizado correctamente.');
    }

    public function destroy(Incoterm $incoterm)
    {
        $incoterm->delete();

        return redirect()->route('admin.exim.incoterms.index')->with('success', 'Incoterm eliminado.');
    }
}
