<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'titulo',
        'cuerpo',
        'slug',
        'estado',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'post_user');
    }

    public function platforms()
    {
        return $this->belongsToMany(Platform::class, 'post_platform');
    }
}
