<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $isAdmin = $user->roles->contains(function ($role) {
                return strtolower($role->nombre) === 'admin' || strtolower($role->name) === 'admin';
            });
            if ($isAdmin) {
                return redirect()->route('admin.dashboard');
            }
            Auth::logout();
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            $isAdmin = $user->roles->contains(function ($role) {
                return strtolower($role->nombre) === 'admin' || strtolower($role->name) === 'admin';
            });

            if ($isAdmin) {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }

            Auth::logout();
            return back()->withErrors([
                'email' => 'No tienes permisos de administrador.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'Credenciales incorrectas.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
