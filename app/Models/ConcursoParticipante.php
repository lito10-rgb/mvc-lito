<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConcursoParticipante extends Model
{
    protected $fillable = [
        'concurso_id',
        'user_id',
        'codigo',
        'email_enviado',
        'ganador',
    ];

    public function concurso()
    {
        return $this->belongsTo(Concurso::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
