<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetallePedido extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla
     */
    protected $table = 'detalle_pedidos';

    /**
     * Clave primaria personalizada
     */
    protected $primaryKey = 'id_detalle';

    /**
     * La tabla no tiene created_at ni updated_at
     */
    public $timestamps = false;

    /**
     * Campos asignables en masa
     */
    protected $fillable = [
        'id_pedido',
        'id_producto',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    /**
     * Casting de atributos
     */
    protected $casts = [
        'id_detalle'     => 'integer',
        'id_pedido'      => 'integer',
        'id_producto'    => 'integer',
        'cantidad'       => 'integer',
        'precio_unitario'=> 'decimal:2',
        'subtotal'       => 'decimal:2',
    ];

    /**
     * Relación: el detalle pertenece a un pedido
     */
    public function pedido()
    {
        return $this->belongsTo(
            Pedido::class,
            'id_pedido',   // FK en detalle_pedidos
            'id_pedido'    // PK en pedidos
        );
    }

    /**
     * Relación: el detalle pertenece a un producto
     */
    public function producto()
    {
        return $this->belongsTo(
            Producto::class,
            'id_producto', // FK en detalle_pedidos
            'id_producto'  // PK en productos
        );
    }
}
