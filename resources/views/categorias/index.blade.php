@extends('template')

@section('title', 'Categorías')

@push('css')
@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Categorías</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Categorías</li>
        </ol>

        <a href="{{ route('categorias.create') }}" class="btn btn-primary mb-2">
            <i class="fa-solid fa-plus"></i> Agregar nueva categoría
        </a>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Tabla de categorías
            </div>

            <div class="card-body">
                <table id="datatablesSimple">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Característica</th>
                            <th>Desc. Característica</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($categorias as $categoria)
                            <tr>
                                <td>{{ $categoria->id }}</td>
                                <td>{{ $categoria->nombre }}</td>
                                <td>{{ $categoria->descripcion }}</td>

                                {{-- Relación --}}
                                <td>{{ $categoria->caracteristicas->nombre ?? '-' }}</td>
                                <td>{{ $categoria->caracteristicas->descripcion ?? '-' }}</td>

                                <td>
                                    <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-warning btn-sm">
                                        Editar
                                    </a>

                                    <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('¿Eliminar categoría?')">
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

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>

    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush
