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
        $hoy = Carbon::today();
        $inicioMes = Carbon::now()->startOfMonth();

        /*
        |--------------------------------------------------------------------------
        | MÉTRICAS PRINCIPALES
        |--------------------------------------------------------------------------
        */

        $ingresosHoy = Venta::whereDate('fecha_hora', $hoy)->sum('total') ?? 0;
        $ingresosTotales = Venta::sum('total') ?? 0;
        $ventasMes = Venta::where('fecha_hora', '>=', $inicioMes)->count();
        $productosActivos = Producto::count();
        $clientes = User::count();

        /*
        |--------------------------------------------------------------------------
        | PORCENTAJES (KPIs)
        |--------------------------------------------------------------------------
        */

        $ventasHoy = Venta::whereDate('fecha_hora', $hoy)->count();

        $porcentajeIngresos = $ingresosTotales > 0
            ? round(($ingresosHoy / $ingresosTotales) * 100)
            : 0;

        $porcentajeVentas = $ventasMes > 0
            ? round(($ventasHoy / $ventasMes) * 100)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | VENTAS POR DÍA (GRÁFICO)
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
        | TOP PRODUCTOS
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

        // Normalizar porcentajes (para barras)
        $maxVentas = $topProductos->max('total_vendidos') ?: 1;

        $topProductos = $topProductos->map(function ($item) use ($maxVentas) {
            $item->porcentaje = ($item->total_vendidos / $maxVentas) * 100;
            return $item;
        });

        /*
        |--------------------------------------------------------------------------
        | DONUT (SIN ERROR)
        |--------------------------------------------------------------------------
        */

        $ventas = Venta::count();
        $servicios = 0; // no usamos 'tipo' para evitar error
        $otros = $ventas;

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
        | STOCK BAJO
        |--------------------------------------------------------------------------
        */

        $productosBajoStock = Producto::where('stock', '<', 5)->get();

        /*
        |--------------------------------------------------------------------------
        | RETURN
        |--------------------------------------------------------------------------
        */

        return view('panel.index', compact(
            'ingresosHoy',
            'ingresosTotales',
            'ventasMes',
            'productosActivos',
            'clientes',
            'ventasPorDia',
            'productosRecientes',
            'ultimasOrdenes',
            'topProductos',
            'productosBajoStock',
            'porcentajeIngresos',
            'porcentajeVentas',
            'ventas',
            'servicios',
            'otros'
        ));
    }
}
