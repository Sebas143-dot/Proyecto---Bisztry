<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Crea una nueva instancia de AuthController.
     * Aplica el middleware 'jwt.auth' a todos los métodos de este controlador,
     * excepto a 'login' y 'register', que deben ser públicos.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Maneja el intento de inicio de sesión de un usuario.
     * Si las credenciales son correctas, devuelve un token JWT.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Intenta autenticar al usuario y generar un token
        if (! $token = auth('api')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Si el login es exitoso, devuelve el token
        return $this->createNewToken($token);
    }

    /**
     * Maneja el registro de un nuevo usuario.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        // Crea el nuevo usuario en la base de datos
        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => Hash::make($request->password)]
                ));

        // Devuelve un mensaje de éxito con los datos del usuario
        return response()->json([
            'message' => '¡Usuario registrado exitosamente!',
            'user' => $user
        ], 201);
    }

    /**
     * Cierra la sesión del usuario (invalida el token).
     */
    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Sesión cerrada exitosamente']);
    }

    /**
     * Refresca un token.
     */
    public function refresh()
    {
        return $this->createNewToken(auth('api')->refresh());
    }

    /**
     * Obtiene los datos del usuario autenticado.
     */
    public function userProfile()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Formatea y devuelve la respuesta del token.
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60, // Tiempo de expiración en segundos
            'user' => auth('api')->user()
        ]);
    }
}
