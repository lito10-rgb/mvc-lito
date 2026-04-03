<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $table = 'user_profiles';
    public $timestamps = false; // ← IMPORTANTE

    protected $fillable = [
        'user_id',
        'telefono',
        'direccion',
        'dni',
        'fecha_nacimiento'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
