<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ProductoController extends Controller
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
            $id_categoria = $request->input('id_categoria');

            $query = Producto::select(
                'productos.id_producto',
                'productos.nombre',
                'productos.descripcion',
                'productos.precio',
                'productos.estado',
                'productos.id_categoria',
                'categorias.nombre as categoria_nombre'
            )
                ->join('categorias', 'categorias.id_categoria', '=', 'productos.id_categoria');
            if (! empty($searchQuery)) {
                $query->where(function ($q) use ($searchQuery) {
                    $q->where('productos.nombre', 'LIKE', "%{$searchQuery}%");
                });
            }
            if ($status !== null && $status !== '') {
                $query->where('productos.estado', $status);
            }
            if ($id_categoria !== null && $id_categoria !== '') {
                $query->where('productos.id_categoria', $id_categoria);
            }


            $data = $query->paginate($perPage);

            if ($data->isEmpty()) {
                return response()->json(['data' => [], 'message' => 'No se encontraron datos'], 200);
            }
            $data->getCollection()->transform(function ($item) {
                $attributes = $item->getAttributes();
                $attributes['hasPhoto'] = true;

                // No need to unset fotografia here, as it's not selected.
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

    public function getFotografia($ci)
    {
        try {
            // 1. Obtener SÓLO la columna 'imagen' para el ID específico
            $persona = Producto::where('id_producto', $ci)
                ->select('imagen')
                ->first();

            // 2. Verificar si el producto existe y si tiene imagen
            if (! $persona || empty($persona->imagen)) {
                // Devolver una respuesta HTTP 404 (Not Found)
                return response()->json(['error' => 'Fotografía no encontrada para el ID: ' . $ci], 404);
            }

            $fotoBinaria = $persona->imagen;

            // 3. Determinar el MIME type
            $mime = 'image/jpeg'; // MIME type por defecto

            // Intenta determinar el MIME type si el ambiente lo permite
            if (extension_loaded('fileinfo')) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $detectedMime = finfo_buffer($finfo, $fotoBinaria);
                finfo_close($finfo);

                if ($detectedMime && strpos($detectedMime, 'image') === 0) {
                    $mime = $detectedMime;
                }
            }

            // 4. Devolver la imagen como una respuesta binaria (STREAM)
            return Response::make($fotoBinaria, 200)
                ->header('Content-Type', $mime)
                ->header('Content-Disposition', 'inline; filename="foto_' . $ci . '"');
        } catch (\Throwable $e) {
            // Log::error('Error en getFotografia DController: ' . $e->getMessage()); // Opcional
            return response()->json(['error' => 'Error al obtener la imagen: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $inputs = $request->input();
        if (! empty($inputs['imagen'])) {
            $inputs['imagen'] = base64_decode($inputs['imagen']);
        }

        $res = Producto::create($inputs);
        $data = $res->toArray();
        if (! empty($res->imagen)) {
            $data['imagen'] = base64_encode($res->imagen);
        }

        return response()->json([
            'data' => $data,
            'mensaje' => 'Agregado con Éxito!!',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Aplica paginación al resultado del filtro
        $data = Producto::select('productos.*')
            ->where('productos.id_producto', $id)
            ->paginate(20);
        if ($data->isEmpty()) {
            return response()->json(['error' => 'No se encontraron datos para el ID especificado'], 404);
        }

        // Convertir los campos a UTF-8 válido para cada página
        $data->getCollection()->transform(function ($item) {
            $attributes = $item->getAttributes();

            foreach ($attributes as $key => $value) {
                if (in_array($key, ['imagen']) && ! empty($value)) {
                    // ✅ Convertir BLOB a base64
                    $attributes[$key] = base64_encode($value);
                } elseif (is_string($value) && ! in_array($key, ['imagen'])) {
                    $attributes[$key] = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                }
            }

            return $attributes;
        });

        // Retornar la respuesta JSON con los metadatos de paginación
        try {
            return response()->json([
                'data' => $data->items(),
                'current_page' => $data->currentPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'last_page' => $data->lastPage(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al codificar los datos a JSON: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $res = Producto::find($id);
        if (isset($res)) {
            $res->id_categoria = $request->id_categoria;
            $res->nombre = $request->nombre;
            $res->descripcion = $request->descripcion;
            $res->precio = $request->precio;
            if (!empty($request->imagen)) {
                $res->imagen = base64_decode($request->imagen);
            }
            $res->estado = $request->estado;

            if ($res->save()) {
                $data = $res->toArray();
                if (!empty($res->imagen)) {
                    $data['imagen'] = base64_encode($res->imagen);
                }

                return response()->json([
                    'data' => $data,
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
                'mensaje' => "El Producto con id: $id no Existe",
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $res = Producto::find($id);
        if (isset($res)) {
            $res->estado = 0;
            $res->save();
            $data = $res->toArray();
            if ($data) {
                if (!empty($res->imagen)) {
                    $data['imagen'] = base64_encode($res->imagen);
                }
                return response()->json([
                    'data' => $data,
                    'mensaje' => 'Inhabilitado con Éxito!!',
                ]);
            } else {
                if (!empty($res->imagen)) {
                    $data['imagen'] = base64_encode($res->imagen);
                }
                return response()->json([
                    'data' => $data,
                    'mensaje' => 'El usuario no existe (puede que ya la haya eliminado)',
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
        $res = Producto::find($id);
        if (isset($res)) {
            $res->estado = 1;
            $res->save();
            $data = $res->toArray();
            if ($data) {
                if (!empty($res->imagen)) {
                    $data['imagen'] = base64_encode($res->imagen);
                }
                return response()->json([
                    'data' => $data,
                    'mensaje' => 'Habilitado con Éxito!!',
                ]);
            } else {
                if (!empty($res->imagen)) {
                    $data['imagen'] = base64_encode($res->imagen);
                }
                return response()->json([
                    'data' => $data,
                    'mensaje' => 'El usuario no existe (puede que ya la haya eliminado)',
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
