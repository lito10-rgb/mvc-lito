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
            'nombre'   => 'required|string|max:255',
            'apellidos'=> 'nullable|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'nombre'          => $request->nombre,
            'apellidos'       => $request->apellidos,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'modo'            => 'manual',
            'foto'            => 'no-image.png',
            'verificacion'    => 0,
            'emailEncriptado' => md5($request->email),
        ]);

        Auth::login($user);
        return redirect('/')->with('success', 'Registro exitoso');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

   public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->withErrors(['email' => 'El usuario no existe']);
    }

    $passwordInput = $request->password;
    $storedHash = $user->password;

    // 1️⃣ Primero detectamos si el hash guardado es bcrypt
    $isBcrypt = str_starts_with($storedHash, '$2y$') || str_starts_with($storedHash, '$2a$');

    if ($isBcrypt) {

        // Intentar autenticación normal con bcrypt
        if (Hash::check($passwordInput, $storedHash)) {
            Auth::login($user);
            return redirect('/')->with('success', 'Bienvenido de nuevo!');
        }

        // Si es bcrypt pero no coincide
        return back()->withErrors(['password' => 'Contraseña incorrecta']);
    }

    // 2️⃣ Aquí entran los usuarios con hash antiguo / crypt / texto plano

    // Si tu sistema anterior usaba crypt con un salt:
    $oldSalt = '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$2a$07$asxx54ahjppf45sd87a5auxq/SS293XhTEeizKWMnfhnpfay0AALe';
    $oldHash = crypt($passwordInput, $oldSalt);

    if ($oldHash === $storedHash) {

        // 🔥 Re-hash inmediato al nuevo bcrypt
        $user->password = Hash::make($passwordInput);
        $user->save();

        Auth::login($user);
        return redirect('/')->with('success', 'Bienvenido!');
    }

    // 3️⃣ Si de verdad es texto plano y coincide
    if ($passwordInput === $storedHash) {

        // Re-hash y guardar bcrypt
        $user->password = Hash::make($passwordInput);
        $user->save();

        Auth::login($user);
        return redirect('/')->with('success', 'Bienvenido!');
    }

    return back()->withErrors(['password' => 'Contraseña incorrecta']);
}


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
