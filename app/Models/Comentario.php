<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    /** @use HasFactory<\Database\Factories\ComentarioFactory> */
    use HasFactory;

    protected $fillable = ['id_usuario', 'id_producto', 'calificacion', 'comentario'];

    public function usuario()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'id_usuario');
    }

    public function producto()
    {
        return $this->belongsTo(\App\Models\Producto::class, 'id_producto');
    }
}
