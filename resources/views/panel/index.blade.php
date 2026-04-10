@extends('template')

@section('title', 'Panel - Vitaco Ventas')

@section('content')

    <div class="container-fluid px-4">

        <!-- HEADER + ACCIONES -->
        <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
            <div>
                <h1 class="fw-bold mb-0">Vitaco Ventas</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active">Panel de control</li>
                </ol>
            </div>

            <!-- 🔥 BOTONES -->
            <div class="d-flex gap-2">

                <!-- Ver cierres -->
                <a href="{{ route('cierres.index') }}" class="btn btn-dark">
                    📊 Cierres
                </a>

                <!-- Cerrar mes -->
                <form action="{{ route('ventas.cierre') }}" method="POST">
                    @csrf
                    <button class="btn btn-warning"
                        onclick="return confirm('¿Seguro que deseas cerrar el mes actual? Esta acción no se puede deshacer.')">
                        🔒 Cerrar Mes
                    </button>
                </form>

            </div>
        </div>

        <!-- CARDS RESUMEN -->
        <div class="row">

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-primary text-white rounded-4 shadow-sm p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase small">Ingresos Hoy</h6>
                            <h3 class="fw-bold mb-0">
                                $ {{ number_format($ingresosHoy ?? 0, 0, ',', '.') }}
                            </h3>
                        </div>
                        <i class="fas fa-cash-register fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-success text-white rounded-4 shadow-sm p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase small">Productos</h6>
                            <h3 class="fw-bold mb-0">
                                {{ $productosActivos ?? 0 }}
                            </h3>
                        </div>
                        <i class="fas fa-box fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-warning text-dark rounded-4 shadow-sm p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase small">Stock Bajo</h6>
                            <h3 class="fw-bold mb-0">
                                {{ $stockBajo ?? 0 }}
                            </h3>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-dark text-white rounded-4 shadow-sm p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase small">Ingresos Totales</h6>
                            <h3 class="fw-bold mb-0">
                                $ {{ number_format($ingresosTotales ?? 0, 0, ',', '.') }}
                            </h3>
                        </div>
                        <i class="fas fa-chart-line fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>

        </div>

        <!-- ACCESOS RÁPIDOS -->
        <h5 class="mb-3 fw-semibold">Accesos rápidos</h5>

        <div class="row">

            <div class="col-md-3 mb-4">
                <a href="{{ route('ventas.create') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 hover-card">
                        <i class="fas fa-barcode fa-2x mb-3 text-primary"></i>
                        <h6 class="fw-bold mb-0">Nueva Venta</h6>
                    </div>
                </a>
            </div>

            <div class="col-md-3 mb-4">
                <a href="{{ route('productos.index') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 hover-card">
                        <i class="fas fa-boxes fa-2x mb-3 text-success"></i>
                        <h6 class="fw-bold mb-0">Productos</h6>
                    </div>
                </a>
            </div>

            <div class="col-md-3 mb-4">
                <a href="{{ route('categorias.index') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 hover-card">
                        <i class="fas fa-tags fa-2x mb-3 text-warning"></i>
                        <h6 class="fw-bold mb-0">Categorías</h6>
                    </div>
                </a>
            </div>

            <div class="col-md-3 mb-4">
                <a href="{{ route('cierres.index') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 hover-card">
                        <i class="fas fa-chart-pie fa-2x mb-3 text-dark"></i>
                        <h6 class="fw-bold mb-0">Cierres Mensuales</h6>
                    </div>
                </a>
            </div>

        </div>

    </div>

@endsection

@push('css')
    <style>
        .hover-card {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
        }
    </style>
@endpush
