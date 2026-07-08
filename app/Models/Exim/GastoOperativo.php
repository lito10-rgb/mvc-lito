<?php

namespace App\Models\Exim;

use Illuminate\Database\Eloquent\Model;

class GastoOperativo extends Model
{
    protected $table = 'exim_gastos_operativos';

    protected $fillable = [
        'nombre', 'descripcion', 'costo', 'tipo_calculo', 'moneda_id', 'activo',
    ];

    protected function casts(): array
    {
        return [
            'costo' => 'decimal:2',
            'activo' => 'boolean',
        ];
    }

    public function moneda()
    {
        return $this->belongsTo(Moneda::class);
    }
}
