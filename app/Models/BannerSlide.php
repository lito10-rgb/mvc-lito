<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerSlide extends Model
{
    protected $fillable = [
        'negocio_id', 'imagen', 'titulo', 'subtitulo',
        'boton_texto', 'boton_url', 'categoria_id', 'orden',
        'color_texto', 'color_boton_fondo', 'color_boton_texto', 'posicion',
    ];

    public function negocio()
    {
        return $this->belongsTo(Negocio::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
