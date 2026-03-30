@extends('template')

@section('title', 'Ventas')

@section('content')

    <div class="container">

        <h2 class="mb-4 text-2xl font-bold">Listado de Ventas</h2>

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
                        <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                        <td>${{ number_format($venta->total, 0, ',', '.') }}</td>
                        <td class="flex gap-2">
                            <a href="{{ route('ventas.show', $venta->id) }}" class="btn btn-info btn-sm">
                                Ver
                            </a>

                            <form action="{{ route('ventas.destroy', $venta->id) }}" method="POST"
                                onsubmit="return confirm('¿Seguro que quieres eliminar esta venta?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

    </div>

@endsection
