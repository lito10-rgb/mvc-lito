<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TareaContacto extends Model
{
    protected $table = 'tareas_contactos';

    protected $fillable = [
        'cuenta',
        'proveedor_email',
        'proveedor_nombre',
        'negocio_id',
        'idx',
        'asunto',
    ];
}