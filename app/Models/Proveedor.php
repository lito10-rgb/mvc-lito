<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;
    protected $table = 'proveedores';

    protected $fillable = [
        'nombre',
        'ruc',
        'empresa',
        'web',
        'celular',
        'telefono',
        'email',
        'email2',
        'direccion',
        'pais_id',
        'departamento_id',
        'provincia_id',
        'distrito_id',
        'facebook',
        'instagram',
        'categoria_id',
        'subcategoria_id',
        'descripcion',
        'codigo_postal',
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class, 'proveedor_id');
    }

    public function pais()
    {
        return $this->belongsTo(Pais::class, 'pais_id');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia_id');
    }

    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'distrito_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class, 'subcategoria_id');
    }
}
