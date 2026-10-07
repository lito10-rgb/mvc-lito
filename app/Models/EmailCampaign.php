<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailCampaign extends Model
{
    protected $fillable = [
        'nombre',
        'tipo',
        'negocio',
        'batch_id',
        'total_enviados',
        'total_fallidos',
        'notas',
        'creado_por',
        'enviado_en',
    ];

    protected $casts = [
        'enviado_en' => 'datetime',
        'total_enviados' => 'integer',
        'total_fallidos' => 'integer',
    ];

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function logs()
    {
        return $this->hasMany(EmailLog::class, 'batch_id', 'batch_id');
    }
}