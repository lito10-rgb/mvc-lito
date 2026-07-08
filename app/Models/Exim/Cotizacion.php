<?php

namespace App\Models\Exim;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'exim_cotizaciones';

    protected $fillable = [
        'codigo', 'cliente_id', 'fecha', 'validez_dias',
        'incoterm_id', 'transporte_id', 'contenedor_id',
        'moneda_id', 'tipo_cambio',
        'gastos_operativos_total', 'gastos_logisticos_total',
        'costo_pallets', 'costo_contenedor',
        'seguro_total', 'documentacion_total',
        'utilidad_porcentaje', 'utilidad_monto',
        'subtotal', 'total', 'notas', 'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date:Y-m-d',
            'tipo_cambio' => 'decimal:4',
            'gastos_operativos_total' => 'decimal:2',
            'gastos_logisticos_total' => 'decimal:2',
            'costo_pallets' => 'decimal:2',
            'costo_contenedor' => 'decimal:2',
            'seguro_total' => 'decimal:2',
            'documentacion_total' => 'decimal:2',
            'utilidad_porcentaje' => 'decimal:2',
            'utilidad_monto' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function incoterm()
    {
        return $this->belongsTo(Incoterm::class);
    }

    public function transporte()
    {
        return $this->belongsTo(Transporte::class);
    }

    public function contenedor()
    {
        return $this->belongsTo(Contenedor::class);
    }

    public function moneda()
    {
        return $this->belongsTo(Moneda::class);
    }

    public function items()
    {
        return $this->hasMany(CotizacionItem::class);
    }

    public function muestras()
    {
        return $this->hasMany(Muestra::class);
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }
}
