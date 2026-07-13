<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserScore;
use App\Models\Pais;
use App\Models\Departamento;      // <- Añadir
use App\Models\Provincia;   // <- Añadir
use App\Models\Distrito;    // <- Añadir
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Rubro;
use App\Models\Role;

class UserAdminController extends Controller
{
    // public function index()
    // {
    //     $users = User::with(['profile', 'scores', 'roles', 'rubros'])->paginate(10);
    //     return view('admin.usuarios.index', compact('users'));
    // }
    //     public function index()
    // {
    //     $users = User::with(['profile', 'scores', 'roles', 'rubros'])
    //                 ->paginate(10);

    //     $roles  = Role::orderBy('nombre')->get();
    //     $rubros = Rubro::orderBy('nombre')->get();

    //     return view('admin.usuarios.index', compact(
    //         'users',
    //         'roles',
    //         'rubros'
    //     ));
    // }
    public function index(Request $request)
{
    $users = User::with(['profile', 'scores', 'roles', 'rubros'])

        // 🔍 nombre o apellidos
        ->when($request->nombre, function ($q) use ($request) {
            $q->where(function ($q2) use ($request) {
                $q2->where('nombre', 'like', "%{$request->nombre}%")
                   ->orWhere('apellidos', 'like', "%{$request->nombre}%")
                   ->orWhere(DB::raw("CONCAT(nombre, ' ', apellidos)"), 'like', "%{$request->nombre}%");
            });
        })

        // 🏢 empresa
        ->when($request->empresa, function ($q) use ($request) {
            $q->whereHas('profile', function ($q2) use ($request) {
                $q2->where('empresa', 'like', "%{$request->empresa}%");
            });
        })

        // 🧾 ruc
       ->when($request->ruc, function ($q) use ($request) {
            $q->whereHas('profile', function ($q2) use ($request) {
                $q2->where('tipo_documento', 'ruc')
                   ->where('num_documento', 'like', "%{$request->ruc}%");
            });
        })

        // 🎭 rol
        ->when($request->role_id, function ($q) use ($request) {
            $q->whereHas('roles', function ($q2) use ($request) {
                $q2->where('roles.id', $request->role_id);
            });
        })

        // 🧱 rubro
        ->when($request->rubro_id, function ($q) use ($request) {
            $q->whereHas('rubros', function ($q2) use ($request) {
                $q2->where('rubros.id', $request->rubro_id);
            });
        })

        // ⭐ mejor cliente por score
        ->when($request->orden == 'score', function ($q) {
            $q->orderByDesc(
                UserScore::select('score')
                    ->whereColumn('user_scores.user_id', 'users.id')
                    ->limit(1)
            );
        })

        // 🏪 negocio (origen del usuario)
        ->when($request->negocio, function ($q) use ($request) {
            $q->where('negocio', $request->negocio);
        })

        // 📅 fecha creación
        ->when($request->fecha_desde, function ($q) use ($request) {
            $q->whereDate('fecha', '>=', $request->fecha_desde);
        })
        ->when($request->fecha_hasta, function ($q) use ($request) {
            $q->whereDate('fecha', '<=', $request->fecha_hasta);
        })

        ->paginate(10)
        ->withQueryString();

    $roles  = Role::orderBy('nombre')->get();
    $rubros = Rubro::orderBy('nombre')->get();

    $dominios = User::whereNotNull('negocio')
        ->where('negocio', '!=', '')
        ->distinct()
        ->orderBy('negocio')
        ->pluck('negocio');

    return view('admin.usuarios.index', compact(
        'users',
        'roles',
        'rubros',
        'dominios'
    ));
}

    // public function create()
    // {
    //     $user = new User();
    //     return view('admin.usuarios.form', compact('user'));
    // }
public function create()
{
    $user   = new User();
    $roles  = Role::orderBy('nombre')->get();
    $rubros = Rubro::orderBy('nombre')->get();
    $paises = Pais::orderBy('nombre')->get();
    $estados    = Departamento::where('pais_id', 1)->orderBy('nombre')->get();
    $provincias = Provincia::where('departamento_id', 15)->orderBy('nombre')->get();
    $distritos  = Distrito::where('provincia_id', 128)->orderBy('nombre')->get();

    return view('admin.usuarios.create', compact(
        'user',
        'roles',
        'rubros',
        'paises',
        'estados',
        'provincias',
        'distritos',
    ));
}
    // public function store(Request $request)
    // {
    //     DB::transaction(function () use ($request) {

