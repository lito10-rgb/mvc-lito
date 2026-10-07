<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = ['nombre', 'descripcion'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'permiso_role');
    }

    public function esAdmin(): bool
    {
        $nombre = strtolower($this->nombre ?? $this->name ?? '');
        return $nombre === 'admin' || $nombre === 'superadmin';
    }
}
