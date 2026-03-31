<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VentaController extends Controller
{
    /**
     * Mostrar todas las ventas
     */
    public function index()
    {
        $ventas = Venta::orderBy('id', 'desc')->get();
        return view('ventas.index', compact('ventas'));
    }

    /**
     * Mostrar una venta específica
     */
    public function show($id)
    {
        $venta = Venta::with('detalles.producto', 'cliente')->findOrFail($id);
        return view('ventas.show', compact('venta'));
    }

    /**
     * Formulario para nueva venta
     */
    public function create()
    {
        $productos = Producto::where('estado', 1)->get();
        return view('ventas.create', compact('productos'));
    }

    /**
     * Guardar venta (compatible con AJAX y formulario normal)
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Obtener los productos (JSON o array)
            $items = is_array($request->productos)
                ? $request->productos
                : json_decode($request->productos, true);

            if (!$items || count($items) === 0) {
                throw new \Exception("No hay productos en la venta");
            }

            // Crear venta
            $venta = Venta::create([
                'total' => 0,
                'impuesto' => 0,
                'fecha_hora' => Carbon::now(),
                'estado' => 'completada',
                'metodo_pago' => $request->metodo_pago ?? 'efectivo',
                'cliente_id' => $request->cliente_id ?? null
            ]);

            $total = 0;

            foreach ($items as $item) {
                $producto = Producto::findOrFail($item['id']);
                $cantidad = (int) $item['cantidad'];

                // Validar stock
                if ($producto->stock < $cantidad) {
                    throw new \Exception("Stock insuficiente de {$producto->nombre}");
                }

                $precio = $producto->precio;
                $subtotal = $precio * $cantidad;

                // Guardar detalle de venta
                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'precio' => $precio
                ]);

                // Descontar stock
                $producto->decrement('stock', $cantidad);

                $total += $subtotal;
            }

            // Actualizar total final
            $venta->update([
                'total' => $total
            ]);

            DB::commit();

            // Respuesta JSON para AJAX
            if ($request->ajax() || $request->wantsJson()) {
                $venta->load('detalles.producto', 'cliente');

                $detalles = $venta->detalles->map(function ($d) {
                    return [
                        'producto' => $d->producto->nombre,
                        'cantidad' => $d->cantidad,
                        'precio' => $d->precio
                    ];
                });

                return response()->json([
                    'success' => true,
                    'message' => 'Venta realizada correctamente',
                    'venta' => [
                        'id' => $venta->id,
                        'total' => $venta->total,
                        'cliente' => optional($venta->cliente)->name ?? 'Consumidor Final',
                        'detalles' => $detalles,
                        'created_at' => $venta->created_at
                    ]
                ]);
            }

            // Para formulario normal
            return redirect()->route('ventas.create')
                ->with('success', 'Venta realizada correctamente');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Mostrar ticket / boleta
     */
    public function ticket($id)
    {
        $venta = Venta::with('detalles.producto', 'cliente')->findOrFail($id);
        return view('ventas.ticket', compact('venta'));
    }

    /**
     * Eliminar venta
     */
    public function destroy($id)
    {
        try {
            $venta = Venta::findOrFail($id);
            $venta->delete(); // eliminar detalles si cascade está definido

            return redirect()->route('ventas.index')
                ->with('success', 'Venta eliminada correctamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la venta: ' . $e->getMessage());
        }
    }
}
