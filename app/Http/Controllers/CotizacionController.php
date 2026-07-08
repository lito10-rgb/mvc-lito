<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\Producto;
use Illuminate\Http\Request;

class CotizacionController extends Controller
{
    public function solicitar($id)
    {
        $producto = Producto::findOrFail($id);
        return view('cotizacion.solicitar', compact('producto'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente'        => 'required|string|max:255',
            'correo'         => 'nullable|email|max:255',
            'telefono'       => 'nullable|string|max:50',
            'producto'       => 'required|string|max:255',
            'descripcion'    => 'nullable|string',
            'cantidad'       => 'required|integer|min:1',
            'precio_unitario'=> 'required|numeric|min:0',
        ]);

        $validated['subtotal'] = $validated['cantidad'] * $validated['precio_unitario'];
        $validated['impuesto'] = 0;
        $validated['total']    = $validated['subtotal'];
        $validated['estado']   = 'pendiente';
        $validated['fecha']    = now()->format('Y-m-d');

        Cotizacion::create($validated);

        return redirect()->back()->with('success', 'Cotización enviada correctamente. Te contactaremos pronto.');
    }
}
