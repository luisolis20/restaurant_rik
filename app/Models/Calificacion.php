<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Calificacion extends Model
{
    use HasFactory;

    protected $table = 'calificaciones';
    protected $primaryKey = 'id_calificacion';

    // Laravel no usará created_at / updated_at
    public $timestamps = false;

    protected $fillable = [
        'id_pedido',
        'cliente',
        'puntuacion',
        'comentario',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'puntuacion' => 'integer',
    ];

    // Relación: una calificación pertenece a un pedido
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido', 'id_pedido');
    }
}
