@extends('template')

@section('title', 'Crear Producto')

@section('content')

    <div class="container-fluid px-4">

        <h1 class="mt-4">Crear Producto</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a></li>
            <li class="breadcrumb-item active">Crear</li>
        </ol>

        <div class="card">
            <div class="card-header">
                Formulario de Producto
            </div>

            <div class="card-body">

                {{-- ERRORES --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('productos.store') }}" method="POST">
                    @csrf

                    <div class="row">

                        <!-- Nombre -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                        </div>

                        <!-- Código de Barra -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Código de Barra</label>
                            <input type="text" name="codigo_barra" class="form-control" value="{{ old('codigo_barra') }}"
                                required>
                        </div>

                        <!-- Precio -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Precio</label>
                            <input type="number" name="precio" class="form-control" step="0.01"
                                value="{{ old('precio') }}" required>
                        </div>

                        <!-- Stock -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Stock</label>
                            <input type="number" name="stock" class="form-control" value="{{ old('stock') }}" required>
                        </div>

                    </div>

                    <!-- Descripción -->
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
                    </div>

                    <!-- Categoría -->
                    <div class="mb-3">
                        <label class="form-label">Categoría</label>
                        <select name="categoria_id" class="form-select" required>
                            <option value="">Seleccione una categoría</option>

                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}"
                                    {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <!-- Estado -->
                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="1" {{ old('estado') == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ old('estado') == 0 ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>

                    <!-- BOTONES -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Guardar
                        </button>

                        <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>

@endsection
