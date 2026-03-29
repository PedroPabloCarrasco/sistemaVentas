@extends('template')

@section('title', 'Editar Producto')

@section('content')
    <div class="max-w-4xl mx-auto mt-8 p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
        <!-- Título -->
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Editar Producto</h1>

        <!-- Mensajes de error -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulario -->
        <form action="{{ route('productos.update', $producto->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <table class="w-full border border-gray-300 rounded-lg">
                <tbody>
                    <tr class="bg-gray-50">
                        <td class="p-3 font-medium text-gray-700">Nombre:</td>
                        <td class="p-3">
                            <input type="text" name="nombre" id="nombre"
                                value="{{ old('nombre', $producto->nombre) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </td>
                    </tr>

                    <tr class="bg-white">
                        <td class="p-3 font-medium text-gray-700">Precio:</td>
                        <td class="p-3">
                            <input type="text" name="precio" id="precio"
                                value="{{ old('precio', number_format($producto->precio, 0, ',', '.')) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                oninput="this.value = this.value.replace(/\D/g,'').replace(/\B(?=(\d{3})+(?!\d))/g, '.');">
                        </td>
                    </tr>

                    <tr class="bg-gray-50">
                        <td class="p-3 font-medium text-gray-700">Stock:</td>
                        <td class="p-3">
                            <input type="text" name="stock" id="stock"
                                value="{{ old('stock', number_format($producto->stock, 0, ',', '.')) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                oninput="this.value = this.value.replace(/\D/g,'').replace(/\B(?=(\d{3})+(?!\d))/g, '.');">
                        </td>
                    </tr>

                    <tr class="bg-white">
                        <td class="p-3 font-medium text-gray-700">Categoría:</td>
                        <td class="p-3">
                            <select name="categoria_id" id="categoria_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}"
                                        {{ $producto->categoria_id == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Botones -->
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('productos.index') }}"
                    class="px-5 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition font-medium">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-5 py-2 bg-yellow-400 text-white rounded hover:bg-yellow-500 transition font-semibold">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
@endsection
