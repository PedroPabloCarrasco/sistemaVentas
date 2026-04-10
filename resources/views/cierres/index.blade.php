@extends('template')

@section('title', 'Cierres Mensuales')

@section('content')

    <div class="container py-4">

        <h2 class="mb-4 fw-bold">📊 Historial de Cierres Mensuales</h2>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body">

                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Mes</th>
                            <th>Año</th>
                            <th>Total Ventas</th>
                            <th>IVA</th>
                            <th>Cantidad Ventas</th>
                            <th>Fecha Cierre</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($cierres as $cierre)
                            <tr>
                                <td class="fw-semibold">
                                    {{ ucfirst($cierre->mes_nombre) }}
                                </td>

                                <td>{{ $cierre->anio }}</td>

                                <td class="text-success fw-bold">
                                    {{ $cierre->total_ventas_formateado }}
                                </td>

                                <td>
                                    {{ $cierre->total_impuesto_formateado }}
                                </td>

                                <td>
                                    <span class="badge bg-primary">
                                        {{ $cierre->cantidad_ventas }}
                                    </span>
                                </td>

                                <td>
                                    {{ $cierre->created_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No hay cierres registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>
        </div>

    </div>

@endsection
