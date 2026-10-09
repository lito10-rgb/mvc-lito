<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DbSyncLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'db_sync_config_id', 'direccion', 'fase', 'resumen',
        'detalle', 'estado', 'ejecutado_por', 'iniciado_en', 'finalizado_en',
    ];

    protected $casts = [
        'resumen' => 'array',
        'iniciado_en' => 'datetime',
        'finalizado_en' => 'datetime',
    ];

    public function config()
    {
        return $this->belongsTo(DbSyncConfig::class);
    }

    public function ejecutor()
    {
        return $this->belongsTo(User::class, 'ejecutado_por');
    }
}