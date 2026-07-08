<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// class Marca extends Model
// {
//     use HasFactory;
//      public $timestamps = false;

//     // Especifica los campos que se pueden asignar masivamente
//     protected $fillable = [
//         'nombre',
//         'descripcion',
//         'detalle',
//         'fecha',
//         'estado',
//         'imgMarca',
//     ];

//     // Relación con productos (una marca tiene muchos productos)
//     public function productos()
//     {
//         return $this->hasMany(Producto::class, 'marca_id');
//     }

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'ruta',
        'descripcion',
        'detalle',
        'fecha',
        'estado',
        'imgMarca',
    ];

    /**
     * Obtener los productos asociados a la marca.
     */
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
