<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class DbSyncConfig extends Model
{
    protected $fillable = [
        'nombre', 'host', 'puerto', 'database', 'usuario',
        'password', 'password_enc', 'activo', 'tablas_excluir', 'tablas_solo_estructura',
        'ultima_sincronizacion', 'ultimo_estado', 'ultimo_mensaje',
    ];

    protected $casts = [
        'tablas_excluir' => 'array',
        'tablas_solo_estructura' => 'array',
        'activo' => 'boolean',
        'ultima_sincronizacion' => 'datetime',
    ];

    public function logs()
    {
        return $this->hasMany(DbSyncLog::class);
    }

    public function getPasswordAttribute(): string
    {
        if (empty($this->password_enc)) {
            return '';
        }
        try {
            return Crypt::decryptString($this->password_enc);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            // Clave cambiada o datos corruptos -> devuelve vacío para no romper
            return '';
        }
    }

    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password_enc'] = Crypt::encryptString($value);
    }

    public function getConnectionConfig(): array
    {
        $password = $this->password;
        return [
            'driver' => 'mysql',
            'host' => $this->host,
            'port' => $this->puerto,
            'database' => $this->database,
            'username' => $this->usuario,
            'password' => $password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
        ];
    }
}