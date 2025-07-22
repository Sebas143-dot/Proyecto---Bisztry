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
use App\Http\Controllers\AuditController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Carga las rutas de autenticación (login, registro, logout, etc.)
require __DIR__.'/auth.php';


// Todas las rutas dentro de este grupo requerirán que el usuario esté logueado.
Route::middleware(['auth'])->group(function () {
    Route::resource('reportes', ReporteController::class)->only(['index']);
    
    // --- AÑADIR ESTAS DOS LÍNEAS ---
    Route::get('/reportes/exportar/pdf', [ReporteController::class, 'exportarPDF'])->name('reportes.exportar.pdf');
    Route::get('/reportes/exportar/excel', [ReporteController::class, 'exportarExcel'])->name('reportes.exportar.excel');

    // Ruta Principal
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Dashboards por rol
Route::get('/ventas/dashboard', function () {
    return view('ventas.dashboard');
})->name('ventas.dashboard');

Route::get('/contabilidad/dashboard', function () {
    return view('contabilidad.dashboard');
})->name('contabilidad.dashboard');

Route::get('/publicidad/dashboard', function () {
    return view('publicidad.dashboard');
})->name('publicidad.dashboard');

Route::get('/produccion/dashboard', function () {
    return view('produccion.dashboard');
})->name('produccion.dashboard');

Route::get('/logistica/dashboard', function () {
    return view('logistica.dashboard');
})->name('logistica.dashboard');

Route::get('/auditoria/dashboard', function () {
    return view('auditoria.dashboard');
})->name('auditoria.dashboard');

Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');


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
// --- INICIO DE RUTAS PARA GESTIÓN DE PEDIDOS (CON ASISTENTE) ---
// ===================================================================
// Rutas específicas para cada paso del asistente de creación
Route::get('/pedidos/crear/paso-1', [PedidoController::class, 'createStep1'])->name('pedidos.create.step1');
Route::post('/pedidos/crear/paso-1', [PedidoController::class, 'postStep1'])->name('pedidos.create.step1.post');
Route::get('/pedidos/crear/paso-2', [PedidoController::class, 'createStep2'])->name('pedidos.create.step2');
Route::post('/pedidos/carrito/add', [PedidoController::class, 'addToCart'])->name('pedidos.cart.add');
Route::post('/pedidos/carrito/remove', [PedidoController::class, 'removeFromCart'])->name('pedidos.cart.remove');
Route::get('/pedidos/crear/paso-3', [PedidoController::class, 'createStep3'])->name('pedidos.create.step3');

// Rutas estándar del CRUD de Pedidos (index, show, edit, etc.), excepto 'create'.
Route::resource('pedidos', PedidoController::class)->except(['create']);
// ===================================================================
    // ===================================================================
    // ---      RUTAS PROTEGIDAS PARA SUPER-ADMINISTRADOR (CORREGIDO)    ---
    // ===================================================================
    // Usamos el middleware que viene con el paquete spatie/laravel-permission.
    // La sintaxis 'role:Super-Admin' le dice: "solo deja pasar a usuarios con el rol 'Super-Admin'".
// En routes/web.php
// ...
    // --- RUTAS PROTEGIDAS (VERSIÓN DE DIAGNÓSTICO) ---
    // Cambiamos 'role:Super-Admin' por nuestro alias 'role.superadmin'
    Route::middleware(['role.superadmin'])->group(function () {
        Route::resource('roles', RoleController::class)->except(['show']);
        Route::resource('users', UserController::class)->except(['show']);
        Route::get('/audits', [AuditController::class, 'index'])->name('audits.index');
    });
    // ===================================================================
    Route::get('/temp-admin-login-12345', function () {
    // Busca al usuario por su email
    $user = \App\Models\User::where('email', 'jdipialesi@utn.edu.ec')->first();

    // Si el usuario existe...
    if ($user) {
        // Inicia sesión con ese usuario
        \Illuminate\Support\Facades\Auth::login($user);
        // Redirige al dashboard
        return redirect('/dashboard'); // O la ruta a la que vayas después de iniciar sesión
    }

    // Si no se encontró el usuario
    return 'Error: No se encontró el usuario con ese email.';
});
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
