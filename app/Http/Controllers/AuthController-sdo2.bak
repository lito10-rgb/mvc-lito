<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $usuario = Usuario::create([
            'nombre'          => $request->nombre,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'modo'            => 'manual',
            'foto'     => 'no-image.png', // ,
            'verificacion'    => 0,
            'emailEncriptado' => encrypt($request->email),
            'fecha'           => now(),
        ]);

        Auth::login($usuario);

        // return redirect('/');
        return redirect('/')->with('success', 'Registro exitoso, bienvenido!');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }
///modificado
    // public function login(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|string',
    //     ]);

    //     $usuario = Usuario::where('email', $credentials['email'])->first();

    //     if ($usuario && Hash::check($credentials['password'], $usuario->password)) {
    //         Auth::login($usuario);
    //         // return redirect('/');
    //         return redirect('/')->with('success', 'Bienvenido de nuevo!');
    //     }

    //     return back()->withErrors([
    //         'email' => 'Credenciales incorrectas',
    //     ]);
    // }
///////////////////
    public function login(Request $request)
{
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->withErrors(['email' => 'Usuario no encontrado']);
    }

    // 1. Intentar con el hash moderno de Laravel
    if (Hash::check($request->password, $user->password)) {
        Auth::login($user);
        return redirect()->route('dashboard');
    }

    // 2. Intentar con el hash viejo (crypt)
    $oldSalt = '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$2a$07$asxx54ahjppf45sd87a5auxq/SS293XhTEeizKWMnfhnpfay0AALe';
    $oldHash = crypt($request->password, $oldSalt);

    if ($oldHash === $user->password) {

        // REHASH automático a bcrypt estándar
        $user->password = Hash::make($request->password);
        $user->save();

        Auth::login($user);
        return redirect()->route('dashboard');
    }

    return back()->withErrors(['password' => 'Contraseña incorrecta']);
}

//////////////////
    // public function logout()
    // {
    //     Auth::logout();
    //     return redirect('/');
    // }
//     public function logout(Request $request)
// {
//     $request->session()->forget('usuario');
//     $request->session()->invalidate();
//     $request->session()->regenerateToken();

//     return redirect('/');
// }
    public function logout(Request $request)
{
    Auth::logout(); // 👈 Cierra sesión

    // Invalida la sesión actual
    $request->session()->invalidate();

    // Genera un nuevo token de sesión
    $request->session()->regenerateToken();

    return redirect('/'); // Redirige a inicio o donde prefieras
}

}
