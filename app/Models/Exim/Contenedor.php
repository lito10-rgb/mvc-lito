<?php

namespace App\Models\Exim;

use Illuminate\Database\Eloquent\Model;

class Contenedor extends Model
{
    protected $table = 'exim_contenedores';

    protected $fillable = [
        'tipo', 'largo_cm', 'ancho_cm', 'alto_cm', 'capacidad_max_kg',
        'pallets_max', 'sacos_max', 'flete_maritimo', 'seguro',
        'gastos_portuarios', 'documentacion',
    ];

    protected function casts(): array
    {
        return [
            'largo_cm' => 'decimal:2',
            'ancho_cm' => 'decimal:2',
            'alto_cm' => 'decimal:2',
            'capacidad_max_kg' => 'decimal:2',
            'flete_maritimo' => 'decimal:2',
            'seguro' => 'decimal:2',
            'gastos_portuarios' => 'decimal:2',
            'documentacion' => 'decimal:2',
        ];
    }

    public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class);
    }
}
