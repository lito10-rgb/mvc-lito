<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $user = Auth::user();

        $acceso = $user->esAdmin() || $user->permisosEfectivos()->isNotEmpty();

        if (!$acceso) {
            return redirect()->route('admin.login')->withErrors(['email' => 'No tienes permisos de administrador.']);
        }

        return $next($request);
    }
}
