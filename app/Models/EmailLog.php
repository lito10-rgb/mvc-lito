<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $fillable = [
        'user_id',
        'email',
        'nombre',
        'asunto',
        'contenido',
        'tipo',
        'negocio',
        'from_email',
        'from_name',
        'batch_id',
        'estado',
        'error',
        'enviado_en',
    ];

    protected $casts = [
        'enviado_en' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}