<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'departamentos';
    public $timestamps = false;

    protected $fillable = ['pais_id', 'nombre'];

    public function pais()
    {
        return $this->belongsTo(Pais::class);
    }

    public function provincias()
    {
        return $this->hasMany(Provincia::class);
    }
}
