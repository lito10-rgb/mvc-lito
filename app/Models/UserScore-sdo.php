<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserScore extends Model
{
    protected $table = 'user_scores';
     public $timestamps = false; // ← IMPORTANTE

     protected $fillable = [
        'user_id',
        'puntuacion',
        'puntuacion_usuario',
        'puntuacion_precio',
        'condicion',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
