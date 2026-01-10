<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InventarioController extends Controller
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

            $query = Inventario::select(
                'inventario.*',
                'productos.nombre as productos_nombre',
                'productos.id_categoria as id_prod_cate',
                'productos.descripcion',
                'productos.estado',
                'productos.precio',
                'categorias.nombre as nombre_categoria',
                'categorias.id_categoria as id_cate',


            )
                ->join('productos', 'productos.id_producto', '=', 'inventario.id_producto')
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
        // 1. Validar que lleguen los datos necesarios
        $request->validate([
            'id_producto' => 'required',
            'cantidad_disponible' => 'required|numeric|min:1'
        ]);

        $id_producto = $request->input('id_producto');
        $nueva_cantidad = $request->input('cantidad_disponible');

        // 2. Buscar si el plato ya existe en el inventario
        $registroExistente = Inventario::where('id_producto', $id_producto)->first();

        if ($registroExistente) {
            // CASO A: El plato ya existe, sumamos la cantidad
            $registroExistente->cantidad_disponible += $nueva_cantidad;
            
            // Opcional: Si el plato estaba inactivo (estado 0), podrías activarlo aquí
            // $registroExistente->estado = 1; 

            $registroExistente->save();
            $res = $registroExistente;
            $mensaje = "¡Cantidad sumada al stock existente con éxito!";
        } else {
            // CASO B: El plato no existe, se crea normal
            $res = Inventario::create($request->all());
            $mensaje = "¡Plato registrado en inventario con éxito!";
        }

        return response()->json([
            'data' => $res,
            'mensaje' => $mensaje,
        ]);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $res = Inventario::find($id);
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
                'mensaje' => "El Inventario con id: $id no Existe",
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // 1. Buscamos el registro de inventario que queremos actualizar
        $res = Inventario::find($id);

        if (isset($res)) {
            // Obtenemos la cantidad que viene del formulario
            $cantidad_nueva = (int)$request->cantidad_disponible;
            
            // Obtenemos la cantidad que hay actualmente en la base de datos
            $cantidad_actual = (int)$res->cantidad_disponible;

            /**
             * LÓGICA DE ACTUALIZACIÓN:
             * Si el usuario en el modal de edición escribe un número, 
             * este se sumará o restará al actual.
             * Ejemplo: Si hay 10 y el usuario escribe 5 -> Resultado 15
             * Ejemplo: Si hay 10 y el usuario escribe -3 -> Resultado 7
             */
            
            $res->id_producto = $request->id_producto;
            
            // Aplicamos la operación aritmética
            $res->cantidad_disponible = $cantidad_actual + $cantidad_nueva;

            // Validación opcional: No permitir stock negativo
            if ($res->cantidad_disponible < 0) {
                return response()->json([
                    'error' => true,
                    'mensaje' => "La operación resultaría en stock negativo (" . $res->cantidad_disponible . ")",
                ]);
            }

            

            if ($res->save()) {
                return response()->json([
                    'data' => $res,
                    'mensaje' => "Stock actualizado correctamente. Nuevo total: " . $res->cantidad_disponible,
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'mensaje' => "Error al Actualizar en la base de datos",
                ]);
            }
        } else {
            return response()->json([
                'error' => true,
                'mensaje' => "El Inventario con id: $id no Existe",
            ]);
        }
    }
}
