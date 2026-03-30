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

    public function index()
    {
        $ventas = Venta::orderBy('id', 'desc')->get();
        return view('ventas.index', compact('ventas'));
    }

    public function show($id)
    {
        $venta = Venta::with('detalles.producto')->findOrFail($id);
        return view('ventas.show', compact('venta'));
    }

    public function create()
    {
        $productos = Producto::where('estado', 1)->get();
        return view('ventas.create', compact('productos'));
    }

    /**
     * 🔥 GUARDAR VENTA (COMPATIBLE CON POS + FORMULARIO)
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            // 🔥 Soporta JSON (POS) o formulario normal
            $items = is_array($request->productos)
                ? $request->productos
                : json_decode($request->productos, true);

            if (!$items || count($items) == 0) {
                throw new \Exception("No hay productos en la venta");
            }

            // 🔥 Crear venta con más datos
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

                // ❌ Validar stock
                if ($producto->stock < $cantidad) {
                    throw new \Exception("Stock insuficiente de {$producto->nombre}");
                }

                $precio = $producto->precio;
                $subtotal = $precio * $cantidad;

                // ✅ Guardar detalle
                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'precio' => $precio
                ]);

                // ✅ Descontar stock
                $producto->decrement('stock', $cantidad);

                $total += $subtotal;
            }

            // ✅ Actualizar total final
            $venta->update([
                'total' => $total
            ]);

            DB::commit();

            // 🔥 RESPUESTA PARA POS (fetch)
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'venta_id' => $venta->id
                ]);
            }

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
     * 🧾 TICKET / BOLETA
     */
    public function ticket($id)
    {
        $venta = Venta::with('detalles.producto')->findOrFail($id);
        return view('ventas.ticket', compact('venta'));
    }



    /**
     * Eliminar una venta
     */
    public function destroy($id)
    {
        try {
            $venta = Venta::findOrFail($id);

            // Esto eliminará también los detalles relacionados si la relación está definida con cascade
            $venta->delete();

            return redirect()->route('ventas.index')
                ->with('success', 'Venta eliminada correctamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la venta: ' . $e->getMessage());
        }
    }
}
