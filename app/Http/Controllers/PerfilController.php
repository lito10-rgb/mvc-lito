<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserProfile;
////lito
use App\Models\Pais;
use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Distrito;
use App\Models\Categoria;
use App\Models\Subcategoria;
// use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{
    // public function index()
    // {
    //     $user = Auth::user();

    //     $perfil = UserProfile::firstOrCreate(['user_id' => $user->id]);

    //     // Datos para selects encadenados
    //     $paises = \DB::table('paises')->get();
    //     $departamentos = \DB::table('departamentos')->get();
    //     $provincias = \DB::table('provincias')->get();
    //     $distritos = \DB::table('distritos')->get();

    //     return view('perfil.index', compact(
    //         'user',
    //         'perfil',
    //         'paises',
    //         'departamentos',
    //         'provincias',
    //         'distritos'
    //     ));
    // }
   public function index()
{
    $user = auth()->user();

    // Crear perfil si no existe
    $perfil = UserProfile::firstOrCreate(['user_id' => $user->id]);

    // Datos para combos
    $paises = Pais::orderBy('nombre')->get();
    $departamentos = Departamento::orderBy('nombre')->get();
    $provincias = Provincia::orderBy('nombre')->get();
    $distritos = Distrito::orderBy('nombre')->get();

    $categorias = Categoria::orderBy('nombre')->get();
    $subcategorias = Subcategoria::orderBy('subcategoria')->get();

    return view('perfil.index', compact(
        'user',
        'perfil',
        'paises',
        'departamentos',
        'provincias',
        'distritos',
        'categorias',
        'subcategorias'
    ));
}


    public function update(Request $request)
    {
        $user = Auth::user();

        // Validación simple
        $request->validate([
            'email' => 'required|email',
            'pais' => 'required|integer',
            'estado' => 'required|integer',
            'provincia' => 'required|integer',
            'distrito' => 'required|integer'
        ]);

        // Actualizar email del usuario
        $user->email = $request->email;
        $user->save();

        // Si cambia contraseña
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        // Actualizar perfil
        $perfil = UserProfile::firstOrCreate(['user_id' => $user->id]);

        $perfil->email = $request->email;
        $perfil->empresa = $request->empresa;
        $perfil->tipo_documento = $request->tipo_documento;
        $perfil->num_documento = $request->num_documento;
        $perfil->telefono = $request->telefono;
        $perfil->celular = $request->celular;
        $perfil->whatsapp = $request->whatsapp;
        $perfil->skype = $request->skype;

        $perfil->pais = $request->pais;
        $perfil->estado = $request->estado;
        $perfil->provincia = $request->provincia;
        $perfil->distrito = $request->distrito;

        $perfil->direccion = $request->direccion;
        $perfil->codigopostal = $request->codigopostal;
        $perfil->detalle = $request->detalle;

        $perfil->web = $request->web;
        $perfil->facebook = $request->facebook;
        $perfil->instagram = $request->instagram;
        $perfil->twitter = $request->twitter;
        $perfil->pinterest = $request->pinterest;

        $perfil->cargo = $request->cargo;
        $perfil->categoria = $request->categoria;
        $perfil->subcategoria = $request->subcategoria;

        $perfil->save();

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}
