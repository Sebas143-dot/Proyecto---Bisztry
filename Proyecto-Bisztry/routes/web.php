<?php

// --- 1. IMPORTACIONES DE CONTROLADORES ---
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
| Archivo de rutas con el sistema de permisos de Spatie integrado.
| Cada ruta de recurso está ahora protegida por su permiso correspondiente.
|
*/

// --- 2. RUTAS DE AUTENTICACIÓN ---
// Carga las rutas de login, logout, etc. El registro está deshabilitado.
require __DIR__.'/auth.php';


// --- 3. RUTAS PROTEGIDAS (REQUIEREN INICIO DE SESIÓN) ---
Route::middleware(['auth'])->group(function () {

    // --- DASHBOARD Y PERFIL (ACCESO GENERAL) ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', [DashboardController::class, 'index']);
    // Dashboards específicos por rol (se mantienen como estaban)
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

    // --- RUTAS DE RECURSOS PROTEGIDAS POR PERMISOS ---
    
    Route::resource('clientes', ClienteController::class)->middleware('permission:gestionar-clientes');
    Route::resource('proveedores', ProveedorController::class)->middleware('permission:gestionar-proveedores');
    
    // Agrupamos todo lo relacionado a productos bajo un mismo permiso
    Route::middleware('permission:gestionar-productos')->group(function () {
        Route::resource('productos', ProductoController::class);
        Route::resource('productos.variantes', VarianteProdController::class)->except(['index', 'show'])->shallow();
        Route::resource('categorias', CategoriaController::class)->except(['show', 'create', 'edit']);
    });

    // Separamos la creación de la gestión de pedidos
    Route::middleware('permission:crear-pedidos')->group(function () {
        Route::get('/pedidos/crear/paso-1', [PedidoController::class, 'createStep1'])->name('pedidos.create.step1');
        Route::post('/pedidos/crear/paso-1', [PedidoController::class, 'postStep1'])->name('pedidos.create.step1.post');
        Route::get('/pedidos/crear/paso-2', [PedidoController::class, 'createStep2'])->name('pedidos.create.step2');
        Route::post('/pedidos/carrito/add', [PedidoController::class, 'addToCart'])->name('pedidos.cart.add');
        Route::post('/pedidos/carrito/remove', [PedidoController::class, 'removeFromCart'])->name('pedidos.cart.remove');
        Route::get('/pedidos/crear/paso-3', [PedidoController::class, 'createStep3'])->name('pedidos.create.step3');
    });
    
    Route::resource('pedidos', PedidoController::class)->except(['create'])->middleware('permission:gestionar-pedidos|crear-pedidos'); // Permite ver el listado a ambos roles
    Route::put('/pedidos/{pedido}/status', [PedidoController::class, 'updateStatus'])->name('pedidos.updateStatus')->middleware('permission:gestionar-pedidos');

    // Rutas de Reportes
    Route::middleware('permission:ver-reportes')->group(function () {
        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
        Route::get('/reportes/exportar/pdf', [ReporteController::class, 'exportarPDF'])->name('reportes.exportar.pdf');
        Route::get('/reportes/exportar/excel', [ReporteController::class, 'exportarExcel'])->name('reportes.exportar.excel');
    });

    // --- RUTAS DE ADMINISTRACIÓN PROTEGIDAS POR PERMISOS ---
    // En lugar de 'role:Super-Admin', usamos los permisos específicos para mayor flexibilidad.
    Route::middleware('permission:gestionar-roles')->group(function () {
        Route::resource('roles', RoleController::class)->except(['show']);
    });

    Route::middleware('permission:gestionar-usuarios')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });

    Route::middleware('permission:view audit logs')->group(function () {
        Route::get('/audits', [AuditController::class, 'index'])->name('audits.index');
    });
});
