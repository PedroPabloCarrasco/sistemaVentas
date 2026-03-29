@extends('template')

@section('title', 'Panel de Control')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />

    <style>
        body {
            background-color: #f4f6f9;
        }

        .card-metric {
            border-radius: 12px;
            padding: 20px;
            color: #333;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            background: #fff;
            transition: 0.3s;
        }

        .card-metric:hover {
            transform: translateY(-3px);
        }

        .metric-title {
            font-size: 14px;
            color: #888;
        }

        .metric-value {
            font-size: 26px;
            font-weight: bold;
        }

        .dashboard-card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .table-modern th {
            background: #f8f9fa;
        }

        .badge-status {
            padding: 5px 10px;
            border-radius: 8px;
            font-size: 12px;
        }

        .bg-success-soft {
            background: #e6f7ee;
            color: #28a745;
        }

        .bg-warning-soft {
            background: #fff4e5;
            color: #ff9800;
        }

        .bg-danger-soft {
            background: #fdecea;
            color: #dc3545;
        }
    </style>
@endpush


@section('content')

    <div class="container-fluid px-4">

        <h2 class="mt-4 mb-4">Bienvenido, Admin 👋</h2>

        <!-- MÉTRICAS -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card-metric">
                    <div class="metric-title">Ingresos de Hoy</div>
                    <div class="metric-value">$1,250</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-metric">
                    <div class="metric-title">Ventas del Mes</div>
                    <div class="metric-value">320</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-metric">
                    <div class="metric-title">Productos Activos</div>
                    <div class="metric-value">150</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-metric">
                    <div class="metric-title">Clientes</div>
                    <div class="metric-value">1,280</div>
                </div>
            </div>
        </div>

        <!-- GRÁFICO -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card dashboard-card">
                    <div class="card-header">
                        Ventas del Último Mes
                    </div>
                    <div class="card-body">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- PRODUCTOS RECIENTES -->
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-header">
                        Productos Recientes
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between">
                                Camiseta Negra <span>$20</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                Audífonos Bluetooth <span>$45</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                Zapatillas <span>$65</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                Reloj Smart <span>$120</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABLA DE ÓRDENES -->
        <div class="card dashboard-card mb-4">
            <div class="card-header">
                Últimas Órdenes
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-modern">
                    <thead>
                        <tr>
                            <th>#Orden</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#1023</td>
                            <td>Juan Pérez</td>
                            <td><span class="badge-status bg-success-soft">Completada</span></td>
                            <td>$150</td>
                        </tr>
                        <tr>
                            <td>#1022</td>
                            <td>Ana Gómez</td>
                            <td><span class="badge-status bg-warning-soft">Pendiente</span></td>
                            <td>$85</td>
                        </tr>
                        <tr>
                            <td>#1021</td>
                            <td>Carlos Ruiz</td>
                            <td><span class="badge-status bg-danger-soft">Cancelada</span></td>
                            <td>$60</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection


@push('js')
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>

    <script>
        var ctx = document.getElementById("myAreaChart").getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['1', '5', '10', '15', '20', '25', '30'],
                datasets: [{
                    label: 'Ventas',
                    data: [200, 400, 300, 500, 700, 600, 900],
                    fill: true
                }]
            }
        });
    </script>

    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush
