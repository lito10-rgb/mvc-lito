<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Mostrar formulario login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Login
    public function login(Request $request)
    {
        // Validar entrada
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        // Buscar usuario
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Usuario no encontrado']);
        }

        // 1️⃣ Intentar con bcrypt moderno
        if (Hash::check($request->password, $user->password)) {
            Auth::login($user);
            // return redirect()->route('dashboard');
            // return redirect('/');  // Redirige a la URL de inicio  Temp1234!
            return redirect()->intended('/');


        }

        // 2️⃣ Intentar con hash antiguo (crypt o texto plano)
        $oldSalt = '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$2a$07$asxx54ahjppf45sd87a5auxq/SS293XhTEeizKWMnfhnpfay0AALe';
        $oldHash = crypt($request->password, $oldSalt);

        if ($oldHash === $user->password || $request->password === $user->password) {
            // Re-hash automático a bcrypt
            $user->password = Hash::make($request->password);
            $user->save();

            Auth::login($user);
             return redirect()->intended('/'); 
            // return redirect()->route('dashboard');
        }

        return back()->withErrors(['password' => 'Contraseña incorrecta']);
    }
// Mostrar formulario de registro
// Mostrar formulario de registro
public function showRegisterForm()
{
    return view('auth.register'); // resources/views/auth/register.blade.php
}

// Registrar nuevo usuario
public function register(Request $request)
{
    // Validar campos
    $request->validate([
        'nombre' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email',
        'password' => 'required|string|min:6|confirmed', // El formulario debe tener "password" y "password_confirmation"
    ]);

    // Crear usuario con bcrypt
    $usuario = User::create([
        'nombre'          => $request->nombre,
        'email'           => $request->email,
        'password'        => Hash::make($request->password),
        'modo'            => 'manual',
        'foto'            => 'no-image.png',
        'verificacion'    => 0,
        'emailEncriptado' => encrypt($request->email),
        'fecha'           => now(),
    ]);

    // Iniciar sesión automáticamente
    Auth::login($usuario);

    return redirect('/')->with('success', 'Registro exitoso, bienvenido!');
}
////////lito actualizar
    // Mostrar formulario de edición de usuario
public function showEditForm($id)
{
    $usuario = User::findOrFail($id);
    return view('auth.register', compact('usuario')); // Puedes reutilizar la vista de register
}

// Actualizar registro existente
public function updateRegister(Request $request, $id)
{
    $usuario = User::findOrFail($id);

    // Validación
    $request->validate([
        'nombre' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $usuario->id,
        'password' => 'nullable|string|min:6|confirmed', // si no cambia, se deja vacío
    ]);

    // Actualizar campos
    $usuario->nombre = $request->nombre;
    $usuario->email  = $request->email;

    if ($request->password) {
        $usuario->password = Hash::make($request->password);
    }

    $usuario->save();
    return redirect('/')->with('success', 'Registro actualizado correctamente');
    // return redirect()->route('dashboard')->with('success', 'Registro actualizado correctamente');
}


    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
