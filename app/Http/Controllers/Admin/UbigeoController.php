<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pais;
use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Distrito;
use Illuminate\Http\Request;

class UbigeoController extends Controller
{
    // ============ PAÍSES ============
    public function paises()
    {
        $paises = Pais::withCount('departamentos')->orderBy('nombre')->get();
        return view('admin.ubigeo.paises', compact('paises'));
    }

    public function paisesStore(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:255']);
        Pais::create(['nombre' => $request->nombre]);
        return back()->with('success', 'País creado correctamente.');
    }

    public function paisesUpdate(Request $request, Pais $pais)
    {
        $request->validate(['nombre' => 'required|string|max:255']);
        $pais->update(['nombre' => $request->nombre]);
        return back()->with('success', 'País actualizado correctamente.');
    }

    public function paisesDestroy(Pais $pais)
    {
        if ($pais->departamentos()->exists()) {
            return back()->with('error', 'No se puede eliminar: tiene departamentos asociados.');
        }
        $usos = \DB::table('user_profiles')->where('pais', $pais->id)->count();
        if ($usos > 0) {
            return back()->with('error', "No se puede eliminar: está en uso por {$usos} perfil(es) de usuario.");
        }
        $pais->delete();
        return back()->with('success', 'País eliminado.');
    }

    // ============ DEPARTAMENTOS ============
    public function departamentos(Request $request)
    {
        $query = Departamento::with('pais')->withCount('provincias');

        if ($request->pais_id) {
            $query->where('pais_id', $request->pais_id);
        }
        if ($request->nombre) {
            $query->where('nombre', 'like', "%{$request->nombre}%");
        }

        $departamentos = $query->orderBy('nombre')->paginate(15)->withQueryString();
        $paises = Pais::orderBy('nombre')->get();

        return view('admin.ubigeo.departamentos', compact('departamentos', 'paises'));
    }

    public function departamentosStore(Request $request)
    {
        $request->validate([
            'pais_id' => 'required|exists:paises,id',
            'nombre' => 'required|string|max:255',
        ]);

        Departamento::create([
            'pais_id' => $request->pais_id,
            'nombre' => $request->nombre,
        ]);

        return redirect()->route('admin.ubigeo.departamentos', ['pais_id' => $request->pais_id])
                         ->with('success', 'Departamento creado correctamente.');
    }

    public function departamentosUpdate(Request $request, Departamento $departamento)
    {
        $request->validate([
            'pais_id' => 'required|exists:paises,id',
            'nombre' => 'required|string|max:255',
        ]);

        $departamento->update([
            'pais_id' => $request->pais_id,
            'nombre' => $request->nombre,
        ]);

        return back()->with('success', 'Departamento actualizado correctamente.');
    }

    public function departamentosDestroy(Departamento $departamento)
    {
        if ($departamento->provincias()->exists()) {
            return back()->with('error', 'No se puede eliminar: tiene provincias asociadas.');
        }
        $usos = \DB::table('user_profiles')->where('estado', $departamento->id)->count();
        if ($usos > 0) {
            return back()->with('error', "No se puede eliminar: está en uso por {$usos} perfil(es) de usuario.");
        }
        $departamento->delete();
        return back()->with('success', 'Departamento eliminado.');
    }

    // ============ PROVINCIAS ============
    public function provincias(Request $request)
    {
        $query = Provincia::with('departamento.pais')->withCount('distritos');

        if ($request->pais_id) {
            $query->whereHas('departamento', fn($q) => $q->where('pais_id', $request->pais_id));
        }
        if ($request->departamento_id) {
            $query->where('departamento_id', $request->departamento_id);
        }
        if ($request->nombre) {
            $query->where('nombre', 'like', "%{$request->nombre}%");
        }

        $provincias = $query->orderBy('nombre')->paginate(15)->withQueryString();
        $paises = Pais::orderBy('nombre')->get();
        $departamentos = $request->pais_id
            ? Departamento::where('pais_id', $request->pais_id)->orderBy('nombre')->get()
            : collect();

        return view('admin.ubigeo.provincias', compact('provincias', 'paises', 'departamentos'));
    }

    public function provinciasStore(Request $request)
    {
        $request->validate([
            'departamento_id' => 'required|exists:departamentos,id',
            'nombre' => 'required|string|max:255',
        ]);

        $departamento = Departamento::find($request->departamento_id);

        Provincia::create([
            'departamento_id' => $request->departamento_id,
            'nombre' => $request->nombre,
        ]);

        return redirect()->route('admin.ubigeo.provincias', ['pais_id' => $departamento->pais_id, 'departamento_id' => $departamento->id])
                         ->with('success', 'Provincia creada correctamente.');
    }

