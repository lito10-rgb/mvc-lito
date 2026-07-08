<?php

namespace App\Http\Controllers\Admin\Exim;

use App\Http\Controllers\Controller;
use App\Models\Exim\Contenedor;
use Illuminate\Http\Request;

class ContenedorController extends Controller
{
    public function index()
    {
        $contenedores = Contenedor::orderBy('id', 'desc')->paginate(20);
        return view('admin.exim.contenedores.index', compact('contenedores'));
    }

    public function create()
    {
        return view('admin.exim.contenedores.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo'              => 'required|in:20ft,40ft,40hq',
            'largo_cm'          => 'required|numeric|min:0',
            'ancho_cm'          => 'required|numeric|min:0',
            'alto_cm'           => 'required|numeric|min:0',
            'capacidad_max_kg'  => 'required|numeric|min:0',
            'pallets_max'       => 'required|integer|min:0',
            'sacos_max'         => 'nullable|integer|min:0',
            'flete_maritimo'    => 'required|numeric|min:0',
            'seguro'            => 'required|numeric|min:0',
            'gastos_portuarios' => 'required|numeric|min:0',
            'documentacion'     => 'required|numeric|min:0',
        ]);

        Contenedor::create($validated);

        return redirect()->route('admin.exim.contenedores.index')->with('success', 'Contenedor creado correctamente.');
    }

    public function edit(Contenedor $contenedor)
    {
        return view('admin.exim.contenedores.edit', compact('contenedor'));
    }

    public function update(Request $request, Contenedor $contenedor)
    {
        $validated = $request->validate([
            'tipo'              => 'required|in:20ft,40ft,40hq',
            'largo_cm'          => 'required|numeric|min:0',
            'ancho_cm'          => 'required|numeric|min:0',
            'alto_cm'           => 'required|numeric|min:0',
            'capacidad_max_kg'  => 'required|numeric|min:0',
            'pallets_max'       => 'required|integer|min:0',
            'sacos_max'         => 'nullable|integer|min:0',
            'flete_maritimo'    => 'required|numeric|min:0',
            'seguro'            => 'required|numeric|min:0',
            'gastos_portuarios' => 'required|numeric|min:0',
            'documentacion'     => 'required|numeric|min:0',
        ]);

        $contenedor->update($validated);

        return redirect()->route('admin.exim.contenedores.index')->with('success', 'Contenedor actualizado correctamente.');
    }

    public function destroy(Contenedor $contenedor)
    {
        $contenedor->delete();

        return redirect()->route('admin.exim.contenedores.index')->with('success', 'Contenedor eliminado.');
    }
}
