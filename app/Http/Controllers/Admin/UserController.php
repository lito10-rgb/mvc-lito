<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['profile', 'scores'])->paginate(10);
        return view('admin.usuarios.index', compact('users'));
    }

    public function create()
    {
        $user = new User();
        return view('admin.usuarios.form', compact('user'));
    }

    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {

            $user = User::create([
                'nombre' => $request->nombre,
                'apellidos' => $request->apellidos,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'modo' => $request->modo ?? 'cliente',
                'verificacion' => 1,
                'fecha' => now(),
            ]);

            UserProfile::create([
                'user_id' => $user->id,
                'dni' => $request->dni,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
            ]);

            UserScore::create([
                'user_id' => $user->id,
                'score' => $request->score ?? 0,
                'nivel' => $request->nivel ?? 'bronce',
            ]);
        });

        return redirect()->route('admin.usuarios.index');
    }

    public function edit(User $user)
    {
        $user->load(['profile', 'scores']);
        return view('admin.usuarios.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        DB::transaction(function () use ($request, $user) {

            $user->update([
                'nombre' => $request->nombre,
                'apellidos' => $request->apellidos,
                'email' => $request->email,
                'modo' => $request->modo,
                'password' => $request->password
                    ? bcrypt($request->password)
                    : $user->password,
            ]);

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'dni' => $request->dni,
                    'telefono' => $request->telefono,
                    'direccion' => $request->direccion,
                ]
            );

            $user->scores()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'score' => $request->score,
                    'nivel' => $request->nivel,
                ]
            );
        });

        return redirect()->route('admin.usuarios.index');
    }

    public function destroy(User $user)
    {
        DB::transaction(function () use ($user) {
            $user->profile()->delete();
            $user->scores()->delete();
            $user->delete();
        });

        return back();
    }
}
