<?php

namespace App\Models\Exim;

use Illuminate\Database\Eloquent\Model;

class Incoterm extends Model
{
    protected $table = 'exim_incoterms';

    protected $fillable = [
        'codigo', 'nombre', 'descripcion',
        'incluye_transporte_interno', 'incluye_flete_maritimo', 'incluye_seguro',
        'incluye_aduanas_origen', 'incluye_aduanas_destino', 'incluye_transporte_destino',
    ];

    protected function casts(): array
    {
        return [
            'incluye_transporte_interno' => 'boolean',
            'incluye_flete_maritimo' => 'boolean',
            'incluye_seguro' => 'boolean',
            'incluye_aduanas_origen' => 'boolean',
            'incluye_aduanas_destino' => 'boolean',
            'incluye_transporte_destino' => 'boolean',
        ];
    }

    public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class);
    }
}
