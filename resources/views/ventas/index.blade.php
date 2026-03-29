@extends('template')

@section('title', 'Ventas')

@section('content')

    <div class="container">

        <h2>Listado de Ventas</h2>

        <a href="{{ route('ventas.create') }}" class="btn btn-primary mb-3">
            Nueva Venta
        </a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Acción</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($ventas as $venta)
                    <tr>
                        <td>{{ $venta->id }}</td>
                        <td>{{ $venta->created_at }}</td>
                        <td>${{ $venta->total }}</td>
                        <td>
                            <a href="{{ route('ventas.show', $venta->id) }}" class="btn btn-info btn-sm">
                                Ver
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

    </div>

@endsection
