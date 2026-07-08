<?php

namespace App\Http\Controllers\Admin\Exim;

use App\Http\Controllers\Controller;
use App\Models\Exim\Moneda;
use App\Models\Exim\Producto;
use App\Models\Producto as ProductoLocal;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::orderBy('id', 'desc')->paginate(20);
        return view('admin.exim.productos.index', compact('productos'));
    }

    public function create()
    {
        $monedas = Moneda::orderBy('codigo')->get();
        $productosLocales = ProductoLocal::orderBy('titulo')->get();
        return view('admin.exim.productos.create', compact('monedas', 'productosLocales'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo'        => 'required|string|max:50',
            'producto_id' => 'nullable|integer|exists:productos,id',
            'nombre'      => 'required|string|max:255',
            'atributos'   => 'nullable|json',
            'precio_base' => 'required|numeric|min:0',
            'moneda_id'   => 'required|integer|exists:exim_monedas,id',
            'estado'      => 'nullable|boolean',
        ]);

        $validated['estado'] = $request->boolean('estado');

        Producto::create($validated);

        return redirect()->route('admin.exim.productos.index')->with('success', 'Producto EXIM creado correctamente.');
    }

    public function edit(Producto $producto)
    {
        $monedas = Moneda::orderBy('codigo')->get();
        $productosLocales = ProductoLocal::orderBy('titulo')->get();
        return view('admin.exim.productos.edit', compact('producto', 'monedas', 'productosLocales'));
    }

    public function update(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'tipo'        => 'required|string|max:50',
            'producto_id' => 'nullable|integer|exists:productos,id',
            'nombre'      => 'required|string|max:255',
            'atributos'   => 'nullable|json',
            'precio_base' => 'required|numeric|min:0',
            'moneda_id'   => 'required|integer|exists:exim_monedas,id',
            'estado'      => 'nullable|boolean',
        ]);

        $validated['estado'] = $request->boolean('estado');

        $producto->update($validated);

        return redirect()->route('admin.exim.productos.index')->with('success', 'Producto EXIM actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()->route('admin.exim.productos.index')->with('success', 'Producto EXIM eliminado.');
    }
}
