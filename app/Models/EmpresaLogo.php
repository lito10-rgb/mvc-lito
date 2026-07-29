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
        'negocio_id',
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

    public function negocio()
    {
        return $this->belongsTo(Negocio::class);
    }
}
