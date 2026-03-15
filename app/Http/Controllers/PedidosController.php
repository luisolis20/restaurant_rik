<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 20);
            $perPage = min($perPage, 50);
            $searchQuery = $request->input('search_query');
            $status = $request->input('status');

            $query = Pedido::select(
                'pedidos.*',
                'mesas.capacidad',
                'mesas.estado',
            )
                ->join('mesas', 'mesas.id_mesa', '=', 'pedidos.id_mesa');
            if (! empty($searchQuery)) {
                $query->where(function ($q) use ($searchQuery) {
                    $q->where('pedidos.fecha_pedido', 'LIKE', "%{$searchQuery}%");

                });
            }

            $data = $query->paginate($perPage);

            if ($data->isEmpty()) {
                return response()->json(['data' => [], 'message' => 'No se encontraron datos'], 200);
            }
            $data->getCollection()->transform(function ($item) {
                $attributes = $item->getAttributes();
                foreach ($attributes as $key => $value) {
                    if (is_string($value)) {
                        $attributes[$key] = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                    }
                }

                return $attributes;
            });

            return response()->json([
                'data' => $data->items(),
                'pagination' => [
                    'current_page' => $data->currentPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total(),
                    'last_page' => $data->lastPage(),
                ],

            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al codificar los datos a JSON: '.$e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $inputs = $request->input();
        $res = Pedido::create($inputs);

        return response()->json([
            'data' => $res,
            'mensaje' => 'Agregado con Éxito!!',
        ]);
    }

    public function getPedidopendiente(string $id)
    {
        // Usamos first() para obtener el pedido pendiente más reciente de esa mesa
        $res = Pedido::where('estado_pedido', 'pendiente')
            ->where('id_mesa', $id)
            ->orderBy('fecha_pedido', 'desc') // El más nuevo
            ->first();

        return response()->json([
            // Si no hay, enviamos null explícito para que el frontend sepa que debe crear uno
            'data' => $res,
            'mensaje' => $res ? 'Pedido pendiente encontrado' : 'No hay pedidos pendientes',
        ]);
    }
    public function getPedidococinando(string $id)
    {
        // Usamos first() para obtener el pedido pendiente más reciente de esa mesa
        $res = Pedido::select('pedidos.*', 'mesas.*', 'tiempos_preparacion.*', 'usuarios.*', 
            'detalle_pedidos.*','productos.nombre as producto_nombre','productos.id_producto')
            ->join('tiempos_preparacion', 'tiempos_preparacion.id_pedido', '=', 'pedidos.id_pedido')
            ->join('usuarios', 'usuarios.id_usuario', '=', 'tiempos_preparacion.id_usuario_chef')
            ->join('mesas', 'mesas.id_mesa', '=', 'pedidos.id_mesa')
            ->join('detalle_pedidos', 'detalle_pedidos.id_pedido', '=', 'pedidos.id_pedido')
            ->join('productos', 'productos.id_producto', '=', 'detalle_pedidos.id_producto')
            ->where('estado_pedido', 'cocinando')
            ->where('mesas.id_mesa', $id)
            ->orderBy('fecha_pedido', 'desc') // El más nuevo
            ->get();

        return response()->json([
            // Si no hay, enviamos null explícito para que el frontend sepa que debe crear uno
            'data' => $res,
            'mensaje' => $res ? 'Pedido cocinando encontrado' : 'No hay pedidos cocinando',
        ]);
    }
    public function getConsultarFacturaCLI(string $idMesa)
    {
        
        try {
            $pedidos = Pedido::with(['detalles' => function($query) {
                // Cargamos la relación del producto para tener el nombre y precio original
                $query->select('id_detalle', 'id_pedido', 'id_producto', 'cantidad', 'precio_unitario');
            }])
            ->where('id_mesa', $idMesa)
            // Filtramos estados que ya están confirmados pero no han sido pagados
            ->where('estado_pedido', '=', 'listo')
            ->orderBy('fecha_pedido', 'asc')
            ->get();

            // Transformamos un poco la respuesta para que el frontend la lea fácil
            $data = $pedidos->map(function ($pedido) {
                return [
                    'id_pedido' => $pedido->id_pedido,
                    'fecha_pedido' => $pedido->fecha_pedido,
                    'estado' => $pedido->estado_pedido,
                    'total' => number_format($pedido->total, 2, '.', ''),
                    'detalles' => $pedido->detalles->map(function ($detalle) {
                        return [
                            'id_detalle' => $detalle->id_detalle,
                            'producto_nombre' => $detalle->producto ? $detalle->producto->nombre : 'Producto no encontrado', // Asumiendo que guardas el nombre en el detalle
                            'cantidad' => $detalle->cantidad,
                            'precio_unitario' => $detalle->precio_unitario,
                            'subtotal' => number_format($detalle->cantidad * $detalle->precio_unitario, 2, '.', '')
                        ];
                    })
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $data,
                'gran_total' => number_format($data->sum('total'), 2, '.', '')
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener la cuenta: ' . $e->getMessage()
            ], 500);
        }
    }

    public function obtenerPedidosEnPreparacion()
    {
        try {
            // Obtenemos los pedidos con estado 'preparacion'
            // Opcional: ordenar por fecha para que los más antiguos aparezcan primero
            $pedidos = Pedido::select(
                'pedidos.*',
                'mesas.*',
            )
                ->where('estado_pedido', 'preparacion')
                ->join('mesas', 'mesas.id_mesa', '=', 'pedidos.id_mesa')
                ->orderBy('fecha_pedido', 'asc')
                ->get();

            if ($pedidos->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'mensaje' => 'No hay pedidos en preparación actualmente',
                    'data' => [],
                ], 200);
            }

            return response()->json([
                'status' => 'success',
                'data' => $pedidos,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'mensaje' => 'Error al obtener los pedidos',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getPedidopreparacion(string $id)
    {
        // Usamos first() para obtener el pedido pendiente más reciente de esa mesa
        $res = Pedido::where('estado_pedido', 'preparacion')
            ->where('id_mesa', $id)
            ->orderBy('fecha_pedido', 'desc') // El más nuevo
            ->first();

        return response()->json([
            // Si no hay, enviamos null explícito para que el frontend sepa que debe crear uno
            'data' => $res,
            'mensaje' => $res ? 'Pedido pendiente encontrado' : 'No hay pedidos pendientes',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $res = Pedido::find($id);
        if (isset($res)) {
            // Verificar si la imagen existe y codificarla en base64
            // $res->imagen = $res->imagen ? base64_encode($res->imagen) : null;

            return response()->json([
                'data' => $res,
                'mensaje' => 'Encontrado con Éxito!!',
            ]);
        } else {
            return response()->json([
                'error' => true,
                'mensaje' => "El Pedido con id: $id no Existe",
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $res = Pedido::find($id);
        if (isset($res)) {
            $res->id_mesa = $request->id_mesa;
            $res->fecha_pedido = $request->fecha_pedido;
            $res->estado_pedido = $request->estado_pedido;
            $res->total = $request->total;

            if ($res->save()) {
                return response()->json([
                    'data' => $res,
                    'mensaje' => 'Actualizado con Éxito!!',
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'mensaje' => 'Error al Actualizar',
                ]);
            }
        } else {
            return response()->json([
                'error' => true,
                'mensaje' => "El Pedido con id: $id no Existe",
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
}
