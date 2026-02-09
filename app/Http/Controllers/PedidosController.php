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
