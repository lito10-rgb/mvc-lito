<?php

namespace App\Models\Exim;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $table = 'exim_documentos';

    protected $fillable = [
        'cotizacion_id', 'tipo', 'numero_documento', 'archivo',
    ];

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class);
    }
}
