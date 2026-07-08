<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitaTecnica extends Model
{
    protected $table = 'visitas_tecnicas';

    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'empresa',
        'fecha_visita',
        'mensaje',
        'estado',
    ];
}
