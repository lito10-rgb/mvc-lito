<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'subcategoria',
        'id_categoria',
        'ruta',
        'estado',
        'ofertadoPorCategoria',
        'oferta',
        'precioOferta',
        'descuentoOferta',
        'imgOferta',
        'finOferta',
        'fecha',
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class, 'subcategoria_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }
}
