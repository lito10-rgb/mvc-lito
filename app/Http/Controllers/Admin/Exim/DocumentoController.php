<?php

namespace App\Http\Controllers\Admin\Exim;

use App\Http\Controllers\Controller;
use App\Models\Exim\Cotizacion;
use App\Models\Exim\Documento;
use Illuminate\Http\Request;

class DocumentoController extends Controller
{
    public function index()
    {
        $documentos = Documento::orderBy('id', 'desc')->paginate(20);
        return view('admin.exim.documentos.index', compact('documentos'));
    }

    public function create()
    {
        $cotizaciones = Cotizacion::orderBy('codigo')->get();
        return view('admin.exim.documentos.create', compact('cotizaciones'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cotizacion_id'   => 'nullable|integer|exists:exim_cotizaciones,id',
            'tipo'            => 'required|string|max:100',
            'numero_documento'=> 'nullable|string|max:100',
            'archivo'         => 'nullable|string|max:255',
        ]);

        Documento::create($validated);

        return redirect()->route('admin.exim.documentos.index')->with('success', 'Documento creado correctamente.');
    }

    public function edit(Documento $documento)
    {
        $cotizaciones = Cotizacion::orderBy('codigo')->get();
        return view('admin.exim.documentos.edit', compact('documento', 'cotizaciones'));
    }

    public function update(Request $request, Documento $documento)
    {
        $validated = $request->validate([
            'cotizacion_id'   => 'nullable|integer|exists:exim_cotizaciones,id',
            'tipo'            => 'required|string|max:100',
            'numero_documento'=> 'nullable|string|max:100',
            'archivo'         => 'nullable|string|max:255',
        ]);

        $documento->update($validated);

        return redirect()->route('admin.exim.documentos.index')->with('success', 'Documento actualizado correctamente.');
    }

    public function destroy(Documento $documento)
    {
        $documento->delete();

        return redirect()->route('admin.exim.documentos.index')->with('success', 'Documento eliminado.');
    }
}
