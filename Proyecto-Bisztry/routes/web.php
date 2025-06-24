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
// Es buena práctica tenerlo al principio para que se registren primero.
require __DIR__.'/auth.php';


// Todas las rutas dentro de este grupo requerirán que el usuario esté logueado.
Route::middleware(['auth'])->group(function () {

    // Ruta Principal
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas del Perfil de Usuario (de Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- RUTAS DE NUESTRA APLICACIÓN ---
    Route::resource('clientes', ClienteController::class);
    Route::resource('proveedores', ProveedorController::class);
    Route::resource('productos', ProductoController::class);
    Route::resource('productos.variantes', VarianteProdController::class)->except(['index', 'show'])->shallow();
    Route::resource('categorias', CategoriaController::class)->except(['show', 'create', 'edit']);
    Route::resource('pedidos', PedidoController::class);
    Route::resource('reportes', ReporteController::class)->only(['index']);
    
    // =======================================================
    // --- INICIO DE LA MEJORA DE SEGURIDAD ---
    // =======================================================
    // Agrupamos las rutas de administración y les aplicamos nuestro middleware 'role.superadmin'.
    // Ahora, solo los usuarios con el rol 'Super-Admin' podrán acceder a estas URLs.
    // Cualquier otro usuario recibirá un error 403 (Acceso Prohibido).
    Route::middleware(['role.superadmin'])->group(function () {
        Route::resource('roles', RoleController::class)->except(['show']);
        Route::resource('users', UserController::class)->except(['show']);
    });
    // =======================================================
    // --- FIN DE LA MEJORA DE SEGURIDAD ---
    // =======================================================
});

