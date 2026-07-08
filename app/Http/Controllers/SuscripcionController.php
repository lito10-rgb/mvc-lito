<?php

namespace App\Http\Controllers;

use App\Models\Suscripcion;
use Illuminate\Http\Request;

class SuscripcionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:150|unique:suscripciones,email',
            'nombre' => 'nullable|string|max:150',
        ]);

        Suscripcion::create($request->all());

        return back()->with('success', '¡Gracias por suscribirte al boletín!');
    }
}
