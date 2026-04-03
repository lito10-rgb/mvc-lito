<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserScore extends Model
{
    use HasFactory;

    protected $table = 'user_scores';

    protected $primaryKey = 'id';

    public $timestamps = false; // tu tabla no usa created_at / updated_at

    protected $fillable = [
        'user_id',
        'puntuacion',
        'puntuacion_usuario',
        'puntuacion_precio',
        'condicion',
    ];

    protected $casts = [
        'puntuacion'          => 'integer',
        'puntuacion_usuario' => 'integer',
        'puntuacion_precio'  => 'integer',
    ];

    /* ======================
     | RELACIONES
     ====================== */

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
