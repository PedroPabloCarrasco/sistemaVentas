@extends('template')

@section('title', 'Productos')

@section('content')

    <div class="container-fluid px-4">

        <div class="container-fluid px-4">

            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center py-3 border-bottom">

                <h2 class="fw-bold m-0">MiApp</h2>

                <div class="d-flex gap-3 align-items-center">

                    <a href="#" class="nav-link active">📊 Dashboard</a>
                    <a href="#" class="nav-link">🛒 Ventas</a>
                    <a href="#" class="nav-link">📦 Productos</a>
                    <a href="#" class="nav-link">👤 Clientes</a>
                    <a href="#" class="nav-link">⚙️ Configuración</a>

                    <!-- DARK MODE -->
                    <button id="darkModeToggle" class="btn btn-dark btn-sm rounded-circle">
                        🌙
                    </button>

                </div>
            </div>

            <!-- FILTROS -->
            <div class="d-flex gap-2 my-4">
                <button class="btn btn-outline-primary active">Hoy</button>
                <button class="btn btn-outline-primary">Semana</button>
                <button class="btn btn-outline-primary">Mes</button>
            </div>

            <!-- MÉTRICAS -->
            <div class="row g-4">

                <!-- CARD -->
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 metric-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Ingresos Hoy</span>
                                <span class="icon bg-success">💰</span>
                            </div>
                            <h3 class="fw-bold mt-2">$2.340</h3>
                            <span class="text-success small">▲ +12%</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 metric-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Ingresos Totales</span>
                                <span class="icon bg-primary">📈</span>
                            </div>
                            <h3 class="fw-bold mt-2">$78.920</h3>
                            <span class="text-success small">▲ +8%</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 metric-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Ventas</span>
                                <span class="icon bg-warning">🛒</span>
                            </div>
                            <h3 class="fw-bold mt-2">320</h3>
                            <span class="text-danger small">▼ -5%</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 metric-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Clientes</span>
                                <span class="icon bg-info">👤</span>
                            </div>
                            <h3 class="fw-bold mt-2">1.450</h3>
                            <span class="text-success small">▲ +4%</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- FILTROS -->
        <div class="card mb-3 shadow-sm fade-in">
            <div class="card-body d-flex gap-3 align-items-center">

                <select id="filtroEstado" class="form-select w-auto">
                    <option value="">Todos</option>
                    <option value="Activo">Activos</option>
                    <option value="Inactivo">Inactivos</option>
                </select>

            </div>
        </div>

        <!-- ALERTA -->
        @if (session('success'))
            <div class="alert alert-success shadow-sm fade-in">
                {{ session('success') }}
            </div>
        @endif

        <!-- TABLA -->
        <div class="card shadow-lg border-0 rounded-3 fade-in">
            <div class="card-body">

                <div class="table-responsive">
                    <table id="tablaProductos" class="table table-hover align-middle">

                        <thead class="table-light">
                            <tr class="text-center">
                                <th>ID</th>
                                <th class="text-start">Nombre</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Categoría</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($productos as $producto)
                                <tr class="text-center fila-animada">

                                    <td>#{{ $producto->id }}</td>

                                    <td class="text-start fw-semibold">
                                        {{ $producto->nombre }}
                                    </td>

                                    <td class="text-success fw-bold">
                                        ${{ number_format($producto->precio, 0, ',', '.') }}
                                    </td>

                                    <td>
                                        @if ($producto->stock <= 5)
                                            <span class="badge bg-danger pulse">
                                                {{ $producto->stock }} ⚠
                                            </span>
                                        @else
                                            <span class="badge bg-info text-dark">
                                                {{ $producto->stock }}
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $producto->categoria->nombre ?? 'Sin categoría' }}
                                    </td>

                                    <td>
                                        @if ($producto->estado == 1)
                                            <span class="badge bg-success estado">Activo</span>
                                        @else
                                            <span class="badge bg-secondary estado">Inactivo</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="d-flex justify-content-center gap-2">

                                            <a href="{{ route('productos.edit', $producto->id) }}"
                                                class="btn btn-warning btn-sm btn-animated">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <button class="btn btn-danger btn-sm btn-animated btn-eliminar"
                                                data-id="{{ $producto->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                            <form id="form-delete-{{ $producto->id }}"
                                                action="{{ route('productos.destroy', $producto->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                            </form>

                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </div>

@endsection


@section('styles')
    <style>
        /* ANIMACIONES */
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* BOTONES */
        .btn-animated {
            transition: all 0.2s ease;
        }

        .btn-animated:hover {
            transform: scale(1.05);
        }

        /* FILAS */
        .fila-animada:hover {
            background: #f8f9fa;
            transition: 0.2s;
        }

        /* STOCK BAJO */
        .pulse {
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }
    </style>
@endsection


@section('scripts')

    <!-- DATATABLES -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- SWEETALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {

            let tabla = $('#tablaProductos').DataTable({
                pageLength: 5,
                language: {
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ productos",
                    paginate: {
                        next: "→",
                        previous: "←"
                    }
                }
            });

            // FILTRO POR ESTADO
            $('#filtroEstado').on('change', function() {
                let valor = this.value;

                tabla.column(5).search(valor).draw();
            });

        });


        // ELIMINAR CON SWEETALERT
        document.querySelectorAll('.btn-eliminar').forEach(btn => {
            btn.addEventListener('click', function() {

                let id = this.dataset.id;

                Swal.fire({
                    title: '¿Eliminar producto?',
                    text: "No podrás revertir esto",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-delete-' + id).submit();
                    }
                });

            });
        });
    </script>

@endsection
