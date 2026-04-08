<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| RUTA PRINCIPAL (DASHBOARD)
|--------------------------------------------------------------------------
*/

Route::get('/', [DashboardController::class, 'index'])->name('panel');

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

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
| API
|--------------------------------------------------------------------------
*/

Route::get('/api/producto/{codigo}', function ($codigo) {
    return \App\Models\Producto::where('codigo', $codigo)->first();
});

Route::get('/api/clientes', function () {
    return \App\Models\Cliente::all();
});

/*
|--------------------------------------------------------------------------
| TICKET
|--------------------------------------------------------------------------
*/

Route::get('/ventas/{id}/ticket', [VentaController::class, 'ticket'])->name('ventas.ticket');

/*
|--------------------------------------------------------------------------
| ERRORES
|--------------------------------------------------------------------------
*/

Route::get('/401', fn() => view('pages.401'));
Route::get('/404', fn() => view('pages.404'));
Route::get('/500', fn() => view('pages.500'));

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
})->name('logout');
