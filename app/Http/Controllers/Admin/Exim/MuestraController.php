<?php

namespace App\Http\Controllers\Admin\Exim;

use App\Http\Controllers\Controller;
use App\Models\Exim\Cotizacion;
use App\Models\Exim\Muestra;
use App\Models\Exim\Producto;
use Illuminate\Http\Request;

class MuestraController extends Controller
{
    public function index()
    {
        $muestras = Muestra::orderBy('id', 'desc')->paginate(20);
        return view('admin.exim.muestras.index', compact('muestras'));
    }

    public function create()
    {
        $productos = Producto::orderBy('nombre')->get();
        $cotizaciones = Cotizacion::orderBy('codigo')->get();
        return view('admin.exim.muestras.create', compact('productos', 'cotizaciones'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'producto_id'   => 'required|integer|exists:exim_productos,id',
            'cotizacion_id' => 'nullable|integer|exists:exim_cotizaciones,id',
            'cantidad'      => 'required|numeric|min:0',
            'peso_kg'       => 'required|numeric|min:0',
            'tipo_empaque'  => 'nullable|string|max:100',
            'caja'          => 'nullable|string|max:100',
            'etiquetas'     => 'nullable|string',
            'certificados'  => 'nullable|string',
            'courier'       => 'nullable|string|max:255',
            'seguro'        => 'nullable|numeric|min:0',
            'valor_muestra' => 'nullable|numeric|min:0',
            'costo_envio'   => 'nullable|numeric|min:0',
            'costo_seguro'  => 'nullable|numeric|min:0',
            'costo_total'   => 'nullable|numeric|min:0',
        ]);

        Muestra::create($validated);

        return redirect()->route('admin.exim.muestras.index')->with('success', 'Muestra creada correctamente.');
    }

    public function edit(Muestra $muestra)
    {
        $productos = Producto::orderBy('nombre')->get();
        $cotizaciones = Cotizacion::orderBy('codigo')->get();
        return view('admin.exim.muestras.edit', compact('muestra', 'productos', 'cotizaciones'));
    }

    public function update(Request $request, Muestra $muestra)
    {
        $validated = $request->validate([
            'producto_id'   => 'required|integer|exists:exim_productos,id',
            'cotizacion_id' => 'nullable|integer|exists:exim_cotizaciones,id',
            'cantidad'      => 'required|numeric|min:0',
            'peso_kg'       => 'required|numeric|min:0',
            'tipo_empaque'  => 'nullable|string|max:100',
            'caja'          => 'nullable|string|max:100',
            'etiquetas'     => 'nullable|string',
            'certificados'  => 'nullable|string',
            'courier'       => 'nullable|string|max:255',
            'seguro'        => 'nullable|numeric|min:0',
            'valor_muestra' => 'nullable|numeric|min:0',
            'costo_envio'   => 'nullable|numeric|min:0',
            'costo_seguro'  => 'nullable|numeric|min:0',
            'costo_total'   => 'nullable|numeric|min:0',
        ]);

        $muestra->update($validated);

        return redirect()->route('admin.exim.muestras.index')->with('success', 'Muestra actualizada correctamente.');
    }

    public function destroy(Muestra $muestra)
    {
        $muestra->delete();

        return redirect()->route('admin.exim.muestras.index')->with('success', 'Muestra eliminada.');
    }
}
