<?php

namespace App\Http\Controllers\Admin\Exim;

use App\Http\Controllers\Controller;
use App\Models\Exim\Cliente;
use App\Models\Pais;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderBy('id', 'desc')->paginate(20);
        return view('admin.exim.clientes.index', compact('clientes'));
    }

    public function create()
    {
        $paises = Pais::orderBy('nombre')->get();
        return view('admin.exim.clientes.create', compact('paises'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'empresa'          => 'required|string|max:255',
            'contacto'         => 'nullable|string|max:255',
            'cargo'            => 'nullable|string|max:255',
            'pais_id'          => 'nullable|integer|exists:paises,id',
            'ciudad'           => 'nullable|string|max:255',
            'direccion'        => 'nullable|string',
            'email'            => 'nullable|email|max:255',
            'whatsapp'         => 'nullable|string|max:50',
            'telefono'         => 'nullable|string|max:50',
            'idioma'           => 'nullable|string|max:10',
            'moneda_preferida' => 'nullable|string|max:3',
            'user_id'          => 'nullable|integer|exists:users,id',
        ]);

        Cliente::create($validated);

        return redirect()->route('admin.exim.clientes.index')->with('success', 'Cliente creado correctamente.');
    }

    public function edit(Cliente $cliente)
    {
        $paises = Pais::orderBy('nombre')->get();
        return view('admin.exim.clientes.edit', compact('cliente', 'paises'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'empresa'          => 'required|string|max:255',
            'contacto'         => 'nullable|string|max:255',
            'cargo'            => 'nullable|string|max:255',
            'pais_id'          => 'nullable|integer|exists:paises,id',
            'ciudad'           => 'nullable|string|max:255',
            'direccion'        => 'nullable|string',
            'email'            => 'nullable|email|max:255',
            'whatsapp'         => 'nullable|string|max:50',
            'telefono'         => 'nullable|string|max:50',
            'idioma'           => 'nullable|string|max:10',
            'moneda_preferida' => 'nullable|string|max:3',
            'user_id'          => 'nullable|integer|exists:users,id',
        ]);

        $cliente->update($validated);

        return redirect()->route('admin.exim.clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()->route('admin.exim.clientes.index')->with('success', 'Cliente eliminado.');
    }
}
