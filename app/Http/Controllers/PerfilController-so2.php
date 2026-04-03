<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProfile;
use App\Models\UserScore;

class PerfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $profile = UserProfile::firstOrCreate([
            'user_id' => $user->id
        ]);

        $score = UserScore::firstOrCreate([
            'user_id' => $user->id
        ], [
            'puntos' => 0,
            'nivel' => 1,
            'experiencia' => 0
        ]);

        return view('perfil.index', compact('user', 'profile', 'score'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $user->update([
            'nombre'    => $request->nombre,
            'apellidos' => $request->apellidos,
        ]);

        UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'telefono'  => $request->telefono,
                'direccion' => $request->direccion,
            ]
        );

        UserScore::updateOrCreate(
            ['user_id' => $user->id],
            [
                'puntos' => $request->puntos,
                'nivel'  => $request->nivel,
            ]
        );

        return back()->with('ok', 'Datos actualizados');
    }
}
