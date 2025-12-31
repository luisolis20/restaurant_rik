<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
                'pedidos.id_pedido',
                'productos.nombre as nombre_producto',

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
            return response()->json(['error' => 'Error al codificar los datos a JSON: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $inputs = $request->input();
        //$inputs["password"] = md5($request->password);
        $res = DetallePedido::create($inputs);
        return response()->json([
            'data' => $res,
            'mensaje' => "Agregado con Éxito!!",
        ]);
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
                'mensaje' => "Encontrado con Éxito!!",
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
                    'mensaje' => "Actualizado con Éxito!!",
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'mensaje' => "Error al Actualizar",
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
   
    
    
}
