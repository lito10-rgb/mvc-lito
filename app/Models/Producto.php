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
        'ofertaCategoria',
        'ofertaSubcategoria',
        'oferta',
        'precioOferta',
        'descuentoOferta',
        'imgOferta',
        'finOferta',
        'etiquetaOferta',
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
        // Fecha de fin: si ya pasó alguna, la oferta caducó.
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
        // Fecha de inicio: si alguna aún no llegó, la oferta todavía no aplica.
        foreach ([$this->categoria?->fechaInicioOferta, $this->subcategoria?->fechaInicioOferta] as $inicio) {
            if (!empty($inicio) && $inicio !== '0000-00-00 00:00:00') {
                try {
                    if (\Carbon\Carbon::parse($inicio)->isFuture()) {
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
     * Si el producto define ofertaCategoria (incluido 0), gana ese valor; si no, hereda de la categoría.
     */
    public function getDescuentoCategoriaAttribute()
    {
        $override = $this->ofertaCategoria;
        if ($override !== null && $override !== '') {
            return $this->ofertaVigente ? max(0, (int) $override) : 0;
        }
        return $this->ofertaVigente ? max(0, (int) ($this->categoria?->oferta ?? 0)) : 0;
    }

    /**
     * Descuento efectivo (%) heredado de la subcategoría.
     * Si el producto define ofertaSubcategoria (incluido 0), gana ese valor; si no, hereda de la subcategoría.
     */
    public function getDescuentoSubcategoriaAttribute()
    {
        $override = $this->ofertaSubcategoria;
        if ($override !== null && $override !== '') {
            return $this->ofertaVigente ? max(0, (int) $override) : 0;
        }
        return $this->ofertaVigente ? max(0, (int) ($this->subcategoria?->oferta ?? 0)) : 0;
    }

    /**
     * Mayor descuento aplicable (%) entre Oferta individual, subcategoría y categoría.
     * (Los descuentos en soles y el Precio Oferta fijo se evalúan por separado en precioFinal.)
     */
    public function getDescuentoEfectivoAttribute()
    {
        if (! $this->ofertaVigente) {
            return 0;
        }
        return max(
            (int) $this->oferta,
            (int) $this->ofertadoPorSubCategoria,
            (int) $this->ofertadoPorCategoria,
            $this->descuentoSubcategoria,
            $this->descuentoCategoria
        );
    }

    /**
     * Etiqueta legible del descuento ganador: "10%", "S/ 2" o "Precio S/ 15".
     */
    public function getDescuentoEtiquetaAttribute()
    {
        if (! $this->enOferta) {
            return '';
        }
        $precio = (float) $this->precio;
        $final = (float) $this->precioFinal;
        if ($final >= $precio || $final <= 0) {
            return '';
        }
        if ((float) $this->precioOferta > 0 && (float) $this->precioOferta < $precio && $final == (float) $this->precioOferta) {
            return 'Precio S/ ' . number_format($final, 2);
        }
        $descuentoSoles = (float) $this->descuentoOferta;
        if ($descuentoSoles > 0 && $final == max(0, $precio - $descuentoSoles)) {
            return 'S/ ' . number_format($descuentoSoles, 2);
        }
        $pct = $this->descuentoEfectivo;
        if ($pct > 0 && $final == round($precio * (1 - $pct / 100), 2)) {
            return $pct . '%';
        }
        return '';
    }

    /**
     * Etiqueta de oferta personalizada, visible solo si hay una oferta vigente real.
     */
    public function getEtiquetaOfertaVisibleAttribute()
    {
        if (! $this->enOferta) {
            return '';
        }
        // Etiqueta propia del producto gana.
        if (!empty($this->etiquetaOferta)) {
            return (string) $this->etiquetaOferta;
        }
        // Hereda de la subcategoría si su descuento está participando.
        if ($this->descuentoSubcategoria > 0 && ($this->subcategoria->etiquetaOferta ?? '')) {
            return (string) $this->subcategoria->etiquetaOferta;
        }
        // Hereda de la categoría.
        if ($this->descuentoCategoria > 0 && ($this->categoria->etiquetaOferta ?? '')) {
            return (string) $this->categoria->etiquetaOferta;
        }
        return '';
    }

    /**
     * Fecha y hora de vencimiento más próxima (aún futura) entre producto, subcategoría y categoría.
     */
    public function getFinOfertaEfectivaAttribute()
    {
        if (! $this->enOferta) {
            return null;
        }
        $candidatos = [];
        foreach ([$this->finOferta, $this->subcategoria?->finOferta, $this->categoria?->finOferta] as $fin) {
            if (! empty($fin) && $fin !== '0000-00-00 00:00:00') {
                try {
                    $candidatos[] = \Carbon\Carbon::parse($fin);
                } catch (\Throwable $e) {
                    // fecha inválida se ignora
                }
            }
        }
        $futuros = array_filter($candidatos, fn ($c) => $c->isFuture());
        if (empty($futuros)) {
            return null;
        }
        return min($futuros);
    }

    /**
     * Precio final: gana el descuento que resulte en el menor precio.
     * Opciones: Precio Oferta fijo, descuento en soles (descuentoOferta) o % (descuentoEfectivo).
     */
    public function getPrecioFinalAttribute()
    {
        $precio = (float) $this->precio;

        if (! $this->ofertaVigente) {
            return $precio;
        }

        $candidatos = [$precio];

        if ((float) $this->precioOferta > 0) {
            $candidatos[] = (float) $this->precioOferta;
        }

        if ($precio > 0 && (float) $this->descuentoOferta > 0) {
            $candidatos[] = max(0, $precio - (float) $this->descuentoOferta);
        }

        $descuento = $this->descuentoEfectivo;
        if ($descuento > 0) {
            $candidatos[] = round($precio * (1 - $descuento / 100), 2);
        }

        return min($candidatos);
    }

    /**
     * Indica si el producto tiene un precio de oferta menor al precio normal.
     */
    public function getEnOfertaAttribute()
    {
        return $this->precioFinal < (float) $this->precio;
    }
}
