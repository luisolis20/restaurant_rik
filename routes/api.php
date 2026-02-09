<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CalificacionesController;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\DetallePedidoController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\DetalleFacturaController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\QrMesaController;
use App\Http\Controllers\TiempoPreparacionController;






/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('restrik')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::apiResource("users", UserController::class)->middleware('throttle:55000,1');
    Route::apiResource("roles", RoleController::class)->middleware('throttle:55000,1');
    Route::apiResource("categorias", CategoriaController::class)->middleware('throttle:55000,1');
    Route::apiResource("productos", ProductoController::class)->middleware('throttle:55000,1');
    Route::apiResource("calificaciones", CalificacionesController::class)->middleware('throttle:55000,1');
    Route::apiResource("pedidos", PedidosController::class)->middleware('throttle:55000,1');
    Route::get('pedidospedidiente/{id}', [PedidosController::class, 'getPedidopendiente'])->middleware('throttle:55000,1');
    Route::apiResource("mesas", MesaController::class)->middleware('throttle:55000,1');
    Route::apiResource("detalle_pedidos", DetallePedidoController::class)->middleware('throttle:55000,1');
    Route::apiResource("facturas", FacturaController::class)->middleware('throttle:55000,1');
    Route::apiResource("detalle_facturas", DetalleFacturaController::class)->middleware('throttle:55000,1');
    Route::apiResource("inventarios", InventarioController::class)->middleware('throttle:55000,1');
    Route::apiResource("qr_mesas", QrMesaController::class)->middleware('throttle:55000,1');
    Route::apiResource("tiempos_preparacion", TiempoPreparacionController::class)->middleware('throttle:55000,1');
    Route::get('/detalle_pedidos/pedido/{id_pedido}', [DetallePedidoController::class, 'getDetallesByPedido']);
    Route::delete('/detalle_pedidos/vaciar/{id_pedido}', [DetallePedidoController::class, 'vaciarCarrito']);
    Route::delete('eliminarqrmesa/{id}', [QrMesaController::class, 'destroy'])->middleware('throttle:55000,1');
    Route::delete('habilitarqrmesa/{id}', [QrMesaController::class, 'habilitar'])->middleware('throttle:55000,1');
    Route::delete('eliminarmesa/{id}', [MesaController::class, 'destroy'])->middleware('throttle:55000,1');
    Route::delete('habilitarmesa/{id}', [MesaController::class, 'habilitar'])->middleware('throttle:55000,1');
    Route::post('generarqr', [MesaController::class, 'generarQr'])->middleware('throttle:55000,1');
    Route::get('verificar_qr/{codigo}', [QrMesaController::class, 'verificar'])->middleware('throttle:10000,1');
    Route::delete('eliminaruser/{id}', [UserController::class, 'destroy'])->middleware('throttle:55000,1');
    Route::delete('eliminarrol/{id}', [RoleController::class, 'destroy'])->middleware('throttle:55000,1');
    Route::delete('habilitaruser/{id}', [UserController::class, 'habilitar'])->middleware('throttle:55000,1');
    Route::delete('eliminarcategoria/{id}', [CategoriaController::class, 'destroy'])->middleware('throttle:55000,1');
    Route::get('getcategoriashabilit', [CategoriaController::class, 'getCategoriasHabilit'])->middleware('throttle:55000,1');
    Route::get('getprodhabilit', [ProductoController::class, 'getProductosHabilit'])->middleware('throttle:55000,1');
    Route::delete('habilitarcategoria/{id}', [CategoriaController::class, 'habilitar'])->middleware('throttle:55000,1');
    Route::delete('eliminarproducto/{id}', [ProductoController::class, 'destroy'])->middleware('throttle:55000,1');
    Route::delete('habilitarproducto/{id}', [ProductoController::class, 'habilitar'])->middleware('throttle:55000,1');
    Route::get('imagenprod/{ci}', [ProductoController::class, 'getFotografia'])->middleware('throttle:55000,1');
    Route::middleware('auth:api,mesa_guard')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('logout', [AuthController::class, 'logout'])->name('logout');
        //Colocar aquí las rutas que necesiten autenticación
    });
});
