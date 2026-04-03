<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactoController extends Controller
{
    public function index()
    {
        return view('contacto.index');
    }

    public function enviar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email',
            'mensaje' => 'required|string|max:500',
        ]);

        // Aquí podrías enviar un correo o guardar en BD
        // Por ahora solo simulamos
        return back()->with('success', 'Gracias por contactarnos, te responderemos pronto.');
    }
}
