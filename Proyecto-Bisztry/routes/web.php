<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\RoleController;


// --- RUTA DE DIAGNÓSTICO ---
Route::get('/diagnostico', function () {
    phpinfo();
});
// ----------------------------

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ... (el resto de tus rutas aquí abajo)
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí es donde registramos todas las rutas para la aplicación.
|
*/

// Ruta Principal
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');


// Route::resource es un atajo increíble de Laravel que crea automáticamente
// todas las rutas necesarias para un CRUD completo (index, create, store, 
// show, edit, update, destroy). Esto nos ahorra escribir 7 rutas por cada módulo.

// Módulos con CRUD completo que hemos construido
Route::resource('clientes', ClienteController::class);
Route::resource('proveedores', ProveedorController::class);
Route::resource('productos', ProductoController::class);
Route::resource('categorias', CategoriaController::class);


// Módulos con vistas de marcador de posición (placeholder)
// Aunque no hemos construido todas sus vistas, definimos el resource
// para que los enlaces del menú funcionen y la estructura esté lista.
Route::resource('pedidos', PedidoController::class);
Route::resource('reportes', ReporteController::class)->only(['index']); // Solo necesita la ruta index por ahora
Route::resource('roles', RoleController::class)->only(['index']); // Solo necesita la ruta index por ahora


// A medida que construyas los CRUD de Pedidos, Reportes, etc., 
// puedes quitar el ->only(['index']) para activar todas las rutas.