    public function provinciasUpdate(Request $request, Provincia $provincia)
    {
        $request->validate([
            'departamento_id' => 'required|exists:departamentos,id',
            'nombre' => 'required|string|max:255',
        ]);

        $provincia->update([
            'departamento_id' => $request->departamento_id,
            'nombre' => $request->nombre,
        ]);

        return back()->with('success', 'Provincia actualizada correctamente.');
    }

    public function provinciasDestroy(Provincia $provincia)
    {
        if ($provincia->distritos()->exists()) {
            return back()->with('error', 'No se puede eliminar: tiene distritos asociados.');
        }
        $usos = \DB::table('user_profiles')->where('provincia', $provincia->id)->count();
        if ($usos > 0) {
            return back()->with('error', "No se puede eliminar: está en uso por {$usos} perfil(es) de usuario.");
        }
        $provincia->delete();
        return back()->with('success', 'Provincia eliminada.');
    }

    // ============ DISTRITOS ============
    public function distritos(Request $request)
    {
        $query = Distrito::with('provincia.departamento.pais');

        if ($request->pais_id) {
            $query->whereHas('provincia.departamento', fn($q) => $q->where('pais_id', $request->pais_id));
        }
        if ($request->departamento_id) {
            $query->whereHas('provincia', fn($q) => $q->where('departamento_id', $request->departamento_id));
        }
        if ($request->provincia_id) {
            $query->where('provincia_id', $request->provincia_id);
        }
        if ($request->nombre) {
            $query->where('nombre', 'like', "%{$request->nombre}%");
        }

        $distritos = $query->orderBy('nombre')->paginate(15)->withQueryString();
        $paises = Pais::orderBy('nombre')->get();
        $departamentos = $request->pais_id
            ? Departamento::where('pais_id', $request->pais_id)->orderBy('nombre')->get()
            : collect();
        $provincias = $request->departamento_id
            ? Provincia::where('departamento_id', $request->departamento_id)->orderBy('nombre')->get()
            : collect();

        return view('admin.ubigeo.distritos', compact('distritos', 'paises', 'departamentos', 'provincias'));
    }

    public function distritosStore(Request $request)
    {
        $request->validate([
            'provincia_id' => 'required|exists:provincias,id',
            'nombre' => 'required|string|max:255',
        ]);

        $provincia = Provincia::with('departamento')->find($request->provincia_id);

        Distrito::create([
            'provincia_id' => $request->provincia_id,
            'nombre' => $request->nombre,
        ]);

        return redirect()->route('admin.ubigeo.distritos', [
            'pais_id' => $provincia->departamento->pais_id,
            'departamento_id' => $provincia->departamento_id,
            'provincia_id' => $provincia->id,
        ])->with('success', 'Distrito creado correctamente.');
    }

    public function distritosUpdate(Request $request, Distrito $distrito)
    {
        $request->validate([
            'provincia_id' => 'required|exists:provincias,id',
            'nombre' => 'required|string|max:255',
        ]);

        $distrito->update([
            'provincia_id' => $request->provincia_id,
            'nombre' => $request->nombre,
        ]);

        return back()->with('success', 'Distrito actualizado correctamente.');
    }

    public function distritosDestroy(Distrito $distrito)
    {
        $usos = \DB::table('user_profiles')->where('distrito', $distrito->id)->count();
        if ($usos > 0) {
            return back()->with('error', "No se puede eliminar: está en uso por {$usos} perfil(es) de usuario.");
        }
        $distrito->delete();
        return back()->with('success', 'Distrito eliminado.');
    }

    // ============ AJAX para cascadas ============
    public function ajaxDepartamentos(Pais $pais)
    {
        return response()->json(
            Departamento::where('pais_id', $pais->id)->orderBy('nombre')->get(['id', 'nombre'])
        );
    }

    public function ajaxProvincias(Departamento $departamento)
    {
        return response()->json(
            Provincia::where('departamento_id', $departamento->id)->orderBy('nombre')->get(['id', 'nombre'])
        );
    }

    public function ajaxDistritos(Provincia $provincia)
    {
        return response()->json(
            Distrito::where('provincia_id', $provincia->id)->orderBy('nombre')->get(['id', 'nombre'])
        );
    }
}