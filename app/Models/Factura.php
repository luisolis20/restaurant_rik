<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Factura extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla
     */
    protected $table = 'facturas';

    /**
     * Clave primaria personalizada
     */
    protected $primaryKey = 'id_factura';

    /**
     * La tabla no tiene created_at ni updated_at
     */
    public $timestamps = false;

    /**
     * Campos asignables en masa
     */
    protected $fillable = [
        'id_pedido',
        'numero_factura',
        'tipo_comprobante',
        'subtotal',
        'iva',
        'total',
        'estado_factura',
        'fecha_emision',
    ];

    /**
     * Casting de atributos
     */
    protected $casts = [
        'id_factura'   => 'integer',
        'id_pedido'    => 'integer',
        'subtotal'     => 'decimal:2',
        'iva'          => 'decimal:2',
        'total'        => 'decimal:2',
        'fecha_emision'=> 'datetime',
    ];

    /**
     * Constantes para ENUMs (opcional, recomendado)
     */
    const TIPO_FACTURA    = 'factura';
    const TIPO_NOTA_VENTA = 'nota_venta';

    const ESTADO_PENDIENTE  = 'pendiente';
    const ESTADO_AUTORIZADA = 'autorizada';

    /**
     * Relación: una factura pertenece a un pedido
     */
    public function pedido()
    {
        return $this->belongsTo(
            Pedido::class,
            'id_pedido',   // FK en facturas
            'id_pedido'    // PK en pedidos
        );
    }
    public function detalles()
    {
        return $this->hasMany(DetalleFactura::class, 'id_factura', 'id_factura');
    }
}
