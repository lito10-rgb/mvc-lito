<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cupon extends Model
{
    protected $table = 'cupones';

    protected $fillable = [
        'codigo',
        'tipo',
        'valor',
        'min_compra',
        'max_usos',
        'usos_actuales',
        'negocio_id',
        'fecha_inicio',
        'fecha_fin',
        'activo',
    ];

    protected $casts = [
        'valor' => 'float',
        'min_compra' => 'float',
        'usos_actuales' => 'integer',
        'activo' => 'boolean',
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];

    public function negocio()
    {
        return $this->belongsTo(Negocio::class);
    }

    public function estaVigente(): bool
    {
        if (!$this->activo) return false;

        $now = now();
        if ($this->fecha_inicio && $now->lt($this->fecha_inicio)) return false;
        if ($this->fecha_fin && $now->gt($this->fecha_fin)) return false;
        if ($this->max_usos !== null && $this->usos_actuales >= $this->max_usos) return false;

        return true;
    }

    public function calcularDescuento(float $subtotal): float
    {
        if ($subtotal < $this->min_compra) return 0;

        if ($this->tipo === 'porcentaje') {
            return round($subtotal * ($this->valor / 100), 2);
        }

        return min($this->valor, $subtotal);
    }

    public function incrementarUsos(): void
    {
        $this->increment('usos_actuales');
    }
}
