<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifaEnvio extends Model
{
    protected $table = 'tarifas_envio';

    protected $fillable = ['tipo_envio_id', 'categoria_id', 'subcategoria_id', 'minimo', 'maximo', 'costo', 'gratis', 'activo'];

    protected $casts = [
        'minimo' => 'float',
        'maximo' => 'float',
        'costo' => 'float',
        'gratis' => 'boolean',
        'activo' => 'boolean',
    ];

    public function tipoEnvio()
    {
        return $this->belongsTo(TipoEnvio::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class);
    }
}