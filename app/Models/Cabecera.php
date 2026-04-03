<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabecera extends Model
{
    /** @use HasFactory<\Database\Factories\CabeceraFactory> */
   use HasFactory;
    public $timestamps = false;
    protected $table = 'cabeceras'; // por si acaso

    protected $fillable = [
        'ruta',
        'titulo',
        'descripcion',
        'palabras_claves',
        'portada',
        'fecha',
    ];
}
