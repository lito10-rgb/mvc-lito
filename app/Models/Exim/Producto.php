<?php

namespace App\Models\Exim;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'exim_productos';

    protected $fillable = [
        'tipo', 'producto_id', 'nombre', 'atributos', 'precio_base', 'moneda_id', 'estado',
    ];

    protected function casts(): array
    {
        return [
            'atributos' => 'array',
            'precio_base' => 'decimal:2',
            'estado' => 'boolean',
        ];
    }

    public function moneda()
    {
        return $this->belongsTo(Moneda::class);
    }

    public function productoLocal()
    {
        return $this->belongsTo(\App\Models\Producto::class, 'producto_id');
    }
}
