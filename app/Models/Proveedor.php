<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;
    protected $table = 'proveedores'; //  añade esto
    // Relación con productos (una marca tiene muchos productos)
     protected $fillable = [
        'nombre',
        'ruc',
        'telefono',
        'email',
        'direccion',
    ];
    public function productos()
    {
        return $this->hasMany(Producto::class, 'proveedor_id');
    }
}
