<?php

namespace App\Models\Exim;

use Illuminate\Database\Eloquent\Model;

class Seguro extends Model
{
    protected $table = 'exim_seguros';

    protected $fillable = [
        'nombre', 'porcentaje', 'costo_base', 'moneda_id', 'activo',
    ];

    protected function casts(): array
    {
        return [
            'porcentaje' => 'decimal:2',
            'costo_base' => 'decimal:2',
            'activo' => 'boolean',
        ];
    }

    public function moneda()
    {
        return $this->belongsTo(Moneda::class);
    }
}
