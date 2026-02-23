<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetallePedidoController extends Controller
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

            $query = DetallePedido::select(
                'detalle_pedidos.*',
                'pedidos.total',
                'productos.nombre',

            )
                ->join('pedidos', 'detalle_pedidos.id_pedido', '=', 'pedidos.id_pedido')
                ->join('productos', 'detalle_pedidos.id_producto', '=', 'productos.id_producto');
            if (! empty($searchQuery)) {
                $query->where(function ($q) use ($searchQuery) {
                    $q->where('detalle_pedidos.cantidad', 'LIKE', "%{$searchQuery}%");

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

    public function getDetallesPedidos(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 20);
            $perPage = min($perPage, 50);
            $searchQuery = $request->input('search_query');
            $status = $request->input('status');
            $query = DetallePedido::select('detalle_pedidos.*', 'productos.nombre as producto_nombre',
                'inventario.*', 'mesas.*', 'pedidos.*', 'productos.id_producto','tiempos_preparacion.*')
                ->join('productos', 'detalle_pedidos.id_producto', '=', 'productos.id_producto')
                ->join('inventario', 'detalle_pedidos.id_producto', '=', 'inventario.id_producto')
                ->join('pedidos', 'detalle_pedidos.id_pedido', '=', 'pedidos.id_pedido')
                ->join('mesas', 'pedidos.id_mesa', '=', 'mesas.id_mesa')
                ->join('tiempos_preparacion', 'pedidos.id_pedido', '=', 'tiempos_preparacion.id_pedido');
            if ($status !== null && $status !== '') {
                $query->where('pedidos.estado_pedido', $status);
            }
            $data = $query->paginate($perPage);

            if ($data->isEmpty()) {
                return response()->json(['data' => [], 'message' => 'No se encontraron datos'], 200);
            }
            $groupedData = $data->getCollection()->transform(function ($item) {
                $attributes = $item->getAttributes();
                foreach ($attributes as $key => $value) {
                    if (is_string($value)) {
                        $attributes[$key] = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                    }
                }

                return $attributes;
            })->groupBy('id_pedido'); // <--- Agrupación aquí

            return response()->json([
                'data' => $groupedData, // Ahora los ítems vienen agrupados por su ID de pedido
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
        // $inputs["password"] = md5($request->password);
        $res = DetallePedido::create($inputs);

        return response()->json([
            'data' => $res,
            'mensaje' => 'Agregado con Éxito!!',
        ]);
    }

    public function getDetallesByPedido(string $id_pedido)
    {
        try {
            // Obtenemos los detalles filtrados por el ID del pedido
            // Cargamos la relación 'producto' para obtener el nombre y otros datos
            $detalles = DetallePedido::select('detalle_pedidos.*', 'productos.nombre as producto_nombre',
                'inventario.*')
                ->join('productos', 'detalle_pedidos.id_producto', '=', 'productos.id_producto')
                ->join('inventario', 'detalle_pedidos.id_producto', '=', 'inventario.id_producto')
                ->where('detalle_pedidos.id_pedido', $id_pedido)
                ->get();

            if ($detalles->isEmpty()) {
                return response()->json([
                    'data' => [],
                    'mensaje' => 'El pedido no tiene productos registrados.',
                ], 200);
            }

            return response()->json([
                'data' => $detalles,
                'mensaje' => 'Detalles del pedido obtenidos con éxito.',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener los detalles',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $res = DetallePedido::find($id);
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
                'mensaje' => "El Detalle del pedido con id: $id no Existe",
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $res = DetallePedido::find($id);
        if (isset($res)) {
            $res->id_pedido = $request->id_pedido;
            $res->id_producto = $request->id_producto;
            $res->cantidad = $request->cantidad;
            $res->precio_unitario = $request->precio_unitario;
            $res->subtotal = $request->subtotal;

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
                'mensaje' => "El Detalle del pedido con id: $id no Existe",
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $res = DetallePedido::find($id);
        if (isset($res)) {
            if ($res->delete()) {
                return response()->json([
                    'data' => $res,
                    'mensaje' => 'Eliminado con Éxito!!',
                ]);
            } else {
                return response()->json([
                    'data' => $res,
                    'mensaje' => 'No se pudo eliminar',
                ]);
            }
        } else {
            return response()->json([
                'error' => true,
                'mensaje' => "El pedido con id: $id no Existe",
            ]);
        }
    }

    public function vaciarCarrito($id_pedido)
    {
        DB::beginTransaction();
        try {
            // 1. Obtener todos los detalles de este pedido
            $detalles = DetallePedido::where('id_pedido', $id_pedido)->get();

            if ($detalles->isEmpty()) {
                return response()->json(['mensaje' => 'El carrito ya está vacío'], 200);
            }

            // 2. Devolver stock a cada producto antes de borrar
            foreach ($detalles as $detalle) {
                // Buscamos el inventario relacionado al producto
                $inventario = DB::table('inventario')
                    ->where('id_producto', $detalle->id_producto)
                    ->first();

                if ($inventario) {
                    DB::table('inventario')
                        ->where('id_inventario', $inventario->id_inventario)
                        ->increment('cantidad_disponible', $detalle->cantidad);
                }
            }

            // 3. Eliminar todos los detalles del pedido
            DB::table('detalle_pedidos')->where('id_pedido', $id_pedido)->delete();

            DB::commit();

            return response()->json(['mensaje' => 'Carrito vaciado y stock restaurado'], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'mensaje' => 'Error al vaciar el carrito',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
