<?php

use Illuminate\Support\Facades\Route;

// 1. Importamos todos los controladores que usaremos
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\VarianteProdController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Carga las rutas de autenticación (login, registro, logout, etc.)
require __DIR__.'/auth.php';


// Todas las rutas dentro de este grupo requerirán que el usuario esté logueado.
Route::middleware(['auth'])->group(function () {

    // Ruta Principal
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas del Perfil de Usuario (de Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- RUTAS DE NUESTRA APLICACIÓN (Accesibles para cualquier usuario logueado) ---
    Route::resource('clientes', ClienteController::class);
    Route::resource('proveedores', ProveedorController::class);
    Route::resource('productos', ProductoController::class);
    Route::resource('productos.variantes', VarianteProdController::class)->except(['index', 'show'])->shallow();
    Route::resource('categorias', CategoriaController::class)->except(['show', 'create', 'edit']);
    Route::resource('pedidos', PedidoController::class);
    Route::resource('reportes', ReporteController::class)->only(['index']);
    
    // ===================================================================
    // ---          RUTAS PROTEGIDAS PARA SUPER-ADMINISTRADOR          ---
    // ===================================================================
    // Todas las rutas dentro de este grupo solo serán accesibles para
    // usuarios que pasen la verificación del middleware 'role:superadmin'.
    Route::middleware('role:superadmin')->group(function () {
        
        // Aquí agrupamos todas las rutas que solo el Super Admin puede ver.
        Route::resource('roles', RoleController::class)->except(['show']);
        Route::resource('users', UserController::class)->except(['show']);

    });
    // ===================================================================
    
});