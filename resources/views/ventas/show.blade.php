@extends('template')

@section('title', 'Detalle de Venta')

@section('content')
    <div class="max-w-6xl mx-auto mt-12 p-8 bg-white shadow-2xl rounded-2xl">
        <!-- Encabezado -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10">
            <h1 class="text-4xl font-extrabold text-gray-800 mb-4 md:mb-0">Venta #{{ $venta->id }}</h1>
            <div class="text-gray-700 text-lg space-y-1">
                <p><span class="font-semibold">Fecha:</span> {{ $venta->created_at->format('d/m/Y H:i') }}</p>
                <p><span class="font-semibold">Total:</span> ${{ number_format($venta->total, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Tabla de productos -->
        <h2 class="text-2xl font-semibold mb-6 text-gray-800">Productos</h2>
        <div class="overflow-x-auto border rounded-lg shadow-lg">
            <table class="w-full min-w-[600px] border-collapse">
                <thead class="bg-gray-100 text-gray-700 uppercase text-sm tracking-wide">
                    <tr>
                        <th class="px-6 py-4 border-b">Producto</th>
                        <th class="px-6 py-4 border-b">Cantidad</th>
                        <th class="px-6 py-4 border-b">Precio Unitario</th>
                        <th class="px-6 py-4 border-b">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600">
                    @foreach ($venta->detalles as $detalle)
                        <tr class="even:bg-gray-50 hover:bg-gray-100 transition-colors">
                            <td class="px-6 py-3 border-b">{{ $detalle->producto->nombre }}</td>
                            <td class="px-6 py-3 border-b text-center">{{ $detalle->cantidad }}</td>
                            <td class="px-6 py-3 border-b">${{ number_format($detalle->precio, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 border-b font-medium">
                                ${{ number_format($detalle->cantidad * $detalle->precio, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-100 font-semibold text-gray-800">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right border-t">TOTAL:</td>
                        <td class="px-6 py-4 border-t">${{ number_format($venta->total, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Botones -->
        <div class="mt-10 flex flex-col md:flex-row justify-end gap-4 md:gap-6">
            <!-- Volver -->
            <a href="{{ route('ventas.index') }}"
                class="bg-black text-white font-bold px-8 py-3 rounded-2xl shadow-lg 
              hover:bg-gray-800 hover:shadow-2xl transition-all transform hover:-translate-y-1
              mb-4 md:mb-0">
                ← Volver al listado
            </a>

            <!-- Imprimir -->
            <button onclick="window.print()"
                class="bg-black text-white font-bold px-8 py-3 rounded-2xl shadow-lg 
              hover:bg-gray-800 hover:shadow-2xl transition-all transform hover:-translate-y-1">
                🖨 Imprimir
            </button>
        </div>
    </div>
@endsection
