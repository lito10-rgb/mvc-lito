<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::orderBy('id','desc')->paginate(10);
        return view('admin.proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        return view('admin.proveedores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'ruc' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:500',
        ]);

        Proveedor::create($request->only(['nombre','ruc','telefono','email','direccion']));

        return redirect()->route('admin.proveedores.index')->with('success', 'Proveedor creado.');
    }

    public function edit(Proveedor $proveedor)
    {
        return view('admin.proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'ruc' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:500',
        ]);

        $proveedor->update($request->only(['nombre','ruc','telefono','email','direccion']));

        return redirect()->route('admin.proveedores.index')->with('success', 'Proveedor actualizado.');
    }

    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();
        return back()->with('success', 'Proveedor eliminado.');
    }

    // eliminar múltiple via AJAX
    public function eliminarMultiple(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return response()->json(['message' => 'No se recibieron IDs.'], 400);
        }

        try {
            DB::beginTransaction();
            Proveedor::whereIn('id', $ids)->delete();
            DB::commit();
            return response()->json(['message' => 'Proveedores eliminados correctamente.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error eliminarMultiple proveedores: '.$e->getMessage());
            return response()->json(['message' => 'Error al eliminar.'], 500);
        }
    }
}
