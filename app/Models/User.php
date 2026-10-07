<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Permiso;
use App\Models\Role;
use App\Models\Rubro;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = false; // ← AGREGA ESTO

    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
        'password',
        'modo',
        'foto',
        'verificacion',
        'emailEncriptado',
        'fecha',
        'negocio',
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
     public function rubros()
    {
        return $this->belongsToMany(Rubro::class);
    }
    public function favoritos()
    {
        return $this->belongsToMany(Producto::class, 'favoritos', 'user_id', 'producto_id');
    }
    public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class, 'cliente_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

        public function roles()
    {
        // return $this->belongsToMany(Role::class);
        return $this->belongsToMany(
            Role::class,
            'role_user',
            'user_id',
            'role_id'
        );

    }

    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'permiso_user');
    }

    public function esAdmin(): bool
    {
        $nombres = $this->roles()
            ->pluck('roles.nombre')
            ->map(fn($n) => strtolower($n ?? ''));

        return $nombres->contains('admin') || $nombres->contains('superadmin');
    }

    public function permisosEfectivos()
    {
        if ($this->esAdmin()) {
            return Permiso::all();
        }

        $permisoIds = $this->roles()
            ->join('permiso_role', 'permiso_role.role_id', '=', 'roles.id')
            ->pluck('permiso_role.permiso_id')
            ->merge($this->permisos()->pluck('permiso_user.permiso_id'))
            ->unique();

        return Permiso::whereIn('id', $permisoIds)->get();
    }

    public function puede(string $clave): bool
    {
        return $this->permisosEfectivos()->contains(fn($permiso) => $permiso->clave === $clave);
    }


}

