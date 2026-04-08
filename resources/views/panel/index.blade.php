@extends('template')

@section('title', 'Panel - Vitaco Ventas')

@section('content')

    <div class="container-fluid px-4">

        <h1 class="mt-4">Vitaco Ventas</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Panel de control</li>
        </ol>

        <!-- CARDS RESUMEN -->
        <div class="row">

            <!-- Ventas -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-primary text-white rounded-4 shadow-sm p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Ventas Hoy</h6>
                            <h3>$ {{ number_format($ingresosHoy, 0, ',', '.') }}</h3>
                        </div>
                        <i class="fas fa-cash-register fa-2x"></i>
                    </div>
                </div>
            </div>

            <!-- Productos -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-success text-white rounded-4 shadow-sm p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Productos</h6>
                            <h3>{{ $productosActivos }}</h3>
                        </div>
                        <i class="fas fa-box fa-2x"></i>
                    </div>
                </div>
            </div>

            <!-- Stock bajo -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-warning text-dark rounded-4 shadow-sm p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Stock Bajo</h6>
                            <h3>{{ $stockBajo ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>

            <!-- Ventas totales -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-dark text-white rounded-4 shadow-sm p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Total Ventas</h6>
                            <h3>$ {{ $totalVentas ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                </div>
            </div>

        </div>

        <!-- ACCESOS RÁPIDOS -->
        <div class="row">

            <div class="col-md-4 mb-4">
                <a href="{{ route('ventas.create') }}" class="text-decoration-none">
                    <div class="card border-0 shadow rounded-4 p-4 text-center">
                        <i class="fas fa-barcode fa-2x mb-3"></i>
                        <h5>Nueva Venta</h5>
                    </div>
                </a>
            </div>

            <div class="col-md-4 mb-4">
                <a href="{{ route('productos.index') }}" class="text-decoration-none">
                    <div class="card border-0 shadow rounded-4 p-4 text-center">
                        <i class="fas fa-boxes fa-2x mb-3"></i>
                        <h5>Productos</h5>
                    </div>
                </a>
            </div>

            <div class="col-md-4 mb-4">
                <a href="{{ route('categorias.index') }}" class="text-decoration-none">
                    <div class="card border-0 shadow rounded-4 p-4 text-center">
                        <i class="fas fa-tags fa-2x mb-3"></i>
                        <h5>Categorías</h5>
                    </div>
                </a>
            </div>

        </div>

    </div>

@endsection
