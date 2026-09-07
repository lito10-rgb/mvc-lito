<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoEnvio extends Model
{
    protected $table = 'tipos_envio';

    protected $fillable = ['nombre', 'slug', 'descripcion', 'activo', 'orden'];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function tarifas()
    {
        return $this->hasMany(TarifaEnvio::class);
    }
}