<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TipoEnvio;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TipoEnvioController extends Controller
{
    public function index()
    {
        $tipos = TipoEnvio::orderBy('orden')->get();
        return view('admin.tipos-envio.index', compact('tipos'));
    }

    public function create()
    {
        return view('admin.tipos-envio.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tipos_envio',
            'descripcion' => 'nullable|string',
            'activo' => 'nullable|boolean',
            'orden' => 'nullable|integer|min:0',
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['nombre']);
        $data['activo'] = $request->boolean('activo', true);
        TipoEnvio::create($data);
        return redirect()->route('admin.tipos-envio.index')->with('success', 'Tipo de envío creado');
    }

    public function edit(TipoEnvio $tipoEnvio)
    {
        return view('admin.tipos-envio.edit', compact('tipoEnvio'));
    }

    public function update(Request $request, TipoEnvio $tipoEnvio)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tipos_envio,slug,' . $tipoEnvio->id,
            'descripcion' => 'nullable|string',
            'activo' => 'nullable|boolean',
            'orden' => 'nullable|integer|min:0',
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['nombre']);
        $tipoEnvio->update($data);
        return redirect()->route('admin.tipos-envio.index')->with('success', 'Tipo de envío actualizado');
    }

    public function destroy(TipoEnvio $tipoEnvio)
    {
        $tipoEnvio->delete();
        return redirect()->route('admin.tipos-envio.index')->with('success', 'Tipo de envío eliminado');
    }
}