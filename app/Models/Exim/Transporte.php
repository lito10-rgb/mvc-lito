<?php

namespace App\Models\Exim;

use Illuminate\Database\Eloquent\Model;

class Transporte extends Model
{
    protected $table = 'exim_transportes';

    protected $fillable = [
        'tipo', 'nombre', 'descripcion', 'costo_base', 'moneda_id', 'activo',
    ];

    protected function casts(): array
    {
        return [
            'costo_base' => 'decimal:2',
            'activo' => 'boolean',
        ];
    }

    public function moneda()
    {
        return $this->belongsTo(Moneda::class);
    }
}
