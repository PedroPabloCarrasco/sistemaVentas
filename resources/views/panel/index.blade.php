@extends('template')

@section('title', 'Dashboard')

@push('css')
    <style>
        body {
            background: #f1f5f9;
            font-family: 'Inter', sans-serif;
        }

        /* DARK MODE */
        .dark-mode {
            background: #0f172a !important;
            color: #e2e8f0;
        }

        .dark-mode .card,
        .dark-mode .sidebar,
        .dark-mode .topbar {
            background: #1e293b !important;
            color: #e2e8f0;
        }

        /* LAYOUT */
        .layout {
            display: flex;
        }

        /* SIDEBAR */
        .sidebar {
            width: 240px;
            height: 100vh;
            background: white;
            padding: 20px;
            position: fixed;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        }

        .sidebar h2 {
            font-size: 20px;
            font-weight: bold;
        }

        .sidebar a {
            display: block;
            padding: 10px;
            border-radius: 10px;
            color: #374151;
            margin-top: 10px;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: #e0e7ff;
        }

        /* CONTENT */
        .content {
            margin-left: 260px;
            padding: 20px;
            width: 100%;
        }

        /* TOPBAR PREMIUM */
        .topbar {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            padding: 14px 18px;
            border-radius: 16px;
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.04);
            transition: 0.3s;
        }

        /* CONTENEDOR FILTROS */
        .filter-container {
            display: flex;
            background: #f1f5f9;
            padding: 4px;
            border-radius: 12px;
            gap: 4px;
        }

        /* BOTONES FILTRO */
        .filter-btn {
            border: none;
            background: transparent;
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 500;
            font-size: 14px;
            color: #64748b;
            transition: all 0.25s ease;
            position: relative;
        }

        .filter-btn:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        /* ACTIVO */
        .filter-btn.active {
            background: #6366f1;
            color: white;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
        }

        /* BOTÓN DARK MODE */
        .theme-toggle {
            background: #0f172a;
            color: white;
            border: none;
            padding: 10px 12px;
            border-radius: 12px;
            transition: all 0.25s ease;
        }

        .theme-toggle:hover {
            background: #1e293b;
            transform: scale(1.08);
        }

        /* USER BADGE */
        .user-badge {
            width: 40px;
            height: 40px;
            background: #e0e7ff;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 18px;
        }

        /* DARK MODE */
        .dark-mode .topbar {
            background: rgba(30, 41, 59, 0.8);
            border-color: #334155;
        }

        .dark-mode .filter-container {
            background: #334155;
        }

        .dark-mode .filter-btn {
            color: #cbd5f5;
        }

        .dark-mode .filter-btn:hover {
            background: #475569;
        }

        .dark-mode .filter-btn.active {
            background: #818cf8;
        }

        .dark-mode .user-badge {
            background: #334155;
        }

        .dark-mode .theme-toggle {
            background: #f1f5f9;
            color: #0f172a;
        }

        .filter-btn.active {
            background: #3b82f6;
            color: white;
        }

        /* METRICS */
        .metric-card {
            background: white;
            border-radius: 16px;
            padding: 18px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            position: relative;
            transition: 0.25s;
        }

        .metric-card:hover {
            transform: translateY(-4px);
        }

        .metric-icon {
            position: absolute;
            right: 15px;
            top: 15px;
            opacity: 0.2;
        }

        .metric-value {
            font-size: 24px;
            font-weight: bold;
        }

        .metric-trend.up {
            color: #16a34a;
        }

        .metric-trend.down {
            color: #dc2626;
        }

        /* CARDS */
        .card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background: none;
            font-weight: 600;
        }

        /* TABLE */
        table tbody tr {
            background: #f9fafb;
        }

        .badge {
            border-radius: 999px;
            padding: 5px 10px;
        }

        /* DARK FIXES */
        .dark-mode .metric-card,
        .dark-mode .card,
        .dark-mode .topbar {
            background: #1e293b;
        }
    </style>
@endpush

@section('content')

    <div class="layout">

        <!-- SIDEBAR -->
        <div class="sidebar">
            <h2>MiApp</h2>

            <a href="#">📊 Dashboard</a>
            <a href="#">🛒 Ventas</a>
            <a href="#">📦 Productos</a>
            <a href="#">👤 Clientes</a>
            <a href="#">⚙️ Configuración</a>
        </div>

        <!-- CONTENT -->
        <div class="content">

            <!-- TOPBAR -->
            <div class="topbar d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">

                <!-- Filtros -->
                <div class="filter-container">
                    <button class="filter-btn active">
                        <span>Hoy</span>
                    </button>
                    <button class="filter-btn">
                        <span>Semana</span>
                    </button>
                    <button class="filter-btn">
                        <span>Mes</span>
                    </button>
                </div>

                <!-- Acciones -->
                <div class="d-flex align-items-center gap-2">

                    <button onclick="toggleDark()" class="theme-toggle">
                        🌙
                    </button>

                    <div class="user-badge">
                        <span>👤</span>
                    </div>

                </div>

            </div>

            <!-- METRICS -->
            <div class="row g-3 mb-4">

                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-icon">💰</div>
                        <div>Ingresos Hoy</div>
                        <div class="metric-value">$2.340</div>
                        <div class="metric-trend up">+12%</div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-icon">📈</div>
                        <div>Ingresos Totales</div>
                        <div class="metric-value">$78.920</div>
                        <div class="metric-trend up">+8%</div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-icon">🛒</div>
                        <div>Ventas</div>
                        <div class="metric-value">320</div>
                        <div class="metric-trend down">-5%</div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-icon">👤</div>
                        <div>Clientes</div>
                        <div class="metric-value">1.450</div>
                        <div class="metric-trend up">+4%</div>
                    </div>
                </div>

            </div>

            <!-- GRAPH + TOP -->
            <div class="row mb-4">

                <div class="col-md-8">
                    <div class="card p-3">
                        <h6>Ventas del Mes</h6>
                        <canvas id="chartVentas"></canvas>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card p-3">
                        <h6>Top Productos 🔥</h6>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between">Auriculares <span>120</span></li>
                            <li class="list-group-item d-flex justify-content-between">Reloj <span>95</span></li>
                            <li class="list-group-item d-flex justify-content-between">Mochila <span>78</span></li>
                        </ul>
                    </div>
                </div>

            </div>

            <!-- TABLE -->
            <div class="card p-3">
                <h6>Últimas Ventas</h6>

                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Detalle</th>
                            <th>Estado</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>#1023</td>
                            <td>3 productos</td>
                            <td><span class="badge bg-success">Completada</span></td>
                            <td>$150</td>
                        </tr>

                        <tr>
                            <td>#1022</td>
                            <td>2 productos</td>
                            <td><span class="badge bg-warning">Pendiente</span></td>
                            <td>$85</td>
                        </tr>

                    </tbody>
                </table>
            </div>

        </div>

    </div>

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        function toggleDark() {
            document.body.classList.toggle('dark-mode');

            localStorage.setItem('darkMode',
                document.body.classList.contains('dark-mode'));
        }

        // PERSISTENCIA DARK MODE
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode');
        }

        // CHART
        new Chart(document.getElementById('chartVentas'), {
            type: 'line',
            data: {
                labels: [1, 2, 3, 4, 5, 6, 7],
                datasets: [{
                    data: [1000, 2000, 1500, 2500, 3000, 2300, 3400],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endpush
