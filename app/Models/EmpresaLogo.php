<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpresaLogo extends Model
{
    protected $table = 'empresa_logos';

    protected $fillable = [
        'nombre',
        'ruta',
        'por_defecto',
    ];

    protected function casts(): array
    {
        return [
            'por_defecto' => 'boolean',
        ];
    }

    public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class, 'logo_id');
    }
}
