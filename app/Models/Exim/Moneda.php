<?php

namespace App\Models\Exim;

use Illuminate\Database\Eloquent\Model;

class Moneda extends Model
{
    protected $table = 'exim_monedas';

    protected $fillable = [
        'codigo', 'nombre', 'simbolo', 'tipo_cambio',
    ];

    protected function casts(): array
    {
        return [
            'tipo_cambio' => 'decimal:4',
        ];
    }
}
