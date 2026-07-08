<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Cotizacion;
use App\Models\Producto;
use App\Models\Role;
use App\Models\Rubro;
use App\Models\User;
use Illuminate\Http\Request;

class CotizacionController extends Controller
{
    public function index(Request $request)
    {
        $query = Cotizacion::orderBy('id', 'desc');

        if ($search = $request->get('cliente')) {
            $query->where('cliente', 'like', "%{$search}%");
        }

        $cotizaciones = $query->paginate(10)->withQueryString();

        return view('admin.cotizaciones.index', compact('cotizaciones'));
    }

    public function create()
    {
        $productos = Producto::with('categoria', 'subcategoria')->orderBy('titulo')->get();
        $categorias = Categoria::orderBy('nombre')->get();
        $usuarios = User::with(['profile', 'scores', 'roles', 'rubros'])->orderBy('nombre')->get();
        $roles = Role::orderBy('nombre')->get();
        $rubros = Rubro::orderBy('nombre')->get();
        return view('admin.cotizaciones.create', compact('productos', 'categorias', 'usuarios', 'roles', 'rubros'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha'          => 'required|date',
            'cliente'        => 'required|string|max:255',
            'telefono'       => 'nullable|string|max:50',
            'correo'         => 'nullable|email|max:255',
            'producto'       => 'required|string|max:255',
            'descripcion'    => 'nullable|string',
            'cantidad'       => 'required|integer|min:1',
            'precio_unitario'=> 'required|numeric|min:0',
            'impuesto'       => 'nullable|numeric|min:0',
            'estado'         => 'required|in:pendiente,aprobada,rechazada,completada',
        ]);

        $validated['subtotal'] = $validated['cantidad'] * $validated['precio_unitario'];
        $validated['impuesto'] = $validated['impuesto'] ?? 0;
        $validated['total']    = $validated['subtotal'] + $validated['impuesto'];

        Cotizacion::create($validated);

        return redirect()->route('admin.cotizaciones.index')->with('success', 'Cotización creada correctamente.');
    }

    public function show(Cotizacion $cotizacione)
    {
        return view('admin.cotizaciones.show', compact('cotizacione'));
    }

    public function edit(Cotizacion $cotizacione)
    {
        $productos = Producto::with('categoria', 'subcategoria')->orderBy('titulo')->get();
        $categorias = Categoria::orderBy('nombre')->get();
        $usuarios = User::with(['profile', 'scores', 'roles', 'rubros'])->orderBy('nombre')->get();
        $roles = Role::orderBy('nombre')->get();
        $rubros = Rubro::orderBy('nombre')->get();
        return view('admin.cotizaciones.edit', compact('cotizacione', 'productos', 'categorias', 'usuarios', 'roles', 'rubros'));
    }

    public function update(Request $request, Cotizacion $cotizacione)
    {
        $validated = $request->validate([
            'fecha'          => 'required|date',
            'cliente'        => 'required|string|max:255',
            'telefono'       => 'nullable|string|max:50',
            'correo'         => 'nullable|email|max:255',
            'producto'       => 'required|string|max:255',
            'descripcion'    => 'nullable|string',
            'cantidad'       => 'required|integer|min:1',
            'precio_unitario'=> 'required|numeric|min:0',
            'impuesto'       => 'nullable|numeric|min:0',
            'estado'         => 'required|in:pendiente,aprobada,rechazada,completada',
        ]);

        $validated['subtotal'] = $validated['cantidad'] * $validated['precio_unitario'];
        $validated['impuesto'] = $validated['impuesto'] ?? 0;
        $validated['total']    = $validated['subtotal'] + $validated['impuesto'];

        $cotizacione->update($validated);

        return redirect()->route('admin.cotizaciones.index')->with('success', 'Cotización actualizada correctamente.');
    }

    public function destroy(Cotizacion $cotizacione)
    {
        $cotizacione->delete();

        return back()->with('success', 'Cotización eliminada.');
    }
}
