<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantillaCorreo extends Model
{
    protected $table = 'plantillas_correo';

    protected $fillable = ['nombre', 'asunto', 'contenido', 'activo'];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }
}
