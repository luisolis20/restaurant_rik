<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetalleFactura extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla
     */
    protected $table = 'detalle_facturas';

    /**
     * Clave primaria personalizada
     */
    protected $primaryKey = 'id_detalle_factura';

    /**
     * La tabla no tiene created_at ni updated_at
     */
    public $timestamps = false;

    /**
     * Campos asignables en masa
     */
    protected $fillable = [
        'id_factura',
        'descripcion',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    /**
     * Casting de atributos
     */
    protected $casts = [
        'id_detalle_factura' => 'integer',
        'id_factura'         => 'integer',
        'cantidad'           => 'integer',
        'precio_unitario'    => 'decimal:2',
        'subtotal'           => 'decimal:2',
    ];

    /**
     * Relación: el detalle pertenece a una factura
     */
    public function factura()
    {
        return $this->belongsTo(
            Factura::class,
            'id_factura',   // FK en detalle_facturas
            'id_factura'    // PK en facturas
        );
    }
}
