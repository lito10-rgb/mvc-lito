<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TarifaEnvio;
use App\Models\TipoEnvio;
use App\Models\Categoria;
use App\Models\Subcategoria;
use Illuminate\Http\Request;

class TarifaEnvioController extends Controller
{
    public function index()
    {
        $tipos = TipoEnvio::orderBy('orden')->with('tarifas')->get();
        return view('admin.tarifas-envio.index', compact('tipos'));
    }

    public function create()
    {
        $tipos = TipoEnvio::orderBy('nombre')->get();
        $categorias = Categoria::orderBy('categoria')->get();
        return view('admin.tarifas-envio.create', compact('tipos', 'categorias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tipo_envio_id' => 'required|exists:tipos_envio,id',
            'categoria_id' => 'nullable|exists:categorias,id',
            'subcategoria_id' => 'nullable|exists:subcategorias,id',
            'minimo' => 'nullable|numeric|min:0',
            'maximo' => 'nullable|numeric|min:0',
            'costo' => 'required_unless:gratis,1|nullable|numeric|min:0',
            'gratis' => 'nullable|boolean',
            'activo' => 'nullable|boolean',
        ]);
        $data['activo'] = $request->boolean('activo', true);
        $data['gratis'] = $request->boolean('gratis', false);
        if ($data['gratis']) $data['costo'] = 0;
        TarifaEnvio::create($data);
        return redirect()->route('admin.tarifas-envio.index')->with('success', 'Tarifa de envío creada');
    }

    public function edit(TarifaEnvio $tarifaEnvio)
    {
        $tipos = TipoEnvio::orderBy('nombre')->get();
        $categorias = Categoria::orderBy('categoria')->get();
        $subcategorias = Subcategoria::orderBy('subcategoria')->get();
        return view('admin.tarifas-envio.edit', compact('tarifaEnvio', 'tipos', 'categorias', 'subcategorias'));
    }

    public function update(Request $request, TarifaEnvio $tarifaEnvio)
    {
        $data = $request->validate([
            'tipo_envio_id' => 'required|exists:tipos_envio,id',
            'categoria_id' => 'nullable|exists:categorias,id',
            'subcategoria_id' => 'nullable|exists:subcategorias,id',
            'minimo' => 'nullable|numeric|min:0',
            'maximo' => 'nullable|numeric|min:0',
            'costo' => 'required_unless:gratis,1|nullable|numeric|min:0',
            'gratis' => 'nullable|boolean',
            'activo' => 'nullable|boolean',
        ]);
        $data['activo'] = $request->boolean('activo', true);
        $data['gratis'] = $request->boolean('gratis', false);
        if ($data['gratis']) $data['costo'] = 0;
        $tarifaEnvio->update($data);
        return redirect()->route('admin.tarifas-envio.index')->with('success', 'Tarifa de envío actualizada');
    }

    public function destroy(TarifaEnvio $tarifaEnvio)
    {
        $tarifaEnvio->delete();
        return redirect()->route('admin.tarifas-envio.index')->with('success', 'Tarifa de envío eliminada');
    }
}