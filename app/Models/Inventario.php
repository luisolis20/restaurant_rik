<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventario extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla
     */
    protected $table = 'inventario';

    /**
     * Clave primaria personalizada
     */
    protected $primaryKey = 'id_inventario';

    /**
     * La tabla no tiene created_at ni updated_at
     */
    public $timestamps = false;

    /**
     * Campos asignables en masa
     */
    protected $fillable = [
        'id_producto',
        'cantidad_disponible',
        'fecha_actualizacion',
    ];

    /**
     * Casting de atributos
     */
    protected $casts = [
        'id_inventario'        => 'integer',
        'id_producto'          => 'integer',
        'cantidad_disponible'  => 'integer',
        'fecha_actualizacion'  => 'datetime',
    ];

    /**
     * Relación: el inventario pertenece a un producto
     */
    public function producto()
    {
        return $this->belongsTo(
            Producto::class,
            'id_producto',   // FK en inventario
            'id_producto'    // PK en productos
        );
    } 
}
