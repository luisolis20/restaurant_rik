<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;

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
    Route::apiResource("users", UserController::class);
    Route::apiResource("roles", RoleController::class);
    Route::delete('eliminaruser/{id}', [UserController::class, 'destroy']);
    Route::delete('habilitaruser/{id}', [UserController::class, 'habilitar']);
    Route::middleware('auth:api')->group(function () {
        //Colocar aquí las rutas que necesiten autenticación
    });
});
