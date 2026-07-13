<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CondicionesComerciale extends Model
{
    protected $table = 'condiciones_comerciales';

    protected $fillable = [
        'titulo',
        'contenido',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class, 'condicion_id');
    }
}
