<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use App\Models\Producto;

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

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/panel', [DashboardController::class, 'index'])->name('panel');

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
| ESCÁNER DE CÓDIGOS DE BARRA
|--------------------------------------------------------------------------
*/

// Vista principal del escáner y carrito
Route::get('/barcode', [BarcodeController::class, 'index'])->name('barcode.index');

// Vista opcional de scanner simple
Route::get('/scanner', function () {
    return view('ventas.scan');
});

// Ruta GET para buscar productos por código (opcional, URL directa)
Route::get('/productos/buscar/{codigo}', [ProductoController::class, 'buscarPorCodigo']);

// Ruta POST para buscar productos vía AJAX (desde JS / escáner)
Route::post('/buscar-producto', function (Request $request) {
    $codigo = $request->input('codigo_barra');
    $producto = Producto::where('codigo_barra', $codigo)->first();

    if ($producto) {
        return response()->json([
            'success' => true,
            'producto' => $producto
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Producto no encontrado'
        ]);
    }
});

/*
|--------------------------------------------------------------------------
| API Y OTROS DATOS
|--------------------------------------------------------------------------
*/

Route::get('/api/producto/{codigo}', function ($codigo) {
    return Producto::where('codigo', $codigo)->first();
});

Route::get('/api/clientes', function () {
    return \App\Models\Cliente::all();
});

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
