<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    /**
     * Guarda un comentario/calificación de un cliente logueado.
     * La tabla comentarios referencia `usuarios` (legacy), así que se
     * sincroniza un registro `usuarios` con el usuario autenticado.
     */
    public function store(Request $request, Producto $producto)
    {
        $request->validate([
            'calificacion' => 'required|integer|min:1|max:5',
            'comentario'   => 'required|string|max:2000',
        ]);

        $user = auth()->user();
        if (!$user) {
            return back()->with('error', 'Debes iniciar sesión para comentar.');
        }

        // Sincronizar registro legacy `usuarios` con el usuario logueado (users)
        $usuario = Usuario::where('email', $user->email)->first();
        if (!$usuario) {
            $usuario = Usuario::create([
                'nombre'          => trim($user->nombre . ' ' . $user->apellidos),
                'password'        => \Hash::make(\Str::random(32)),
                'email'           => $user->email,
                'modo'            => 'cliente',
                'foto'            => '',
                'verificacion'    => 1,
                'emailEncriptado' => '',
            ]);
        }

        // Un solo comentario por cliente/producto: si existe, lo actualiza
        $comentario = Comentario::where('id_usuario', $usuario->id)
            ->where('id_producto', $producto->id)
            ->first();

        if ($comentario) {
            $comentario->update([
                'calificacion' => (float) $request->calificacion,
                'comentario'   => $request->comentario,
            ]);
            $msg = 'Tu opinión fue actualizada. ¡Gracias!';
        } else {
            Comentario::create([
                'id_usuario'   => $usuario->id,
                'id_producto'  => $producto->id,
                'calificacion' => (float) $request->calificacion,
                'comentario'   => $request->comentario,
            ]);
            $msg = 'Gracias por tu opinión.';
        }

        return back()->with('success', $msg);
    }
}