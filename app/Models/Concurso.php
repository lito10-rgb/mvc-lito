<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Concurso extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha_sorteo',
        'premio',
        'estado',
        'ganador_participante_id',
    ];

    protected function casts(): array
    {
        return [
            'fecha_sorteo' => 'date',
        ];
    }

    public function participantes()
    {
        return $this->hasMany(ConcursoParticipante::class);
    }

    public function ganador()
    {
        return $this->belongsTo(ConcursoParticipante::class, 'ganador_participante_id');
    }
}
