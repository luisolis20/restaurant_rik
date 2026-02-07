<?php

namespace App\Http\Controllers;

use App\Models\QrMesa;
use Illuminate\Http\Request;

class QrMesaController extends Controller
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

            $query = QrMesa::select(
                'qr_mesas.*',
                'mesas.*',
            )
                ->leftJoin('mesas', 'mesas.id_mesa', '=', 'qr_mesas.id_mesa');
            if (! empty($searchQuery)) {
                $query->where(function ($q) use ($searchQuery) {
                    $q->where('mesas.codigo_mesa', 'LIKE', "%{$searchQuery}%");
                });
            }
            if (! empty($status)) {
                $query->where(function ($q) use ($status) {
                    $q->where('mesas.estado', 'LIKE', "{$status}");
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
        // $inputs["password"] = md5($request->password);
        $inputs['fecha_generacion'] = now();
        $res = QrMesa::create($inputs);

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
        $res = QrMesa::find($id);
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
                'mensaje' => "El QR con id: $id no Existe",
            ]);
        }
    }

    public function verificar(string $codigo)
    {
        $registro = QrMesa::select(
            'qr_mesas.*',
            'mesas.*',
        )
            ->join('mesas', 'mesas.id_mesa', '=', 'qr_mesas.id_mesa')
            ->where('codigo_qr', $codigo)->first();

        if (! $registro) {
            return response()->json(['valido' => false, 'mensaje' => 'Código no encontrado.']);
        }

        return response()->json([
            'valido' => true,
            'data' => [
                'codigo_mesa' => $registro->codigo_mesa,
                'capacidad' => $registro->capacidad,
                'estado' => $registro->estado,
                'fecha_generacion' => $registro->fecha_generacion,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $res = QrMesa::find($id);
        if (isset($res)) {
            $res->id_mesa = $request->id_mesa;
            $res->codigo_qr = $request->codigo_qr;
            $res->estado = $request->estado;
            $res->fecha_generacion = now();

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
                'mensaje' => "El QR con id: $id no Existe",
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $res = QrMesa::find($id);
        if (isset($res)) {
            $res->estado = 0;
            $res->save();
            $data = $res->toArray();
            if ($data) {

                return response()->json([
                    'data' => $data,
                    'mensaje' => 'Inhabilitado con Éxito!!',
                ]);
            } else {
                return response()->json([
                    'data' => $data,
                    'mensaje' => 'El QR no existe (puede que ya la haya eliminado)',
                ]);
            }
        } else {
            return response()->json([
                'error' => true,
                'mensaje' => "El QR con id: $id no Existe",
            ]);
        }
    }

    public function habilitar(string $id)
    {
        $res = QrMesa::find($id);
        if (isset($res)) {
            $res->estado = 1;
            $res->save();
            $data = $res->toArray();
            if ($data) {

                return response()->json([
                    'data' => $data,
                    'mensaje' => 'Eliminado con Éxito!!',
                ]);
            } else {
                return response()->json([
                    'data' => $data,
                    'mensaje' => 'El QR no existe (puede que ya la haya eliminado)',
                ]);
            }
        } else {
            return response()->json([
                'error' => true,
                'mensaje' => "El QR con id: $id no Existe",
            ]);
        }
    }
}
