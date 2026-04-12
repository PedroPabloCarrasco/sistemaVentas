<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

// Mostrar formulario
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// Procesar login
Route::post('/login', [AuthController::class, 'login']);

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/', [DashboardController::class, 'index'])->name('panel');


    /*
    |--------------------------------------------------------------------------
    | CRUD
    |--------------------------------------------------------------------------
    */

    Route::resource('categorias', CategoriaController::class);
    Route::resource('productos', ProductoController::class);
    Route::resource('ventas', VentaController::class);


    /*
    |--------------------------------------------------------------------------
    | OTROS
    |--------------------------------------------------------------------------
    */

    Route::get('/barcode', [BarcodeController::class, 'index'])->name('barcode.index');


    /*
    |--------------------------------------------------------------------------
    | TICKET
    |--------------------------------------------------------------------------
    */

    Route::get('/ventas/{id}/ticket', [VentaController::class, 'ticket'])->name('ventas.ticket');


    /*
    |--------------------------------------------------------------------------
    | CIERRES
    |--------------------------------------------------------------------------
    */

    Route::post('/cierre-mensual', [VentaController::class, 'cierreMensual'])
        ->name('ventas.cierre');

    Route::get('/cierres', [VentaController::class, 'historialCierres'])
        ->name('cierres.index');
});


/*
|--------------------------------------------------------------------------
| API (puedes protegerlas después si quieres)
|--------------------------------------------------------------------------
*/

Route::get('/api/producto/{codigo}', function ($codigo) {
    return \App\Models\Producto::where('codigo_barra', $codigo)->first();
});

Route::get('/api/clientes', function () {
    return \App\Models\Cliente::all();
});


/*
|--------------------------------------------------------------------------
| ERRORES
|--------------------------------------------------------------------------
*/

Route::get('/401', fn() => view('pages.401'));
Route::get('/404', fn() => view('pages.404'));
Route::get('/500', fn() => view('pages.500'));
