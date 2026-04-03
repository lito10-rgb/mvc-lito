<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    public $timestamps = false; // porque usas `fecha` en vez de created_at / updated_at

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'modo',
        'foto',
        'verificacion',
        'emailEncriptado',
        'fecha',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
