<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = 'user_profiles';

    protected $primaryKey = 'id';

    public $timestamps = false; // tu tabla no usa created_at / updated_at

    protected $fillable = [
        'user_id',

        // contacto / identidad
        'email',
        'email2',
        'email3',
        'email4',

        'empresa',
        'cargo',

        'tipo_documento',
        'num_documento',

        // teléfonos
        'telefono',
        'celular',
        'celular2',
        'celular3',
        'celular4',
        'whatsapp',

        // mensajería
        'skype',
        'wechat',

        // datos personales
        'fechanacimiento',

        // ubicación
        'pais',
        'estado',
        'provincia',
        'distrito',
        'direccion',
        'codigopostal',

        // info adicional
        'detalle',

        // web / redes
        'web',
        'web2',
        'facebook',
        'instagram',
        'twitter',
        'pinterest',

        // plataformas B2B
        'alibaba',
        'madeinchina',

        // clasificación
        'categoria',
        'subcategoria',
        'tipo_usuario_vendedor_productor',

        // otros
        'codigo',
        'cuenta_banco',
        'representantelegal',
        'fecha_registro',
    ];

    protected $casts = [
        'fechanacimiento' => 'date',
        'fecha_registro'  => 'datetime',
    ];

    /* ======================
     | RELACIONES
     ====================== */

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
