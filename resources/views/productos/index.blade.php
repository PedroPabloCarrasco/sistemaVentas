@extends('template')

@section('title', 'Productos')

@section('content')

    <div class="container-fluid px-4">

        <h1 class="mt-4">Productos</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Productos</li>
        </ol>

        <!-- BOTÓN -->
        <a href="{{ route('productos.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Nuevo Producto
        </a>

        <!-- MENSAJE -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                Lista de productos
            </div>

            <div class="card-body">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Categoría</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($productos as $producto)
                            <tr>
                                <td>{{ $producto->id }}</td>
                                <td>{{ $producto->nombre }}</td>
                                <td>${{ $producto->precio }}</td>
                                <td>{{ $producto->stock }}</td>

                                <!-- RELACIÓN -->
                                <td>{{ $producto->categoria->nombre ?? '-' }}</td>

                                <td>
                                    @if ($producto->estado == 1)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-warning btn-sm">
                                        Editar
                                    </a>

                                    <form action="{{ route('productos.destroy', $producto->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger btn-sm">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>
        </div>

    </div>

@endsection
