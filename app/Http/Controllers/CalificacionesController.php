<?php

namespace App\Http\Controllers;

use App\Models\Calificacion;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class CalificacionesController extends Controller
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

            $query = Calificacion::select(
                'calificaciones.*',
                'pedidos.fecha_pedido as fecha_pedido',
            )
                ->join('pedidos', 'pedidos.id_pedido', '=', 'calificaciones.id_pedido');
            if (! empty($searchQuery)) {
                $query->where(function ($q) use ($searchQuery) {
                    $q->where('calificaciones.fecha', 'LIKE', "%{$searchQuery}%");

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

    public function getCalificacionesRecientes()
    {
        try {
            $calificaciones = Calificacion::where('puntuacion', '>=', 4)
                ->orderBy('fecha', 'desc') // Usamos tu columna 'fecha'
                ->take(10)
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id_calificacion, // Tu PK es id_calificacion
                        'nombre_cliente' => $item->cliente ?? 'Cliente Rico Rico',
                        'comentario' => $item->comentario,
                        'puntuacion' => (int) $item->puntuacion,
                        // Convertimos el string de la DB a objeto Carbon para usar diffForHumans
                        'fecha_formateada' => $item->fecha ? Carbon::parse($item->fecha)->diffForHumans() : 'Recientemente',
                        'foto' => null, // Por ahora null según tu tabla
                    ];
                });

            return response()->json([
                'status' => 'success',
                'data' => $calificaciones,
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al cargar testimonios',
                'message' => $e->getMessage(), // Útil para debuggear
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $inputs = $request->input();
        // $inputs["password"] = md5($request->password);
        $res = Calificacion::create($inputs);

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
        $res = Calificacion::find($id);
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
                'mensaje' => "La Calificacion con id: $id no Existe",
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $res = Calificacion::find($id);
        if (isset($res)) {
            $res->id_pedido = $request->id_pedido;
            $res->puntuacion = $request->puntuacion;
            $res->comentario = $request->comentario;
            $res->fecha = $request->fecha;

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
                'mensaje' => "La Calificacion con id: $id no Existe",
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
}
