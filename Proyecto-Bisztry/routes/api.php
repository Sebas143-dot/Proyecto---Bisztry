<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí es donde registramos las rutas para nuestra API. Estas rutas
| son asignadas automáticamente al grupo de middleware 'api'.
|
*/

// Agrupamos todas nuestras rutas de autenticación bajo el prefijo 'auth'
// y las enlazamos a nuestro AuthController.
// Ejemplo: la ruta 'login' será accesible en '/api/auth/login'.
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {

    // --- Rutas Públicas ---
    // Estas dos rutas no requieren un token para ser accedidas.
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // --- Rutas Protegidas ---
    // Estas rutas requieren un token JWT válido en la cabecera de la petición.
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);



    Route::post('/create-super-admin-user-a1b2c3d4e5', function (Request $request) {
    // Valida que el email no exista para no crear duplicados
    $userExists = User::where('email', $request->input('email'))->exists();
    if ($userExists) {
        return response()->json(['message' => 'Error: El usuario ya existe.'], 409);
    }

    // Crea el nuevo usuario
    $user = User::create([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'password' => Hash::make($request->input('password')),
    ]);

    return response()->json([
        'message' => '¡Usuario Superadmin creado con éxito!',
        'user' => $user
    ], 201);
});
});
