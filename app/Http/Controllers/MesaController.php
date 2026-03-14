<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use App\Models\QrMesa;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Para generar strings aleatorios
use SimpleSoftwareIO\QrCode\Facades\QrCode; // La librería
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MesaController extends Controller
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

            $query = Mesa::select('mesas.*','qr_mesas.codigo_qr')
            
                ->leftJoin('qr_mesas', 'qr_mesas.id_mesa', '=', 'mesas.id_mesa');
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
    public function getMesaDisponibles(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 20);
            $perPage = min($perPage, 50);
            $searchQuery = $request->input('search_query');
            $status = $request->input('status');

            $query = Mesa::select('mesas.*','qr_mesas.codigo_qr')
            
                ->leftJoin('qr_mesas', 'qr_mesas.id_mesa', '=', 'mesas.id_mesa')
                ->where('mesas.estado', '=', 'libre');
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

        $registroExistente = Mesa::where('codigo_mesa', $request->input('codigo_mesa'))->first();

        if ($registroExistente) {
            return response()->json([
                'mensaje' => "La Mesa con código: $registroExistente->codigo_mesa ya existe",
            ], 409);
        } else {

            $res = Mesa::create($request->all());

            return response()->json([
                'data' => $res,
                'mensaje' => 'Agregado con Éxito!!',
            ], 200);
        }

    }
    public function generarQr(Request $request)
    {
        $request->validate([
            'id_mesa' => 'required|exists:mesas,id_mesa'
        ]);

        try {
            DB::beginTransaction();

            // 1. Desactivar QRs anteriores de esa mesa (para seguridad)
            QrMesa::where('id_mesa', $request->id_mesa)
                  ->update(['estado' => 'inactivo']);

            // 2. Generar Token Seguro y Aleatorio
            // Usamos 64 caracteres alfanuméricos aleatorios. Imposible de adivinar.
            $tokenSeguro = Str::random(8); 
            
            // 3. Crear la URL que tendrá el QR (ej. tu dominio + menu + token)
            // Esta URL es la que el cliente escaneará
            $urlDestino = url('/menu/digital/' . $tokenSeguro);

            // 4. Guardar en Base de Datos
            $nuevoQr = QrMesa::create([
                'id_mesa' => $request->id_mesa,
                'codigo_qr' => $tokenSeguro, // Guardamos el token, no la URL completa (opcional)
                'estado' => 'activo',
                'fecha_generacion' => Carbon::now()
            ]);

            // 5. Generar la imagen del QR en formato SVG o Base64
            // Usamos generate($urlDestino)
            // base64_encode para poder enviarlo por JSON y pintarlo en Vue
            $imagenQr = base64_encode(QrCode::format('svg')->size(300)->generate($tokenSeguro));

            DB::commit();

            return response()->json([
                'mensaje' => 'QR Generado con éxito',
                'codigo_qr' => $tokenSeguro,
                'imagen_qr' => $imagenQr // Enviamos la imagen lista para mostrar
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Error al generar QR: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $res = Mesa::find($id);
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
                'mensaje' => "La Mesa con id: $id no Existe",
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $res = Mesa::find($id);
        if (isset($res)) {
            $res->codigo_mesa = $request->codigo_mesa;
            $res->capacidad = $request->capacidad;
            $res->estado = $request->estado;

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
                'mensaje' => "La Mesa con id: $id no Existe",
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $res = Mesa::find($id);
        if (isset($res)) {
            $res->estado = "ocupada";
            $res->save();
            $data = $res->toArray();
            if ($data) {

                return response()->json([
                    'data' => $data,
                    'mensaje' => "Inhabilitado con Éxito!!",
                ]);
            } else {
                return response()->json([
                    'data' => $data,
                    'mensaje' => "El usuario no existe (puede que ya la haya eliminado)",
                ]);
            }
        } else {
            return response()->json([
                'error' => true,
                'mensaje' => "El usuario con id: $id no Existe",
            ]);
        }
    }
    public function habilitar(string $id)
    {
        $res = Mesa::find($id);
        if (isset($res)) {
            $res->estado = "libre";
            $res->save();
            $data = $res->toArray();
            if ($data) {

                return response()->json([
                    'data' => $data,
                    'mensaje' => "Habilitado con Éxito!!",
                ]);
            } else {
                return response()->json([
                    'data' => $data,
                    'mensaje' => "El usuario no existe (puede que ya la haya eliminado)",
                ]);
            }
        } else {
            return response()->json([
                'error' => true,
                'mensaje' => "El usuario con id: $id no Existe",
            ]);
        }
    }
}
