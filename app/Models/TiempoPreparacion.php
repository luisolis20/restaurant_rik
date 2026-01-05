<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiempoPreparacion extends Model
{
    use HasFactory;

    protected $table = 'tiempos_preparacion';
    protected $primaryKey = 'id_tiempo';
    public $timestamps = false;

    protected $fillable = [
        'id_pedido',
        'id_usuario_chef',
        'tiempo_estimado_minutos',
        'fecha_inicio',
        'fecha_fin',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    // Un tiempo de preparación pertenece a un pedido
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido', 'id_pedido');
    }

    // Un tiempo de preparación pertenece a un chef (usuario)
    public function chef()
    {
        return $this->belongsTo(User::class, 'id_usuario_chef', 'id_usuario');
    }
}
