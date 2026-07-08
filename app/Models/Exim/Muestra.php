<?php

namespace App\Models\Exim;

use Illuminate\Database\Eloquent\Model;

class Muestra extends Model
{
    protected $table = 'exim_muestras';

    protected $fillable = [
        'producto_id', 'cotizacion_id', 'cantidad', 'peso_kg',
        'tipo_empaque', 'caja', 'etiquetas', 'certificados',
        'courier', 'seguro', 'valor_muestra', 'costo_envio',
        'costo_seguro', 'costo_total',
    ];

    protected function casts(): array
    {
        return [
            'cantidad' => 'decimal:2',
            'peso_kg' => 'decimal:2',
            'seguro' => 'decimal:2',
            'valor_muestra' => 'decimal:2',
            'costo_envio' => 'decimal:2',
            'costo_seguro' => 'decimal:2',
            'costo_total' => 'decimal:2',
        ];
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class);
    }
}
