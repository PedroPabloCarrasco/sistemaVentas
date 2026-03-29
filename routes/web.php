<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\BarcodeController;

/*
|--------------------------------------------------------------------------
| RUTAS PRINCIPALES
|--------------------------------------------------------------------------
*/





Route::get('/', function () {
    return view('template');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/panel', function () {
    return view('panel.index');
})->name('panel');

Route::get('/edit', function () {
    return view('productos/edit');
});


/*
|--------------------------------------------------------------------------
| MÓDULOS (CRUD)
|--------------------------------------------------------------------------
*/

Route::resource('categorias', CategoriaController::class);
Route::resource('productos', ProductoController::class);
Route::resource('ventas', VentaController::class);


/*
|--------------------------------------------------------------------------
| PÁGINAS DE ERROR
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('template');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/panel', function () {
    return view('panel.index');
})->name('panel');

// CRUD
Route::resource('categorias', CategoriaController::class);
Route::resource('productos', ProductoController::class);
Route::resource('ventas', VentaController::class);

// Páginas de error
Route::get('/401', fn() => view('pages.401'));
Route::get('/404', fn() => view('pages.404'));
Route::get('/500', fn() => view('pages.500'));

// Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

Route::get('/barcode', [BarcodeController::class, 'index'])->name('barcode.index');
