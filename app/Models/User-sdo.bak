<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    // ❗ Importante: tu tabla NO tiene created_at / updated_at
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
        'password',
        'modo',
        'foto',
        'verificacion',
        'emailEncriptado',
        'fecha', // <–– Lo agregamos
    ];

    protected $hidden = [
        'password',
        'emailEncriptado',
        'remember_token',
    ];

    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }

    public function scores()
    {
        return $this->hasOne(UserScore::class, 'user_id');
    }
}
