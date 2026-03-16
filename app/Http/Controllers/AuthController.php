<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        $email = $request->input('email');
        $password = $request->input('password');
        $user = User::select('usuarios.*', 'roles.nombre_rol')
            ->join('roles', 'roles.id_rol', '=', 'usuarios.id_rol')
            ->where('email', $email)
            ->first();
        $usermesa = Mesa::select('mesas.*', 'qr_mesas.codigo_qr')
            ->join('qr_mesas', 'qr_mesas.id_mesa', '=', 'mesas.id_mesa')
            ->where('mesas.codigo_mesa', $email)
            ->first();

        if ($user) {

            if (md5($password) !== $user->password) {
                return response()->json([
                    'error' => true,
                    'mensaje' => 'Usuario correcto pero la clave es incorrecta',
                ], Response::HTTP_UNAUTHORIZED);
            }

            $token = auth()->login($user);

            return response()->json([
                'mensaje' => 'Autenticación exitosa',
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => config('jwt.ttl') * 60,
                'nombre' => $user->nombre,
                'apellido' => $user->apellido,
                'email' => $user->email,
                'rol' => $user->nombre_rol,
            ]);
        } elseif ($usermesa) {
            if ($password !== $usermesa->codigo_qr) {
                return response()->json([
                    'error' => true,
                    'mensaje' => 'Código QR incorrecto',
                ], Response::HTTP_UNAUTHORIZED);
            }
            $usermesa->update(['estado' => 'ocupada']);

            // ERROR CORREGIDO: Usamos $usermesa, no $user (que es null aquí)
            $token2 = auth()->login($usermesa);

            return response()->json([
                'mensaje' => 'Autenticación exitosa',
                'token' => $token2,
                'token_type' => 'bearer',
                'expires_in' => config('jwt.ttl') * 60,
                'codigo_mesa' => $usermesa->codigo_mesa,
                'rolme' => 'Usuario Mesa',
            ]);
        } else {

            return response()->json([
                'error' => true,
                'mensaje' => "El Usuario: $email no Existe",
            ], Response::HTTP_NOT_FOUND);
        }

    }

    public function me()
    {
        // Intentamos obtener el usuario del guard por defecto (usuarios)
        $user = auth('api')->user();

        // Si no hay usuario, intentamos obtenerlo del guard de mesas
        if (! $user) {
            $user = auth('mesa_guard')->user();
        }

        if (! $user) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        return response()->json($user);
    }

    public function logout()
    {
        // auth()->logout();
        try {
            $token = JWTAuth::getToken();
            if (! $token) {
                return response()->json(['error' => 'No hay token'], Response::HTTP_BAD_REQUEST);
            }

            // 1. Intentamos obtener el usuario de forma segura
            $user = null;
            try {
                $user = JWTAuth::authenticate($token);
            } catch (\Exception $e) {
                // Si el token falló pero podemos parsearlo, intentamos sacar el ID (sub)
                $payload = JWTAuth::setToken($token)->getPayload();
                $userId = $payload->get('sub');
                $user = \App\Models\Mesa::find($userId);
            }

            // 2. Liberar la mesa si logramos identificarla
            if ($user && $user instanceof \App\Models\Mesa) {
                $user->update(['estado' => 'libre']);
            }

            // 3. Invalidar el token
            JWTAuth::invalidate($token);

            return response()->json(['message' => 'Mesa liberada y sesión cerrada'], Response::HTTP_OK);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido'], Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo cerrar sesion'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function refresh()
    {
        try {
            $token = JWTAuth::getToken();
            if (! $token) {
                return response()->json(['error' => 'No hay token'], Response::HTTP_BAD_REQUEST);
            }
            $nuevo_token = JWTAuth::refresh();
            JWTAuth::invalidate($token);

            return $this->respondWithToken($nuevo_token);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido'], Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo refrescar sesion'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ], Response::HTTP_OK);
    }
}
