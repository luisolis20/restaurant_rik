<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QrMesa extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla
     */
    protected $table = 'qr_mesas';

    /**
     * Clave primaria personalizada
     */
    protected $primaryKey = 'id_qr';

    /**
     * La tabla no tiene created_at ni updated_at
     */
    public $timestamps = false;

    /**
     * Campos asignables en masa
     */
    protected $fillable = [
        'id_mesa',
        'codigo_qr',
        'estado',
        'fecha_generacion',
    ];

    

    /**
     * Relación: el QR pertenece a una mesa
     */
    public function mesa()
    {
        return $this->belongsTo(
            Mesa::class,
            'id_mesa',   // FK en qr_mesas
            'id_mesa'    // PK en mesas
        );
    }
}
