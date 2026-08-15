<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    public $timestamps = false;
    // Si tu tabla se llama 'productos' y no 'productos' en plural
    protected $table = 'productos';

    // Si deseas proteger algunos campos de la asignación masiva
    protected $fillable = [
        'tipo',
        'ruta',
        'estado',
        'titulo',
        'titular',
        'descripcion',
        'multimedia',
        'detalles',
        'precio',
        'portada',
        'vistas',
        'ventas',
        'vistasGratis',
        'ventasGratis',
        'ofertadoPorCategoria',
        'ofertadoPorSubCategoria',
        'oferta',
        'precioOferta',
        'descuentoOferta',
        'imgOferta',
        'finOferta',
        'peso',
        'entrega',
        'categoria_id',
        'subcategoria_id',
        'marca_id',
        'proveedor_id',
        'fecha',
        'stock',
        'costo_envio',
    ];

     // `id``tipo``ruta``estado``titulo``titular``descripcion``multimedia``detalles``precio``portada``vistas``ventas``vistasGratis``ventasGratis``ofertadoPorCategoria``ofertadoPorSubCategoria``oferta``precioOferta``descuentoOferta``imgOferta``finOferta``peso``entrega``categoria_id``subcategoria_id``marca_id``proveedor_id``fecha`

    // Relación inversa con categoria (un producto pertenece a una categoría)
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    // Relación inversa con subcategoria (un producto pertenece a una subcategoría)
    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class, 'subcategoria_id');
    }

    // Relación inversa con marca (un producto pertenece a una marca)
    public function marca()
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }

    // Relación inversa con proveedor (un producto pertenece a un proveedor)
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }
    public function cabecera()
    {
        return $this->hasOne(Cabecera::class, 'ruta', 'ruta');
    }

    public function negocios()
    {
        return $this->belongsToMany(Negocio::class, 'producto_negocio');
    }

    // Método para obtener titular con encoding correcto
    public function getTitularDecodedAttribute()
    {
        return html_entity_decode($this->titular, ENT_QUOTES, 'UTF-8');
    }

    // Método para obtener descripción con encoding correcto
    public function getDescripcionDecodedAttribute()
    {
        return html_entity_decode($this->descripcion, ENT_QUOTES, 'UTF-8');
    }

    // Método para obtener detalles decodificados como array
    public function getDetallesDecodedAttribute()
    {
        $detallesRaw = $this->detalles ?? '';
        $detallesRaw = html_entity_decode($detallesRaw, ENT_QUOTES, 'UTF-8');
        return json_decode($detallesRaw, true);
    }
}
