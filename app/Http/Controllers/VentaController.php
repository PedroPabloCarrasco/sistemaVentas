<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{





    public function index()
    {
        $ventas = \App\Models\Venta::orderBy('id', 'desc')->get();

        return view('ventas.index', compact('ventas'));
    }


    public function show($id)
    {
        $venta = \App\Models\Venta::with('detalles.producto')->findOrFail($id);

        return view('ventas.show', compact('venta'));
    }





    /**
     * Mostrar formulario de venta
     */
    public function create()
    {
        $productos = Producto::where('estado', 1)->get();
        return view('ventas.create', compact('productos'));
    }

    /**
     * Guardar venta
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            //  Convertir JSON a array
            $items = json_decode($request->productos, true);

            if (!$items || count($items) == 0) {
                throw new \Exception("No hay productos en la venta");
            }

            //  Crear venta
            $venta = Venta::create([
                'total' => 0,
                'impuesto' => 0
            ]);

            $total = 0;

            foreach ($items as $item) {

                $producto = Producto::findOrFail($item['id']);
                $cantidad = (int) $item['cantidad'];

                //  Validar stock
                if ($producto->stock < $cantidad) {
                    throw new \Exception("Stock insuficiente de {$producto->nombre}");
                }

                $precio = $producto->precio;
                $subtotal = $precio * $cantidad;

                //  Guardar detalle
                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'precio' => $precio
                ]);

                //  Descontar stock
                $producto->decrement('stock', $cantidad);

                $total += $subtotal;
            }

            //  Actualizar total
            $venta->update([
                'total' => $total
            ]);

            DB::commit();

            return redirect()->route('ventas.create')
                ->with('success', 'Venta realizada correctamente');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }
}
