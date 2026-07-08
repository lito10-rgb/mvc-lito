<?php

namespace App\Models\Exim;

use Illuminate\Database\Eloquent\Model;

class GastoLogistico extends Model
{
    protected $table = 'exim_gastos_logisticos';

    protected $fillable = [
        'nombre', 'descripcion', 'costo', 'moneda_id', 'activo',
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
