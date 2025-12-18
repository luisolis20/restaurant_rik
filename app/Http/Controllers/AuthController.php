<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

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
                'error' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }
    
        $email = $request->input('email');
        $password = $request->input('password');
        $user = User::select('nombre', 'apellido', 'email', 'password', 'estado', 'id_rol')
            ->where('email', $email)
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
                'id_rol' => $user->id_rol,
            ]);
        }else{
            
            return response()->json([
                'error' => true,
                'mensaje' => "El Usuario: $email no Existe",
            ], Response::HTTP_NOT_FOUND);
        }
    
    }
   
    public function me(){
        return response()->json(auth()->user());
    }
     public function logout(){
        //auth()->logout();
        try{
            $token = JWTAuth::getToken();
            if(!$token){
                return response()->json(['error'=>'No hay token'],Response::HTTP_BAD_REQUEST);
            }
            JWTAuth::invalidate($token);
            return response()->json(['message'=>'Has cerrado sesion'],Response::HTTP_OK);
        }catch(TokenInvalidException $e){
            return response()->json(['error'=>'Token inválido'],Response::HTTP_UNAUTHORIZED);
        }catch(\Exception $e){
            return response()->json(['error'=>'No se pudo cerrar sesion'],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function refresh(){
        try{
            $token = JWTAuth::getToken();
            if(!$token){
                return response()->json(['error'=>'No hay token'],Response::HTTP_BAD_REQUEST);
            }
            $nuevo_token = JWTAuth::refresh();
            JWTAuth::invalidate($token);
            return $this->respondWithToken($nuevo_token);
        }catch(TokenInvalidException $e){
            return response()->json(['error'=>'Token inválido'],Response::HTTP_UNAUTHORIZED);
        }catch(\Exception $e){
            return response()->json(['error'=>'No se pudo refrescar sesion'],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    protected function respondWithToken($token){
        return response()->json([
            'token'=>$token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ],Response::HTTP_OK);
    }
}
