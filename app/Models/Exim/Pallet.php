<?php

namespace App\Models\Exim;

use Illuminate\Database\Eloquent\Model;

class Pallet extends Model
{
    protected $table = 'exim_pallets';

    protected $fillable = [
        'tipo', 'material', 'largo_cm', 'ancho_cm', 'alto_cm',
        'peso_kg', 'capacidad_kg', 'costo_unitario',
    ];

    protected function casts(): array
    {
        return [
            'largo_cm' => 'decimal:2',
            'ancho_cm' => 'decimal:2',
            'alto_cm' => 'decimal:2',
            'peso_kg' => 'decimal:2',
            'capacidad_kg' => 'decimal:2',
            'costo_unitario' => 'decimal:2',
        ];
    }
}
