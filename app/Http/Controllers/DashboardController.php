<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Producto;
use App\Models\User;
use App\Models\DetalleVenta;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | FECHAS (AJUSTADAS A CHILE)
        |--------------------------------------------------------------------------
        */

        $inicioDia = Carbon::now()->startOfDay();
        $finDia = Carbon::now()->endOfDay();
        $inicioMes = Carbon::now()->startOfMonth();

        /*
        |--------------------------------------------------------------------------
        | MÉTRICAS
        |--------------------------------------------------------------------------
        */

        // ✅ Ingresos del día (SOLUCIÓN DEFINITIVA)
        $ingresosHoy = Venta::whereBetween('fecha_hora', [
            $inicioDia,
            $finDia
        ])->sum('total') ?? 0;

        // ✅ Ingresos totales
        $ingresosTotales = Venta::sum('total') ?? 0;

        // ✅ Ventas del mes (cantidad)
        $ventasMes = Venta::where('fecha_hora', '>=', $inicioMes)->count();

        // ✅ Productos activos (estado = 1)
        $productosActivos = Producto::where('estado', 1)->count();

        // ✅ Productos inactivos
        $productosInactivos = Producto::where('estado', 0)->count();

        // ✅ Clientes
        $clientes = User::count();

        // ✅ Stock bajo (cantidad directa para la card)
        $stockBajo = Producto::where('stock', '<', 5)->count();

        /*
        |--------------------------------------------------------------------------
        | GRÁFICO (VENTAS POR DÍA)
        |--------------------------------------------------------------------------
        */

        $ventasPorDia = Venta::select(
            DB::raw('DATE(fecha_hora) as fecha'),
            DB::raw('SUM(total) as total')
        )
            ->where('fecha_hora', '>=', $inicioMes)
            ->groupBy(DB::raw('DATE(fecha_hora)'))
            ->orderBy('fecha', 'ASC')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | PRODUCTOS RECIENTES
        |--------------------------------------------------------------------------
        */

        $productosRecientes = Producto::latest()->take(5)->get();

        /*
        |--------------------------------------------------------------------------
        | ÚLTIMAS VENTAS
        |--------------------------------------------------------------------------
        */

        $ultimasOrdenes = Venta::with('detalles.producto')
            ->latest()
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | TOP PRODUCTOS MÁS VENDIDOS
        |--------------------------------------------------------------------------
        */

        $topProductos = DetalleVenta::select(
            'producto_id',
            DB::raw('SUM(cantidad) as total_vendidos')
        )
            ->with('producto')
            ->groupBy('producto_id')
            ->orderByDesc('total_vendidos')
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | PRODUCTOS CON STOCK BAJO (LISTA)
        |--------------------------------------------------------------------------
        */

        $productosBajoStock = Producto::where('stock', '<', 5)->get();

        /*
        |--------------------------------------------------------------------------
        | RETORNO
        |--------------------------------------------------------------------------
        */

        return view('panel.index', compact(
            'ingresosHoy',
            'ingresosTotales',
            'ventasMes',
            'productosActivos',
            'productosInactivos',
            'clientes',
            'stockBajo', // 👈 clave para tu card
            'ventasPorDia',
            'productosRecientes',
            'ultimasOrdenes',
            'topProductos',
            'productosBajoStock'
        ));
    }
}
