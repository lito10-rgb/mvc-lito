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
        'nombre',
        'ruta',
        'estado',
        'oferta',
        'precioOferta',
        'descuentoOferta',
        'imgOferta',
        'finOferta',
        'etiquetaOferta',
        'fechaInicioOferta',
        'fecha',
    ];

    /**
     * Subcategorías de la categoría (propias y compartidas vía pivote).
     * La columna subcategorias.id_categoria sigue siendo el dueño primario.
     */
    public function subcategorias()
    {
        return $this->belongsToMany(Subcategoria::class, 'categoria_subcategoria');
    }

    public function productos()
    {
        return $this->hasMany(Producto::class, 'categoria_id');
    }

    public function negocios()
    {
        return $this->belongsToMany(Negocio::class, 'categoria_negocio');
    }

    public function getCategoriaAttribute($value)
    {
        return $this->attributes['nombre'] ?? $value;
    }
}