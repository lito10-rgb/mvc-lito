<?php

namespace App\Http\Controllers;

use App\Models\VisitaTecnica;
use Illuminate\Http\Request;

class VisitaTecnicaController extends Controller
{
    public function create()
    {
        return view('visita-tecnica.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'email' => 'required|email|max:150',
            'telefono' => 'nullable|string|max:20',
            'empresa' => 'nullable|string|max:200',
            'fecha_visita' => 'required|date|after:today',
            'mensaje' => 'nullable|string|max:1000',
        ]);

        VisitaTecnica::create($request->all());

        return redirect()->route('visita-tecnica.create')
            ->with('success', 'Solicitud enviada correctamente. Te contactaremos pronto.');
    }
}
