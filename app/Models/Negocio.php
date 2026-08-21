<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Negocio extends Model
{
    protected $fillable = [
        'nombre', 'empresa', 'dominio',
        'logo', 'logo_height', 'banner_entrada', 'banner_categoria', 'banner_subcategoria',
        'color_primary', 'color_secondary', 'color_accent', 'color_accent_light', 'color_header_bg', 'color_footer_bg',
        'color_nav_btn', 'color_nav_btn_texto', 'color_nav_texto', 'nav_tipo',
        'footer_phone', 'footer_email', 'footer_whatsapp', 'footer_address',
        'footer_facebook', 'footer_twitter', 'footer_instagram', 'footer_linkedin',
        'footer_html', 'map_lat', 'map_lng', 'map_photo',
    ];

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_negocio');
    }

    /**
     * Productos incluidos en la carta / catálogo de este negocio.
     */
    public function cartaProductos()
    {
        return $this->belongsToMany(Producto::class, 'carta_productos');
    }

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'categoria_negocio');
    }

    public function subcategorias()
    {
        return $this->belongsToMany(Subcategoria::class, 'subcategoria_negocio');
    }

    public function marcas()
    {
        return $this->belongsToMany(Marca::class, 'marca_negocio');
    }

    public function bannerSlides()
    {
        return $this->hasMany(BannerSlide::class)->with('categoria')->orderBy('orden');
    }
}
