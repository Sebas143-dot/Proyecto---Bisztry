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
});
