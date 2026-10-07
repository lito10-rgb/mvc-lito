<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permiso extends Model
{
    protected $table = 'permisos';

    protected $fillable = [
        'clave',
        'etiqueta',
        'modulo',
        'descripcion',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'permiso_role');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'permiso_user');
    }
}