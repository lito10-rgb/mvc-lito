<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use App\Models\Pais;
use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Distrito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $query = Proveedor::with(['pais', 'departamento', 'provincia', 'distrito', 'categoria', 'subcategoria']);

        if ($buscar = $request->get('buscar')) {
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%$buscar%")
                  ->orWhere('empresa', 'like', "%$buscar%");
            });
        }

        if ($pais_id = $request->get('pais_id')) {
            $query->where('pais_id', $pais_id);
        }

        if ($categoria_id = $request->get('categoria_id')) {
            $query->where('categoria_id', $categoria_id);
        }

        if ($subcategoria_id = $request->get('subcategoria_id')) {
            $query->where('subcategoria_id', $subcategoria_id);
        }

        $query->orderBy('id', 'desc');

        $proveedores = $query->paginate(10)->withQueryString();

        $paises = Pais::orderBy('nombre')->get();
        $categorias = Categoria::orderBy('nombre')->get();
        $subcategorias = Subcategoria::orderBy('subcategoria')->get();

        return view('admin.proveedores.index', compact('proveedores', 'paises', 'categorias', 'subcategorias'));
    }

    public function create()
    {
        $paises = Pais::orderBy('nombre')->get();
        $departamentos = Departamento::orderBy('nombre')->get();
        $provincias = Provincia::orderBy('nombre')->get();
        $categorias = Categoria::orderBy('nombre')->get();
        $subcategorias = Subcategoria::orderBy('subcategoria')->get();
        $distritos = Distrito::orderBy('nombre')->get();
        return view('admin.proveedores.create', compact('paises', 'departamentos', 'provincias', 'distritos', 'categorias', 'subcategorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'ruc' => 'nullable|string|max:20',
            'empresa' => 'nullable|string|max:255',
            'web' => 'nullable|string|max:255',
            'celular' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'email2' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:500',
            'pais_id' => 'nullable|integer|exists:paises,id',
            'departamento_id' => 'nullable|integer|exists:departamentos,id',
            'provincia_id' => 'nullable|integer|exists:provincias,id',
            'distrito_id' => 'nullable|integer|exists:distritos,id',
            'codigo_postal' => 'nullable|string|max:20',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'categoria_id' => 'nullable|integer|exists:categorias,id',
            'subcategoria_id' => 'nullable|integer|exists:subcategorias,id',
            'descripcion' => 'nullable|string|max:5000',
        ]);

        Proveedor::create($request->only([
            'nombre', 'ruc', 'empresa', 'web', 'celular', 'telefono',
            'email', 'email2', 'direccion',
            'pais_id', 'departamento_id', 'provincia_id', 'distrito_id',
            'codigo_postal',
            'facebook', 'instagram', 'categoria_id',
            'subcategoria_id', 'descripcion',
        ]));

        return redirect()->route('admin.proveedores.index')->with('success', 'Proveedor creado.');
    }

    public function edit(Proveedor $proveedor)
    {
        $paises = Pais::orderBy('nombre')->get();
        $departamentos = Departamento::orderBy('nombre')->get();
        $provincias = Provincia::orderBy('nombre')->get();
        $categorias = Categoria::orderBy('nombre')->get();
        $subcategorias = Subcategoria::orderBy('subcategoria')->get();
        $distritos = Distrito::orderBy('nombre')->get();
        return view('admin.proveedores.edit', compact('proveedor', 'paises', 'departamentos', 'provincias', 'distritos', 'categorias', 'subcategorias'));
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'ruc' => 'nullable|string|max:20',
            'empresa' => 'nullable|string|max:255',
            'web' => 'nullable|string|max:255',
            'celular' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'email2' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:500',
            'pais_id' => 'nullable|integer|exists:paises,id',
            'departamento_id' => 'nullable|integer|exists:departamentos,id',
            'provincia_id' => 'nullable|integer|exists:provincias,id',
            'distrito_id' => 'nullable|integer|exists:distritos,id',
            'codigo_postal' => 'nullable|string|max:20',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'categoria_id' => 'nullable|integer|exists:categorias,id',
            'subcategoria_id' => 'nullable|integer|exists:subcategorias,id',
            'descripcion' => 'nullable|string|max:5000',
        ]);

        $proveedor->update($request->only([
            'nombre', 'ruc', 'empresa', 'web', 'celular', 'telefono',
            'email', 'email2', 'direccion',
            'pais_id', 'departamento_id', 'provincia_id', 'distrito_id',
            'codigo_postal',
            'facebook', 'instagram', 'categoria_id',
            'subcategoria_id', 'descripcion',
        ]));

        return redirect()->route('admin.proveedores.index')->with('success', 'Proveedor actualizado.');
    }

    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();
        return back()->with('success', 'Proveedor eliminado.');
    }

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
