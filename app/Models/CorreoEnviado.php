<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CorreoEnviado extends Model
{
    protected $table = 'correos_enviados';

    protected $fillable = [
        'cotizacion_id', 'para', 'cc', 'asunto', 'contenido', 'enviado_ok', 'error',
    ];

    protected function casts(): array
    {
        return [
            'enviado_ok' => 'boolean',
        ];
    }

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class);
    }
}
