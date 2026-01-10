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
    public $timestamps = false;

    /**
     * La tabla no tiene created_at ni updated_at
     */
   

    /**
     * Campos asignables en masa
     */
    protected $fillable = [
        'id_producto',
        'cantidad_disponible'
    ];

    

    /**
     * Relación: el inventario pertenece a un producto
     */
     public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
     
}
