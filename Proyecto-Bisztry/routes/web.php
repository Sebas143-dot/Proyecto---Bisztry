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
use App\Http\Controllers\VarianteProdController; // Importante añadir este



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí registramos todas las rutas para la aplicación. Usamos Route::resource
| para crear automáticamente todas las rutas necesarias para un CRUD.
|
*/

// --- RUTA PRINCIPAL ---
// Apunta a nuestro Dashboard.
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');


// --- RUTAS DE RECURSOS (CRUDs) ---

// Módulo de Clientes
Route::resource('clientes', ClienteController::class);

// Módulo de Proveedores
Route::resource('proveedores', ProveedorController::class);

// Módulo de Productos y sus sub-recursos
Route::resource('productos', ProductoController::class);
Route::resource('productos.variantes', VarianteProdController::class)->shallow(); // Rutas para variantes
Route::resource('categorias', CategoriaController::class)->except(['show', 'create', 'edit']); // Categorías se manejan en una sola vista

// Módulo de Pedidos
Route::resource('pedidos', PedidoController::class);

// Módulos que por ahora solo tienen una vista principal
Route::resource('reportes', ReporteController::class)->only(['index']);
Route::resource('roles', RoleController::class)->only(['index']);
Route::resource('productos', ProductoController::class);
Route::resource('productos.variantes', VarianteProdController::class)->except(['index', 'show'])->shallow();