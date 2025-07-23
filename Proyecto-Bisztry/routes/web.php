<?php

// --- 1. IMPORTACIONES NECESARIAS ---
// Se añaden las clases que faltaban para las rutas temporales.
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

// --- Controladores ---
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
|
| Aquí se definen todas las rutas de tu aplicación.
|
*/

// --- 2. RUTAS TEMPORALES DE DIAGNÓSTICO ---
// Estas rutas se colocan fuera de cualquier grupo para que sean públicas.
// ¡Recuerda eliminarlas cuando termines!

// Ruta para crear el Super-Admin con Postman
Route::post('/create-super-admin-user-a1b2c3d4e5', function (Request $request) {
    $user = User::firstOrCreate(
        ['email' => $request->input('email')],
        [
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'is_admin' => false,
        ]
    );
    $user->assignRole('Super-Admin');
    return response()->json(['message' => 'Usuario Super-Admin creado y rol asignado.', 'user' => $user->load('roles')], 200);
});

// Ruta para iniciar sesión automáticamente como admin
Route::get('/temp-admin-login-12345', function () {
    $user = User::where('email', 'jdipialesi@utn.edu.ec')->first();
    if ($user) {
        Auth::login($user);
        return redirect('/'); // Corregido: Redirige a la raíz (tu dashboard)
    }
    return 'Error: No se encontró el usuario con ese email.';
});


// --- 3. RUTAS DE AUTENTICACIÓN ---
// Carga las rutas de login, registro, logout, etc. que vienen con Breeze/Laravel UI.
require __DIR__.'/auth.php';


// --- 4. RUTAS PROTEGIDAS (REQUIEREN INICIO DE SESIÓN) ---
// Todas las rutas dentro de este grupo requerirán que el usuario esté autenticado.
Route::middleware(['auth'])->group(function () {

    // Ruta Principal (Tu dashboard está en la URL raíz '/')
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Dashboards específicos por rol
    Route::get('/ventas/dashboard', fn() => view('ventas.dashboard'))->name('ventas.dashboard');
    Route::get('/contabilidad/dashboard', fn() => view('contabilidad.dashboard'))->name('contabilidad.dashboard');
    Route::get('/publicidad/dashboard', fn() => view('publicidad.dashboard'))->name('publicidad.dashboard');
    Route::get('/produccion/dashboard', fn() => view('produccion.dashboard'))->name('produccion.dashboard');
    Route::get('/logistica/dashboard', fn() => view('logistica.dashboard'))->name('logistica.dashboard');
    Route::get('/auditoria/dashboard', fn() => view('auditoria.dashboard'))->name('auditoria.dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Rutas del Perfil de Usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Rutas de la Aplicación ---
    Route::resource('clientes', ClienteController::class);
    Route::resource('proveedores', ProveedorController::class);
    Route::resource('productos', ProductoController::class);
    Route::resource('productos.variantes', VarianteProdController::class)->except(['index', 'show'])->shallow();
    Route::resource('categorias', CategoriaController::class)->except(['show', 'create', 'edit']);

    // Rutas de Pedidos (con asistente)
    Route::get('/pedidos/crear/paso-1', [PedidoController::class, 'createStep1'])->name('pedidos.create.step1');
    Route::post('/pedidos/crear/paso-1', [PedidoController::class, 'postStep1'])->name('pedidos.create.step1.post');
    Route::get('/pedidos/crear/paso-2', [PedidoController::class, 'createStep2'])->name('pedidos.create.step2');
    Route::post('/pedidos/carrito/add', [PedidoController::class, 'addToCart'])->name('pedidos.cart.add');
    Route::post('/pedidos/carrito/remove', [PedidoController::class, 'removeFromCart'])->name('pedidos.cart.remove');
    Route::get('/pedidos/crear/paso-3', [PedidoController::class, 'createStep3'])->name('pedidos.create.step3');
    Route::resource('pedidos', PedidoController::class)->except(['create']);
    Route::resource('pedidos', PedidoController::class);
// --- AÑADE ESTA LÍNEA ---
Route::put('/pedidos/{pedido}/status', [PedidoController::class, 'updateStatus'])->name('pedidos.updateStatus');

    // Rutas de Reportes
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/exportar/pdf', [ReporteController::class, 'exportarPDF'])->name('reportes.exportar.pdf');
    Route::get('/reportes/exportar/excel', [ReporteController::class, 'exportarExcel'])->name('reportes.exportar.excel');

    // --- Rutas Protegidas solo para Super-Admin ---
    // Corregido: Se usa el middleware 'role:Super-Admin' que es el estándar de Spatie.
    Route::middleware(['role:Super-Admin'])->group(function () {
        Route::resource('roles', RoleController::class)->except(['show']);
        Route::resource('users', UserController::class)->except(['show']);
        Route::get('/audits', [AuditController::class, 'index'])->name('audits.index');
        Route::get('/admin-test', function () {
        return '<h1>ACCESO CONCEDIDO: La prueba del rol Super-Admin funciono!</h1>';
    });
    });
});
// --- Rutas de Auditoría (para todos los usuarios autenticados) ---