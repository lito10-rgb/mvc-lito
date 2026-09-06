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

    /**
     * Negocios cuya carta / catálogo incluye este producto.
     */
    public function cartaNegocios()
    {
        return $this->belongsToMany(Negocio::class, 'carta_productos');
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

    /**
     * ¿La oferta sigue vigente? Verifica finOferta (producto, categoría o subcategoría).
     */
    public function getOfertaVigenteAttribute()
    {
        foreach ([$this->finOferta, $this->categoria?->finOferta, $this->subcategoria?->finOferta] as $fin) {
            if (!empty($fin) && $fin !== '0000-00-00 00:00:00') {
                try {
                    if (\Carbon\Carbon::parse($fin)->isPast()) {
                        return false;
                    }
                } catch (\Throwable $e) {
                    // fecha inválida se ignora
                }
            }
        }
        return true;
    }

    /**
     * Descuento efectivo (%) heredado de la categoría.
     */
    public function getDescuentoCategoriaAttribute()
    {
        return $this->ofertaVigente ? max(0, (int) ($this->categoria?->oferta ?? 0)) : 0;
    }

    /**
     * Descuento efectivo (%) heredado de la subcategoría.
     */
    public function getDescuentoSubcategoriaAttribute()
    {
        return $this->ofertaVigente ? max(0, (int) ($this->subcategoria?->oferta ?? 0)) : 0;
    }

    /**
     * Mayor descuento aplicable: Precio Oferta fijo > oferta individual (%) > subcategoría (%) > categoría (%).
     */
    public function getDescuentoEfectivoAttribute()
    {
        if (! $this->ofertaVigente) {
            return 0;
        }
        return max(
            (int) $this->oferta,
            (int) $this->descuentoOferta,
            (int) $this->ofertadoPorSubCategoria,
            (int) $this->ofertadoPorCategoria,
            $this->descuentoSubcategoria,
            $this->descuentoCategoria
        );
    }

    /**
     * Precio final: si hay Precio Oferta fijo lo usa; si no, aplica el % de descuento efectivo.
     */
    public function getPrecioFinalAttribute()
    {
        if (! $this->ofertaVigente) {
            return (float) $this->precio;
        }
        if ((float) $this->precioOferta > 0) {
            return (float) $this->precioOferta;
        }
        $descuento = $this->descuentoEfectivo;
        if ($descuento > 0) {
            return round((float) $this->precio * (1 - $descuento / 100), 2);
        }
        return (float) $this->precio;
    }

    /**
     * Indica si el producto tiene un precio de oferta menor al precio normal.
     */
    public function getEnOfertaAttribute()
    {
        return $this->precioFinal < (float) $this->precio;
    }
}
