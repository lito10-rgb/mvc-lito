<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    use HasFactory;
     // public $timestamps = false;

    // Relación con productos (una subcategoría tiene muchos productos)
    public function productos()
    {

        return $this->hasMany(Producto::class, 'subcategoria_id');

    }
    public function categoria()
{
    return $this->belongsTo(Categoria::class, 'id_categoria');
}  

}
