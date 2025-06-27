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

    // --- RUTAS DE NUESTRA APLICACIÓN ---
    Route::resource('clientes', ClienteController::class);
    Route::resource('proveedores', ProveedorController::class);
    Route::resource('productos', ProductoController::class);
    Route::resource('productos.variantes', VarianteProdController::class)->except(['index', 'show'])->shallow();
    Route::resource('categorias', CategoriaController::class)->except(['show', 'create', 'edit']);
    Route::resource('reportes', ReporteController::class)->only(['index']);
    
    // =======================================================
    // --- RUTAS PARA GESTIÓN DE PEDIDOS (CORREGIDO) ---
    // =======================================================
    // Se definen las rutas específicas para cada paso del asistente de creación.
    Route::get('/pedidos/crear/paso-1', [PedidoController::class, 'createStep1'])->name('pedidos.create.step1');
    Route::post('/pedidos/crear/paso-1', [PedidoController::class, 'postStep1'])->name('pedidos.create.step1.post');
    Route::get('/pedidos/crear/paso-2', [PedidoController::class, 'createStep2'])->name('pedidos.create.step2');
    Route::post('/pedidos/carrito/add', [PedidoController::class, 'addToCart'])->name('pedidos.cart.add');
    Route::post('/pedidos/carrito/remove', [PedidoController::class, 'removeFromCart'])->name('pedidos.cart.remove');
    Route::get('/pedidos/crear/paso-3', [PedidoController::class, 'createStep3'])->name('pedidos.create.step3');
    // Se mantienen las rutas estándar del CRUD de Pedidos, excepto 'create'.
    Route::resource('pedidos', PedidoController::class)->except(['create']);
    // =======================================================
    
    // Rutas protegidas para Super-Admin
    Route::middleware(['role:Super-Admin'])->group(function () {
        Route::resource('roles', RoleController::class)->except(['show']);
        Route::resource('users', UserController::class)->except(['show']);
    });
});