    //         $user = User::create([
    //             'nombre' => $request->nombre,
    //             'apellidos' => $request->apellidos,
    //             'email' => $request->email,
    //             'password' => bcrypt($request->password),
    //             'modo' => $request->modo ?? 'cliente',
    //             'verificacion' => 1,
    //             'fecha' => now(),
    //         ]);

    //         UserProfile::create([
    //             'user_id' => $user->id,
    //             'dni' => $request->dni,
    //             'telefono' => $request->telefono,
    //             'direccion' => $request->direccion,
    //         ]);

    //         UserScore::create([
    //             'user_id' => $user->id,
    //             'score' => $request->score ?? 0,
    //             'nivel' => $request->nivel ?? 'bronce',
    //         ]);
    //     });

    //     return redirect()->route('admin.usuarios.index');
    // }
public function store(Request $request)
{
    DB::transaction(function () use ($request) {

        // 1️⃣ Crear el usuario
        $user = User::create([
            'nombre'       => $request->nombre,
            'apellidos'    => $request->apellidos,
            'email'        => $request->email,
            'password'     => bcrypt($request->password),
            'modo'         => $request->modo ?? 'cliente',
            'verificacion' => 1,
            'fecha'        => now(),
        ]);

        // 2️⃣ Crear profile completo
        $user->profile()->create([
            'email'                       => $request->email,
            'empresa'                     => $request->empresa,
            'tipo_documento'              => $request->tipo_documento,
            'num_documento'               => $request->num_documento,
            'telefono'                    => $request->telefono,
            'celular'                     => $request->celular,
            'celular2'                    => $request->celular2,
            'celular3'                    => $request->celular3,
            'celular4'                    => $request->celular4,
            'whatsapp'                    => $request->whatsapp,
            'skype'                       => $request->skype,
            'wechat'                       => $request->wechat,
            'fechanacimiento'             => $request->fechanacimiento,
            'pais'                        => $request->pais,
            'estado'                      => $request->estado,
            'provincia'                   => $request->provincia,
            'distrito'                    => $request->distrito,
            'direccion'                   => $request->direccion,
            'codigopostal'                => $request->codigopostal,
            'detalle'                     => $request->detalle,
            'email2'                      => $request->email2,
            'email3'                      => $request->email3,
            'email4'                      => $request->email4,
            'web'                         => $request->web,
            'web2'                        => $request->web2,
            'facebook'                    => $request->facebook,
            'instagram'                   => $request->instagram,
            'twitter'                     => $request->twitter,
            'pinterest'                   => $request->pinterest,
            'alibaba'                      => $request->alibaba,
            'madeinchina'                 => $request->madeinchina,
            'cargo'                       => $request->cargo,
            'categoria'                   => $request->categoria,
            'subcategoria'                => $request->subcategoria,
            'tipo_usuario_vendedor_productor'=> $request->tipo_usuario_vendedor_productor,
            'codigo'                      => $request->codigo,
            'cuenta_banco'                => $request->cuenta_banco,
            'representantelegal'          => $request->representantelegal,
            'fecha_registro'              => now(),
        ]);

        // 3️⃣ Crear scores
        $user->scores()->create([
            'puntuacion'         => $request->input('scores.puntuacion', 0),
            'puntuacion_usuario' => $request->input('scores.puntuacion_usuario', 0),
            'puntuacion_precio'  => $request->input('scores.puntuacion_precio', 0),
            'condicion'          => $request->input('scores.condicion', 1),
        ]);

        // 4️⃣ Roles y Rubros
        $user->roles()->sync($request->roles ?? []);
        $user->rubros()->sync($request->rubros ?? []);
    });

    return redirect()->route('admin.usuarios.index')
                     ->with('success','Usuario creado correctamente.');
}
    // public function edit(User $user)
    // {
    //     // dd(view()->exists('admin.usuarios.form'));
    //     $user->load(['profile', 'scores']);
    //     return view('admin.usuarios.edit', compact('user'));
    // }
// public function edit($id)
// {
//     $user = User::with(['profile', 'scores'])->findOrFail($id);

//     // Blindaje 1–1
//     if (!$user->profile) {
//         $user->profile = new UserProfile();
//     }

//     if (!$user->scores) {
//         $user->scores = new UserScore();
//     }

//     return view('admin.usuarios.edit', compact('user'));
// }
//     public function edit($id)
// {
//     $user = User::with(['profile', 'scores'])->findOrFail($id);

//     // Blindaje relaciones 1–1
//     if (!$user->profile) {
//         $user->profile = new UserProfile();
//     }

//     if (!$user->scores) {
//         $user->scores = new UserScore();
//     }

//     // 👉 PAISES (para el combo)
//     $paises = Pais::orderBy('nombre')->get();
//       // 👉 RUBROS (checkbox / multiselect)
//     $rubros = Rubro::orderBy('nombre')->get();
//      $roles  = Role::orderBy('nombre')->get();

//     return view('admin.usuarios.edit', compact('user', 'paises','rubros','roles'));
// }
public function edit($id)
{
    $user = User::with(['profile', 'scores'])->findOrFail($id);

    // Blindaje relaciones 1–1
    if (!$user->profile) {
        $user->profile = new UserProfile();
    }

    if (!$user->scores) {
        $user->scores = new UserScore();
    }

    // 👉 PAISES (para el combo)
    $paises = Pais::orderBy('nombre')->get();

    // 👉 ESTADOS / DEPARTAMENTOS según país del usuario
    $estados = $user->profile->pais 
        ? Departamento::where('pais_id', $user->profile->pais)->orderBy('nombre')->get()
        : collect();
// dd($estados);

    // 👉 PROVINCIAS según estado del usuario
    $provincias = $user->profile->estado 
        ? Provincia::where('departamento_id', $user->profile->estado)->orderBy('nombre')->get()
        : collect();

    // 👉 DISTRITOS según provincia del usuario
    $distritos = $user->profile->provincia 
        ? Distrito::where('provincia_id', $user->profile->provincia)->orderBy('nombre')->get()
        : collect();

    // 👉 RUBROS (checkbox / multiselect)
    $rubros = Rubro::orderBy('nombre')->get();
    $roles  = Role::orderBy('nombre')->get();

    return view('admin.usuarios.edit', compact(
        'user', 'paises', 'estados', 'provincias', 'distritos', 'rubros', 'roles'
    ));
}


