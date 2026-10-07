<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permiso;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class PermisoController extends Controller
{
    public function buscarUsuarios(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $users = User::where('nombre', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->orderBy('nombre')
            ->limit(15)
            ->get(['id', 'nombre', 'email']);

        return response()->json($users->map(fn($u) => [
            'id' => $u->id,
            'nombre' => trim($u->nombre . ' ' . ($u->email ?? '')),
            'email' => $u->email,
        ]));
    }

    public function index()
    {
        $roles = Role::with('permisos')->orderBy('id')->get();
        $modulos = Permiso::orderBy('modulo')->get()->groupBy('modulo');

        return view('admin.permisos.index', compact('roles', 'modulos'));
    }

    public function editarRol(Role $role)
    {
        $permisos = Permiso::orderBy('modulo')->get();
        $modulos = $permisos->groupBy('modulo');
        $seleccionados = $role->permisos->pluck('clave')->all();

        return view('admin.permisos.rol', compact('role', 'modulos', 'seleccionados'));
    }

    public function guardarRol(Request $request, Role $role)
    {
        if ($role->esAdmin()) {
            return back()->with('success', 'El rol admin siempre tiene todos los permisos.');
        }

        $ids = $request->has('permisos')
            ? Permiso::whereIn('clave', $request->input('permisos', []))->pluck('id')
            : collect();

        $role->permisos()->sync($ids);

        return back()->with('success', "Permisos del rol '{$role->nombre}' actualizados.");
    }

    public function editarUsuario(User $user)
    {
        $permisos = Permiso::orderBy('modulo')->get();
        $modulos = $permisos->groupBy('modulo');
        $seleccionados = $user->permisos->pluck('clave')->all();

        return view('admin.permisos.usuario', compact('user', 'modulos', 'seleccionados'));
    }

    public function guardarUsuario(Request $request, User $user)
    {
        $ids = $request->has('permisos')
            ? Permiso::whereIn('clave', $request->input('permisos', []))->pluck('id')
            : collect();

        $user->permisos()->sync($ids);

        return back()->with('success', "Permisos extra de {$user->nombre} actualizados.");
    }
}