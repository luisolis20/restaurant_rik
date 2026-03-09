<?php

namespace App\Http\Controllers;

use App\Models\DetalleFactura;
use App\Models\Factura;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacturaController extends Controller
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

            $query = Factura::select(
                'facturas.*',
                'pedidos.fecha_pedido as fecha_pedido',
            )
                ->join('pedidos', 'pedidos.id_pedido', '=', 'facturas.id_pedido');
            if (! empty($searchQuery)) {
                $query->where(function ($q) use ($searchQuery) {
                    $q->where('facturas.numero_factura', 'LIKE', "%{$searchQuery}%");
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
        // $inputs["password"] = md5($request->password);
        $res = Factura::create($inputs);

        return response()->json([
            'data' => $res,
            'mensaje' => 'Agregado con Éxito!!',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $res = Factura::find($id);
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
                'mensaje' => "La Factura con id: $id no Existe",
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $res = Factura::find($id);
        if (isset($res)) {
            $res->id_pedido = $request->id_pedido;
            $res->numero_factura = $request->numero_factura;
            $res->tipo_comprobante = $request->tipo_comprobante;
            $res->subtotal = $request->subtotal;
            $res->total = $request->total;
            $res->estado_factura = $request->estado_factura;
            $res->fecha_emision = $request->fecha_emision;

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
                'mensaje' => "La Factura con id: $id no Existe",
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function finalizarYFacturar(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            // 1. Cargamos el pedido con sus detalles Y el producto de cada detalle
            // Usamos el punto (.) para cargar la relación anidada
            $pedido = Pedido::with('detalles.producto')->findOrFail($id);

            if ($pedido->estado_pedido === 'listo') {
                return response()->json(['mensaje' => 'El pedido ya fue finalizado'], 400);
            }

            // 2. Actualizar estado del pedido
            $pedido->update(['estado_pedido' => 'listo']);

            // 3. Crear la factura
            $factura = Factura::create([
                'id_pedido' => $pedido->id_pedido,
                'numero_factura' => 'FAC-' . strtoupper(uniqid()),
                'tipo_comprobante' => 'factura',
                'subtotal' => $pedido->total,
                'total' => $pedido->total,
                'estado_factura' => 'pendiente',
            ]);

            // 4. Registrar detalles de factura
            foreach ($pedido->detalles as $detalle) {
                // Accedemos al nombre a través de la relación 'producto'
                // Usamos un valor por defecto ('Producto desconocido') por seguridad
                $nombreProducto = $detalle->producto ? $detalle->producto->nombre : 'Producto sin nombre';

                DetalleFactura::create([
                    'id_factura' => $factura->id_factura,
                    'descripcion' => $nombreProducto,
                    'cantidad' => $detalle->cantidad,
                    'precio_unitario' => $detalle->precio_unitario,
                    'subtotal' => $detalle->subtotal,
                ]);
            }

            DB::commit();

            return response()->json(['mensaje' => 'Pedido listo y factura generada'], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Error en servidor: ' . $e->getMessage()], 500);
        }
    }
    public function getFacturaByPedido($id_pedido)
    {
        try {
            // Buscamos la factura usando el ID del pedido
            // 'detalles' es el nombre de la relación definida en el modelo Factura
            $factura = Factura::with(['detalles', 'pedido.mesa'])
                ->where('id_pedido', $id_pedido)
                ->first();

            if (!$factura) {
                return response()->json([
                    'message' => 'No se encontró factura para el pedido #' . $id_pedido
                ], 404);
            }

            // Formateamos la respuesta para el modal de Vue
            return response()->json([
                'factura' => [
                    'id_factura'      => $factura->id_factura,
                    'id_pedido'       => $factura->id_pedido,
                    'numero_factura'  => $factura->numero_factura,
                    'tipo_comprobante' => ucfirst(str_replace('_', ' ', $factura->tipo_comprobante)),
                    'subtotal'        => number_format($factura->subtotal, 2, '.', ''),
                    'total'           => number_format($factura->total, 2, '.', ''),
                    'fecha_emision'   => $factura->fecha_emision->format('d/m/Y H:i'),
                    'estado_factura'   => $factura->estado_factura,

                    // --- Datos de la Mesa extraídos a través del pedido ---
                    'mesa' => [
                        'id_mesa'     => $factura->pedido->mesa->id_mesa ?? null,
                        'codigo_mesa' => $factura->pedido->mesa->codigo_mesa ?? 'N/A',
                        'capacidad'   => $factura->pedido->mesa->capacidad ?? 0,
                    ]
                ],
                'detalles' => $factura->detalles // Esto trae el array de detalle_facturas
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener la factura',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
