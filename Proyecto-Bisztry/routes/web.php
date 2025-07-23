<?php

// --- 1. IMPORTACIONES DE CONTROLADORES ---
// Se mantienen solo los controladores que están en uso en las rutas web.
use Illuminate\Support\Facades\Route;
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
| Rutas Web
|--------------------------------------------------------------------------
|
| Aquí se definen todas las rutas web para la aplicación. Estas rutas
| son cargadas por el RouteServiceProvider y todas están dentro del
| grupo de middleware "web".
|
*/

// --- 2. RUTAS DE AUTENTICACIÓN ---
// Carga las rutas de login, logout, reseteo de contraseña, etc.
// El registro público está deshabilitado dentro de 'auth.php'.
require __DIR__.'/auth.php';


// --- 3. RUTAS PROTEGIDAS (REQUIEREN INICIO DE SESIÓN) ---
// Todas las rutas dentro de este grupo requerirán que el usuario esté autenticado.
Route::middleware(['auth'])->group(function () {

    // Ruta Principal del Dashboard
    // Se define tanto / como /dashboard para apuntar al dashboard principal.
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', [DashboardController::class, 'index']);

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

    // --- Rutas de Recursos de la Aplicación (CRUDs) ---
    Route::resource('clientes', ClienteController::class);
    Route::resource('proveedores', ProveedorController::class);
    Route::resource('productos', ProductoController::class);
    Route::resource('productos.variantes', VarianteProdController::class)->except(['index', 'show'])->shallow();
    Route::resource('categorias', CategoriaController::class)->except(['show', 'create', 'edit']);

    // Rutas de Pedidos (con asistente y actualización de estado)
    Route::get('/pedidos/crear/paso-1', [PedidoController::class, 'createStep1'])->name('pedidos.create.step1');
    Route::post('/pedidos/crear/paso-1', [PedidoController::class, 'postStep1'])->name('pedidos.create.step1.post');
    Route::get('/pedidos/crear/paso-2', [PedidoController::class, 'createStep2'])->name('pedidos.create.step2');
    Route::post('/pedidos/carrito/add', [PedidoController::class, 'addToCart'])->name('pedidos.cart.add');
    Route::post('/pedidos/carrito/remove', [PedidoController::class, 'removeFromCart'])->name('pedidos.cart.remove');
    Route::get('/pedidos/crear/paso-3', [PedidoController::class, 'createStep3'])->name('pedidos.create.step3');
    Route::resource('pedidos', PedidoController::class)->except(['create']);
    Route::put('/pedidos/{pedido}/status', [PedidoController::class, 'updateStatus'])->name('pedidos.updateStatus');

    // Rutas de Reportes
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/exportar/pdf', [ReporteController::class, 'exportarPDF'])->name('reportes.exportar.pdf');
    Route::get('/reportes/exportar/excel', [ReporteController::class, 'exportarExcel'])->name('reportes.exportar.excel');

    // --- Rutas Protegidas solo para Super-Admin ---
    // Se utiliza el middleware 'role:Super-Admin' de Spatie para proteger estas rutas.
    Route::middleware(['role:Super-Admin'])->group(function () {
        Route::resource('roles', RoleController::class)->except(['show']);
        Route::resource('users', UserController::class)->except(['show']);
        Route::get('/audits', [AuditController::class, 'index'])->name('audits.index');
        
        // Ruta de prueba para verificar el rol de Super-Admin
        Route::get('/admin-test', function () {
            return '<h1>ACCESO CONCEDIDO: La prueba del rol Super-Admin funcionó correctamente.</h1>';
        });
    });
});
