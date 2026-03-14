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
                'mesas.*',
                'pedidos.fecha_pedido as fecha_pedido',
            )
                ->join('pedidos', 'pedidos.id_pedido', '=', 'facturas.id_pedido')
                ->join('mesas', 'mesas.id_mesa', '=', 'pedidos.id_mesa');
            if (! empty($searchQuery)) {
                $query->where(function ($q) use ($searchQuery) {
                    $q->where('mesas.codigo_mesa', 'LIKE', "%{$searchQuery}%");
                });
            }
            if (! empty($status)) {
                $query->where('facturas.estado_factura', $status);
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
    public function getDashboardStats()
    {
        try {
            // Total de ventas: Suma de facturas pagadas
            $totalVentas = Factura::where('estado_factura', 'pagada')
                ->sum('total');

            // Total de pedidos: Conteo de todos los pedidos (excepto cancelados si prefieres)
            $totalPedidos = Pedido::where('estado_pedido', '!=', 'cancelado')
                ->count();

            return response()->json([
                'total_ventas' => (float) $totalVentas,
                'total_pedidos' => $totalPedidos
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getMonthlySales(Request $request)
    {
        $year = $request->input('year', date('Y'));

        // 1. Obtener años disponibles para el dropdown
        $availableYears = Factura::selectRaw('YEAR(fecha_emision) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // 2. Obtener ventas por mes del año seleccionado
        $sales = Factura::where('estado_factura', 'pagada')
            ->whereYear('fecha_emision', $year)
            ->selectRaw('MONTH(fecha_emision) as month, SUM(total) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // 3. Preparar array de 12 meses inicializado en 0
        $monthlyData = array_fill(0, 12, 0);
        foreach ($sales as $sale) {
            $monthlyData[$sale->month - 1] = (float) $sale->total;
        }

        return response()->json([
            'years' => $availableYears,
            'selected_year' => (int)$year,
            'sales_data' => $monthlyData
        ]);
    }
    public function getMonthlyTarget()
    {
        $target = 1000; // Objetivo mensual
        $startOfMonth = now()->startOfMonth()->toDateTimeString();
        $todayStart = now()->startOfDay()->toDateTimeString();

        // Ingresos del mes actual
        $revenueMonth = Factura::where('estado_factura', 'pagada')
            ->where('fecha_emision', '>=', $startOfMonth)
            ->sum('total');

        // Ingresos de hoy
        $revenueToday = Factura::where('estado_factura', 'pagada')
            ->where('fecha_emision', '>=', $todayStart)
            ->sum('total');

        // Cálculo del porcentaje (máximo 100%)
        $rawPercentage = ($target > 0) ? ($revenueMonth / $target) * 100 : 0;
        $percentage = min($rawPercentage, 100);

        return response()->json([
            'target' => $target,
            'revenue_month' => (float)$revenueMonth,
            'revenue_today' => (float)$revenueToday,
            'percentage' => round($percentage, 2),
            'raw_percentage' => round($rawPercentage, 2)
        ]);
    }
    public function getStatistics()
    {
        $year = now()->year;

        // Obtener ventas mensuales (Suma de totales)
        $salesData = Factura::where('estado_factura', 'pagada')
            ->whereYear('fecha_emision', $year)
            ->selectRaw('MONTH(fecha_emision) as month, SUM(total) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Obtener pedidos mensuales (Conteo de registros)
        $ordersData = Pedido::where('estado_pedido', '!=', 'cancelado')
            ->whereYear('fecha_pedido', $year)
            ->selectRaw('MONTH(fecha_pedido) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Llenar los 12 meses (asegurar que haya datos para cada mes)
        $sales = [];
        $orders = [];

        for ($i = 1; $i <= 12; $i++) {
            $sales[] = $salesData[$i] ?? 0;
            $orders[] = $ordersData[$i] ?? 0;
        }

        return response()->json([
            'sales' => $sales,
            'orders' => $orders
        ]);
    }
    public function getStatistics2(Request $request)
    {
        $filter = $request->input('filter', 'monthly'); // monthly, quarterly, annually
        $year = now()->year;

        $querySales = Factura::where('estado_factura', 'pagada')->whereYear('fecha_emision', $year);
        $queryOrders = Pedido::where('estado_pedido', '!=', 'cancelado')->whereYear('fecha_pedido', $year);

        if ($filter === 'quarterly') {
            // Agrupar por Trimestre (1, 2, 3, 4)
            $sales = $querySales->selectRaw('QUARTER(fecha_emision) as period, SUM(total) as total')
                ->groupBy('period')->pluck('total', 'period')->toArray();
            $orders = $queryOrders->selectRaw('QUARTER(fecha_pedido) as period, COUNT(*) as count')
                ->groupBy('period')->pluck('count', 'period')->toArray();

            $limit = 4;
            $categories = ['T1', 'T2', 'T3', 'T4'];
        } elseif ($filter === 'annually') {
            // Mostrar últimos 5 años
            $sales = Factura::where('estado_factura', 'pagada')
                ->selectRaw('YEAR(fecha_emision) as period, SUM(total) as total')
                ->groupBy('period')->orderBy('period', 'desc')->take(5)->pluck('total', 'period')->toArray();
            $orders = Pedido::where('estado_pedido', '!=', 'cancelado')
                ->selectRaw('YEAR(fecha_pedido) as period, COUNT(*) as count')
                ->groupBy('period')->orderBy('period', 'desc')->take(5)->pluck('count', 'period')->toArray();

            ksort($sales);
            ksort($orders);
            return response()->json([
                'sales' => array_values($sales),
                'orders' => array_values($orders),
                'categories' => array_map('strval', array_keys($sales))
            ]);
        } else {
            // Mensual (Por defecto)
            $sales = $querySales->selectRaw('MONTH(fecha_emision) as period, SUM(total) as total')
                ->groupBy('period')->pluck('total', 'period')->toArray();
            $orders = $queryOrders->selectRaw('MONTH(fecha_pedido) as period, COUNT(*) as count')
                ->groupBy('period')->pluck('count', 'period')->toArray();

            $limit = 12;
            $categories = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        }

        $finalSales = [];
        $finalOrders = [];
        for ($i = 1; $i <= $limit; $i++) {
            $finalSales[] = $sales[$i] ?? 0;
            $finalOrders[] = $orders[$i] ?? 0;
        }

        return response()->json([
            'sales' => $finalSales,
            'orders' => $finalOrders,
            'categories' => $categories
        ]);
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
