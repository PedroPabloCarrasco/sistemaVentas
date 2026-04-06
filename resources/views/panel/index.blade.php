@extends('template')

@section('title', 'Dashboard')

@push('css')
    <style>
        body {
            background: linear-gradient(135deg, #eef2ff, #f8fafc);
        }

        .dark-mode {
            background: #0f172a !important;
            color: white;
        }

        .card-metric {
            border-radius: 16px;
            padding: 25px 20px;
            background: white;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            transition: transform 0.25s, box-shadow 0.25s;
            text-align: center;
        }

        .card-metric:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .dark-mode .card-metric,
        .dark-mode .card {
            background: #1e293b;
            color: white;
        }

        .metric-title {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .metric-value {
            font-size: 32px;
            font-weight: 700;
            color: #111827;
        }

        .dashboard-card {
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border: none;
        }

        .card-header {
            background: #111827;
            color: white;
            font-weight: 600;
        }

        .badge-status {
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 12px;
        }

        .bg-success-soft {
            background: #dcfce7;
            color: #16a34a;
        }

        .bg-warning-soft {
            background: #fef3c7;
            color: #d97706;
        }

        .bg-danger-soft {
            background: #fee2e2;
            color: #dc2626;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">

        <!-- <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
                <h2 class="fw-bold">Dashboard 📊</h2>
                <button onclick="toggleDark()" class="btn btn-dark">🌙</button>
        </div>

        <!-- MÉTRICAS -->
        <div class="row mb-4 g-3">

            <div class="col-md-3 col-sm-6">
                <div class="card-metric">
                    <div class="metric-title">Ingresos Hoy</div>
                    <div class="metric-value">
                        ${{ number_format($ingresosHoy ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card-metric">
                    <div class="metric-title">Ingresos Totales</div>
                    <div class="metric-value">
                        ${{ number_format($ingresosTotales ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card-metric">
                    <div class="metric-title">Ventas del Mes</div>
                    <div class="metric-value">{{ $ventasMes ?? 0 }}</div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card-metric">
                    <div class="metric-title">Productos</div>
                    <div class="metric-value">{{ $productosActivos ?? 0 }}</div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card-metric">
                    <div class="metric-title">Clientes</div>
                    <div class="metric-value">{{ $clientes ?? 0 }}</div>
                </div>
            </div>

        </div>

        <!-- GRÁFICO + TOP PRODUCTOS -->
        <div class="row mb-4 g-3">

            <div class="col-md-8">
                <div class="card dashboard-card">
                    <div class="card-header">Ventas del Mes</div>
                    <div class="card-body">
                        <canvas id="chartVentas"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-header">Top Productos 🔥</div>
                    <div class="card-body">
                        <ul class="list-group">
                            @forelse ($topProductos ?? [] as $item)
                                <li class="list-group-item d-flex justify-content-between">
                                    {{ $item->producto->nombre ?? 'Producto' }}
                                    <span>{{ $item->total_vendidos }}</span>
                                </li>
                            @empty
                                <li class="list-group-item">Sin datos</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

        </div>

        <!-- PRODUCTOS + STOCK -->
        <div class="row mb-4 g-3">

            <div class="col-md-6">
                <div class="card dashboard-card">
                    <div class="card-header">Productos Recientes</div>
                    <div class="card-body">
                        <ul class="list-group">
                            @forelse ($productosRecientes ?? [] as $producto)
                                <li class="list-group-item d-flex justify-content-between">
                                    {{ $producto->nombre }}
                                    <span>${{ number_format($producto->precio, 0, ',', '.') }}</span>
                                </li>
                            @empty
                                <li class="list-group-item">Sin productos</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card dashboard-card">
                    <div class="card-header">⚠️ Stock Bajo</div>
                    <div class="card-body">
                        <ul class="list-group">
                            @forelse ($productosBajoStock ?? [] as $producto)
                                <li class="list-group-item d-flex justify-content-between">
                                    {{ $producto->nombre }}
                                    <span style="color:red;">{{ $producto->stock }}</span>
                                </li>
                            @empty
                                <li class="list-group-item">Todo OK</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

        </div>

        <!-- VENTAS -->
        <div class="card dashboard-card mb-4">
            <div class="card-header">Últimas Ventas</div>
            <div class="card-body">

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Detalle</th>
                            <th>Estado</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($ultimasOrdenes ?? [] as $orden)
                            <tr>
                                <td>#{{ $orden->id }}</td>
                                <td>
                                    <strong>{{ $orden->detalles->count() }} productos</strong>
                                    <div style="font-size:12px;">
                                        @foreach ($orden->detalles as $detalle)
                                            {{ $detalle->producto->nombre ?? 'Producto' }}
                                            (x{{ $detalle->cantidad }})
                                            <br>
                                        @endforeach
                                    </div>
                                </td>

                                <td>
                                    <span
                                        class="badge-status
                                    @if (($orden->estado ?? '') == 'completada') bg-success-soft
                                    @elseif(($orden->estado ?? '') == 'pendiente') bg-warning-soft
                                    @else bg-danger-soft @endif">
                                        {{ ucfirst($orden->estado ?? 'completada') }}
                                    </span>
                                </td>

                                <td>
                                    ${{ number_format($orden->total ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No hay ventas</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>
        </div>

    </div> -->

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function toggleDark() {
            document.body.classList.toggle('dark-mode');
        }

        document.addEventListener('DOMContentLoaded', function() {

            const ventasData = @json($ventasPorDia ?? []);

            const labels = ventasData.map(v => v.fecha);
            const data = ventasData.map(v => v.total);

            new Chart(document.getElementById('chartVentas'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Ventas',
                        data: data,
                        tension: 0.4,
                        fill: true,
                        backgroundColor: 'rgba(59,130,246,0.2)',
                        borderColor: '#3b82f6',
                        borderWidth: 2,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

        });
    </script>
@endpush
