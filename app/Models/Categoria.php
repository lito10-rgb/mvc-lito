<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subcategoria; // 👈 importante

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = [
        'categoria',
        'ruta',
        'estado',
        'oferta',
        'precioOferta',
        'descuentoOferta',
        'imgOferta',
        'finOferta',
        'fecha',
    ];

    // Relaciones
    // public function subcategorias()
    // {
    //     return $this->hasMany(Subcategoria::class, 'id_categoria');
    // }

    // public function productos()
    // {
    //     return $this->hasMany(Producto::class, 'categoria_id');
    // }
    public function subcategorias()
    {
        return $this->hasMany(Subcategoria::class, 'id_categoria');
    }
}