    // public function update(Request $request, $id)
    // {
    //     $user = User::findOrFail($id);
    //     DB::transaction(function () use ($request, $user) {

    //         $user->update([
    //             'nombre' => $request->nombre,
    //             'apellidos' => $request->apellidos,
    //             'email' => $request->email,
    //             'modo' => $request->modo,
    //             'password' => $request->password
    //                 ? bcrypt($request->password)
    //                 : $user->password,
    //         ]);

    //         $user->profile()->updateOrCreate(
    //             ['user_id' => $user->id],
    //             [
    //                 'dni' => $request->dni,
    //                 'telefono' => $request->telefono,
    //                 'direccion' => $request->direccion,
    //             ]
    //         );

    //         $user->scores()->updateOrCreate(
    //             ['user_id' => $user->id],
    //             [
    //                 'score' => $request->score,
    //                 'nivel' => $request->nivel,
    //             ]
    //         );
    //          // ✅ AQUÍ ESTABA EL FALTANTE
    //     $user->rubros()->sync($request->rubros ?? []);   
    //  });

    //     return redirect()->route('admin.usuarios.index');
    // }
    public function update(Request $request, $id)
{
    $user = User::findOrFail($id);
     // dd($request->all());
    DB::transaction(function () use ($request, $user) {

        // Actualiza datos principales
       // dd($request->web);
        $user->update([
            'nombre'    => $request->nombre,
            'apellidos' => $request->apellidos,
            'email'     => $request->email,
            'modo'      => $request->modo,
            'password'  => $request->password ? bcrypt($request->password) : $user->password,
            'negocio'   => $request->negocio,
        ]);

        // Actualiza todo el profile
        // dd($request->all());
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'empresa'        => $request->empresa,
                'tipo_documento' => $request->tipo_documento,
                'num_documento'  => $request->num_documento,
                'telefono'       => $request->telefono,
                'celular'        => $request->celular,
                'celular2'       => $request->celular2,
                'celular3'       => $request->celular3,
                'celular4'       => $request->celular4,
                'direccion'      => $request->direccion,
                'detalle'        => $request->detalle,
                'email2'         => $request->email2,
                'email3'         => $request->email3,
                'email4'         => $request->email4,
                'web'            => $request->web,
                'web2'           => $request->web2,
                'facebook'       => $request->facebook,
                'instagram'      => $request->instagram,
                'cargo'          => $request->cargo,
                'categoria'      => $request->categoria,
                'subcategoria'   => $request->subcategoria,
                'representantelegal' => $request->representantelegal,
                'cuenta_banco'   => $request->cuenta_banco,
                'fecha_registro' => now(),

                // <-- AÑADE ESTOS CAMPOS
                'pais'           => $request->pais,
                'estado'         => $request->estado,
                'provincia'      => $request->provincia,
                'distrito'       => $request->distrito,
                'puntaje'        => $request->puntaje,
                    ]
                );

        // Actualiza scores completos
        $user->scores()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'puntuacion'         => $request->input('scores.puntuacion', 0),
                'puntuacion_usuario' => $request->input('scores.puntuacion_usuario', 0),
                'puntuacion_precio'  => $request->input('scores.puntuacion_precio', 0),
                'condicion'          => $request->input('scores.condicion', 1),
            ]
        );

        // Roles y Rubros
        $user->roles()->sync($request->roles ?? []);
        $user->rubros()->sync($request->rubros ?? []);

    });

    return redirect()->route('admin.usuarios.index')
                     ->with('success','Usuario actualizado correctamente.');
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
    ////asignacion 
