<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmpresaLogo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpresaLogoController extends Controller
{
    public function index()
    {
        $logos = EmpresaLogo::orderBy('por_defecto', 'desc')->orderBy('nombre')->paginate(20);
        return view('admin.logos.index', compact('logos'));
    }

    public function create()
    {
        return view('admin.logos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'logo' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
            'por_defecto' => 'nullable|boolean',
        ]);

        $ruta = $request->file('logo')->store('logos', 'public');

        if ($request->boolean('por_defecto')) {
            EmpresaLogo::where('por_defecto', true)->update(['por_defecto' => false]);
        }

        EmpresaLogo::create([
            'nombre' => $validated['nombre'],
            'ruta' => $ruta,
            'por_defecto' => $request->boolean('por_defecto'),
        ]);

        return redirect()->route('admin.logos.index')
            ->with('success', 'Logo subido correctamente.');
    }

    public function edit(EmpresaLogo $logo)
    {
        return view('admin.logos.edit', compact('logo'));
    }

    public function update(Request $request, EmpresaLogo $logo)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'por_defecto' => 'nullable|boolean',
        ]);

        if ($request->boolean('por_defecto')) {
            EmpresaLogo::where('por_defecto', true)->where('id', '!=', $logo->id)->update(['por_defecto' => false]);
        }

        $data = [
            'nombre' => $validated['nombre'],
            'por_defecto' => $request->boolean('por_defecto'),
        ];

        if ($request->hasFile('logo')) {
            Storage::disk('public')->delete($logo->ruta);
            $data['ruta'] = $request->file('logo')->store('logos', 'public');
        }

        $logo->update($data);

        return redirect()->route('admin.logos.index')
            ->with('success', 'Logo actualizado correctamente.');
    }

    public function destroy(EmpresaLogo $logo)
    {
        Storage::disk('public')->delete($logo->ruta);
        $logo->delete();
        return back()->with('success', 'Logo eliminado.');
    }
}
