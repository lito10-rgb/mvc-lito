<?php

use App\Models\Role;
use App\Models\Pais;
use App\Models\User;
use App\Models\UserProfile;

// ============================================================
// 1. AJUSTA AQUÍ: los datos a insertar
//    nombre -> se usa en users.nombre y user_profiles.empresa
// ============================================================
$datos = [
    ['nombre' => 'Empresa Uno', 'email' => 'contacto@uno.com',  'web' => 'www.uno.com',  'telefono' => '+56 9 1111 1111'],
    ['nombre' => 'Empresa Dos', 'email' => 'contacto@dos.com',  'web' => 'www.dos.com',  'telefono' => null],
];

// ============================================================
// 2. AJUSTA: rol (debe existir en tabla roles) y país (nombre)
// ============================================================
$nombreRol  = 'cotizante';
$nombrePais = 'Chile';
$negocio    = 'cafe-peruano.com'; // valor para users.negocio
$password   = 'password';         // se guarda hasheada con bcrypt

// ============================================================
// NO tocar de aquí en adelante
// ============================================================
$rol = Role::where('nombre', $nombreRol)->first();
if (!$rol) { echo "ERROR: rol '$nombreRol' no existe\n"; return; }

$pais = Pais::where('nombre', $nombrePais)->first();
if (!$pais) { echo "ERROR: país '$nombrePais' no existe\n"; return; }

$contador = 0;
foreach ($datos as $d) {
    $user = User::create([
        'nombre'   => $d['nombre'],
        'email'    => $d['email'],
        'modo'     => 'pagina',
        'password' => bcrypt($password),
        'negocio'  => $negocio,
    ]);

    UserProfile::create([
        'user_id'        => $user->id,
        'empresa'        => $d['nombre'],
        'email'          => $d['email'],
        'web'            => $d['web'] ?? null,
        'telefono'       => $d['telefono'] ?? null,
        'pais'           => $pais->id,
        'fecha_registro' => date('Y-m-d'),
    ]);

    $user->roles()->attach($rol->id);
    $contador++;
    echo "INSERTADO: {$d['nombre']} | id={$user->id}\n";
}

echo "\nTotal: $contador usuarios (rol=$nombreRol, pais=$nombrePais, negocio=$negocio)\n";