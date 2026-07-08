<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitaTecnica;
use Illuminate\Http\Request;

class VisitaTecnicaController extends Controller
{
    public function index()
    {
        $visitas = VisitaTecnica::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.visitas-tecnicas.index', compact('visitas'));
    }

    public function show($id)
    {
        $visita = VisitaTecnica::findOrFail($id);
        return view('admin.visitas-tecnicas.show', compact('visita'));
    }

    public function destroy($id)
    {
        VisitaTecnica::findOrFail($id)->delete();
        return redirect()->route('admin.visitas-tecnicas.index')->with('success', 'Visita eliminada.');
    }
}
