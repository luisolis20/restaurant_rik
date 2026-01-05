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
        'url_acceso',
        'estado',
    ];

    /**
     * Casting de atributos
     */
    protected $casts = [
        'id_qr'    => 'integer',
        'id_mesa'  => 'integer',
    ];

    /**
     * Constantes para ENUM estado (opcional, recomendado)
     */
    const ESTADO_ACTIVO   = 'activo';
    const ESTADO_INACTIVO = 'inactivo';

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
