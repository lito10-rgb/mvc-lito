<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';

    protected $fillable = [
        'fecha',
        'cliente',
        'telefono',
        'correo',
        'producto',
        'descripcion',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'impuesto',
        'descuento_porcentaje',
        'descuento_monto',
        'total',
        'estado',
        'productos_json',
        'condiciones',
        'emisor_id',
        'emisor_data',
        'logo_id',
        'condicion_id',
        'cliente_id',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date:Y-m-d',
            'cantidad' => 'integer',
            'precio_unitario' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'impuesto' => 'decimal:2',
            'descuento_porcentaje' => 'decimal:2',
            'descuento_monto' => 'decimal:2',
            'total' => 'decimal:2',
            'productos_json' => 'array',
            'emisor_data' => 'array',
        ];
    }

    public function getItemsAttribute()
    {
        return $this->productos_json ?? [
            [
                'producto' => $this->producto,
                'descripcion' => $this->descripcion,
                'cantidad' => $this->cantidad,
                'precio_unitario' => $this->precio_unitario,
                'subtotal' => $this->subtotal,
            ],
        ];
    }

    public function emisor()
    {
        return $this->belongsTo(User::class, 'emisor_id');
    }

    public function logo()
    {
        return $this->belongsTo(EmpresaLogo::class, 'logo_id');
    }

    public function condicion()
    {
        return $this->belongsTo(CondicionesComerciale::class, 'condicion_id');
    }

    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }
}
