<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';
    protected $primaryKey = 'id_pedido';

    public $timestamps = false;

    protected $fillable = [
        'id_mesa',
        'fecha_pedido',
        'estado_pedido',
        'total',
    ];

    protected $casts = [
        'fecha_pedido' => 'datetime',
        'total' => 'decimal:2',
    ];

    /**
     * Un pedido pertenece a una mesa
     */
    public function mesa()
    {
        return $this->belongsTo(
            Mesa::class,
            'id_mesa',   // FK en pedidos
            'id_mesa'    // PK en mesas
        );
    }

    /**
     * Un pedido tiene una calificación
     */
    public function calificacion()
    {
        return $this->hasOne(
            Calificacion::class,
            'id_pedido',
            'id_pedido'
        );
    }
    public function detalles()
    {
        return $this->hasMany(
            DetallePedido::class,
            'id_pedido',
            'id_pedido'
        );
    }
}
