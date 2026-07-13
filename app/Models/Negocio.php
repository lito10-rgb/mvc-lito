<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Negocio extends Model
{
    protected $fillable = ['nombre', 'dominio'];

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_negocio');
    }
}
