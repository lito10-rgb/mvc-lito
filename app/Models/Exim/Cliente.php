<?php

namespace App\Models\Exim;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'exim_clientes';

    protected $fillable = [
        'empresa', 'contacto', 'cargo', 'pais_id', 'ciudad', 'direccion',
        'email', 'whatsapp', 'telefono', 'idioma', 'moneda_preferida', 'user_id',
    ];

    public function pais()
    {
        return $this->belongsTo(\App\Models\Pais::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class);
    }
}