//     public function asignarView()
// {
//     $users  = User::with('profile')->get();
//     $roles  = Role::all();
//     $rubros = Rubro::all();

//     return view('admin.usuarios.asignar', compact('users','roles','rubros'));
// }
    public function asignarView()
{
    $users = User::with('profile')
        ->select('id', 'nombre', 'email')
        ->paginate(50);

    $rubros = Rubro::select('id', 'nombre')->get();
    $roles  = Role::select('id', 'nombre')->get();

    return view('admin.usuarios.asignar', compact(
        'users',
        'rubros',
        'roles'
    ));
}

public function asignarStore(Request $request)
{
    $request->validate([
        'users' => 'required|array',
        'rol_id' => 'nullable|exists:roles,id',
        'rubro_id' => 'nullable|exists:rubros,id',
    ]);

    DB::transaction(function () use ($request) {

        foreach ($request->users as $userId) {

            if ($request->rol_id) {
                DB::table('role_user')->updateOrInsert(
                    ['user_id' => $userId],
                    ['role_id' => $request->rol_id]
                );
            }

            if ($request->rubro_id) {
                DB::table('rubro_user')->updateOrInsert(
                    [
                        'user_id' => $userId,
                        'rubro_id' => $request->rubro_id
                    ],
                    []
                );
            }
        }
    });

    return back()->with('success', 'Asignación realizada');
}

public function negocioBulk(Request $request)
{
    $request->validate([
        'user_ids' => 'required|array',
        'user_ids.*' => 'exists:users,id',
    ]);

    $negocio = $request->negocio;
    if ($negocio === '__custom__') {
        $negocio = $request->negocio_custom;
    }

    if (!$negocio) {
        $negocio = null;
    }

    User::whereIn('id', $request->user_ids)->update(['negocio' => $negocio]);

    return back()->with('success', 'Negocio actualizado para ' . count($request->user_ids) . ' usuarios.');
}

    public function miPerfil()
    {
        $user = User::with(['profile', 'scores'])->findOrFail(auth()->id());

        if (!$user->profile) $user->profile = new UserProfile();
        if (!$user->scores) $user->scores = new UserScore();

        $paises = Pais::orderBy('nombre')->get();
        $estados = $user->profile->pais
            ? Departamento::where('pais_id', $user->profile->pais)->orderBy('nombre')->get()
            : collect();
        $provincias = $user->profile->estado
            ? Provincia::where('departamento_id', $user->profile->estado)->orderBy('nombre')->get()
            : collect();
        $distritos = $user->profile->provincia
            ? Distrito::where('provincia_id', $user->profile->provincia)->orderBy('nombre')->get()
            : collect();
        $rubros = Rubro::orderBy('nombre')->get();
        $roles  = Role::orderBy('nombre')->get();

        return view('admin.usuarios.edit', compact(
            'user', 'paises', 'estados', 'provincias', 'distritos', 'rubros', 'roles'
        ));
    }

    public function actualizarMiPerfil(Request $request)
    {
        $user = User::findOrFail(auth()->id());

        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->nombre = $request->nombre;
        $user->apellidos = $request->apellidos;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.mi-perfil')->with('success', 'Perfil actualizado correctamente.');
    }
}
