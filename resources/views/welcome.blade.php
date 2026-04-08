@extends('template')

@section('title', 'Dashboard - Vitaco Ventas')

@section('content')

    <div class="container-fluid px-4">

        <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Resumen general</li>
        </ol>

        <!-- MÉTRICAS -->
        <div class="row">

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-success text-white rounded-4 p-3 shadow-sm">
                    <h6>Ingresos Hoy</h6>
                    <h3>$ {{ number_format($ingresosHoy, 0, ',', '.') }}</h3>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-primary text-white rounded-4 p-3 shadow-sm">
                    <h6>Ingresos Totales</h6>
                    <h3>$ {{ number_format($ingresosTotales, 0, ',', '.') }}</h3>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-warning text-dark rounded-4 p-3 shadow-sm">
                    <h6>Ventas del Mes</h6>
                    <h3>{{ $ventasMes }}</h3>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-dark text-white rounded-4 p-3 shadow-sm">
                    <h6>Producto</h6>
                    <h3>{{ $productosActivos }}</h3>

                </div>
            </div>

        </div>

        <div class="row">

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-info text-white rounded-4 p-3 shadow-sm">
                    <h6>Clientes</h6>
                    <h3>{{ $clientes }}</h3>
                </div>
            </div>

        </div>

        <!-- GRÁFICO -->
        <div class="card mb-4 shadow-sm rounded-4">
            <div class="card-header">
                Ventas del mes
            </div>
            <div class="card-body">
                <canvas id="graficoVentas"></canvas>
            </div>
        </div>

        <!-- TABLAS -->
        <div class="row">

            <!-- ÚLTIMAS VENTAS -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm rounded-4">
                    <div class="card-header">Últimas Ventas</div>
                    <div class="card-body">

                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Total</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ultimasOrdenes as $venta)
                                    <tr>
                                        <td>{{ $venta->id }}</td>
                                        <td>$ {{ number_format($venta->total, 0, ',', '.') }}</td>
                                        <td>{{ $venta->fecha_hora }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

            <!-- TOP PRODUCTOS -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm rounded-4">
                    <div class="card-header">Top Productos</div>
                    <div class="card-body">

                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Vendidos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topProductos as $item)
                                    <tr>
                                        <td>{{ $item->producto->nombre ?? 'N/A' }}</td>
                                        <td>{{ $item->total_vendidos }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

        </div>

        <!-- PRODUCTOS RECIENTES -->
        <div class="card mb-4 shadow-sm rounded-4">
            <div class="card-header">Productos Recientes</div>
            <div class="card-body">

                <ul class="list-group">
                    @foreach ($productosRecientes as $producto)
                        <li class="list-group-item d-flex justify-content-between">
                            {{ $producto->nombre }}
                            <span class="badge bg-primary">$ {{ number_format($producto->precio, 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>

            </div>
        </div>

        <!-- STOCK BAJO -->
        <div class="card mb-4 shadow-sm rounded-4 border-danger">
            <div class="card-header bg-danger text-white">Stock Bajo</div>
            <div class="card-body">

                @if ($productosBajoStock->isEmpty())
                    <p>No hay productos con stock bajo</p>
                @else
                    <ul class="list-group">
                        @foreach ($productosBajoStock as $producto)
                            <li class="list-group-item d-flex justify-content-between">
                                {{ $producto->nombre }}
                                <span class="badge bg-danger">{{ $producto->stock }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif

            </div>
        </div>

    </div>

@endsection


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const labels = @json($ventasPorDia->pluck('fecha'));
        const data = @json($ventasPorDia->pluck('total'));

        const ctx = document.getElementById('graficoVentas').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Ventas',
                    data: data,
                    fill: true,
                    tension: 0.3
                }]
            }
        });
    </script>
@endsection
