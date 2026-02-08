<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Importante
use Tymon\JWTAuth\Contracts\JWTSubject;

class Mesa extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'mesas';

    protected $primaryKey = 'id_mesa';

    protected $keyType = 'int';

    public $incrementing = true;

    // La tabla no tiene created_at ni updated_at
    public $timestamps = false;

    protected $fillable = [
        'codigo_mesa',
        'capacidad',
        'estado',
    ];

    /**
     * Una mesa tiene muchos pedidos
     */
    public function pedidos()
    {
        return $this->hasMany(
            Pedido::class,
            'id_mesa',   // FK en pedidos
            'id_mesa'    // PK en mesas
        );
    }

    public function qrMesa()
    {
        // hasOne busca un registro en qr_mesas donde id_mesa coincida
        return $this->hasOne(QrMesa::class, 'id_mesa', 'id_mesa')
            ->where('estado', 'activo') // Opcional: solo traer el activo
            ->latest('fecha_generacion');
    }

    // Estos métodos son obligatorios para JWT
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Retorna el id_mesa
    }

    public function getJWTCustomClaims()
    {
        return [
            'role' => 'mesa', // Esto ayuda a identificar el tipo de token
        ];
    }
}
