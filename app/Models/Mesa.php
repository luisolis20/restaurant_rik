<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mesa extends Model
{
    use HasFactory;

    protected $table = 'mesas';
    protected $primaryKey = 'id_mesa';

    // La tabla no tiene created_at ni updated_at
    public $timestamps = false;

    protected $fillable = [
        'codigo_mesa',
        'capacidad',
        'estado',
    ];

    protected $casts = [
        'capacidad' => 'integer',
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
}
