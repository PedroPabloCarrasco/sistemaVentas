@extends('template')

@section('title', 'Nueva Venta')

@section('content')

    <div class="container">
        <h2>Nueva Venta</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('ventas.store') }}">
            @csrf

            <div class="row mb-3">
                <div class="col">
                    <label>Producto</label>
                    <select id="producto" class="form-control">
                        @foreach ($productos as $producto)
                            <option value="{{ $producto->id }}" data-precio="{{ $producto->precio }}">
                                {{ $producto->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col">
                    <label>Cantidad</label>
                    <input type="number" id="cantidad" class="form-control" value="1">
                </div>

                <div class="col d-flex align-items-end">
                    <button type="button" onclick="agregarProducto()" class="btn btn-primary">
                        Agregar
                    </button>
                </div>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody id="lista"></tbody>
            </table>

            <h4>Total: $<span id="total">0</span></h4>

            <input type="hidden" name="productos" id="productosInput">

            <button class="btn btn-success">Guardar Venta</button>
        </form>
    </div>

    <script>
        let productos = [];

        function agregarProducto() {
            let select = document.getElementById('producto');
            let cantidad = parseInt(document.getElementById('cantidad').value);

            let id = select.value;
            let nombre = select.options[select.selectedIndex].text;
            let precio = parseFloat(select.options[select.selectedIndex].dataset.precio);

            if (cantidad <= 0) {
                alert("Cantidad inválida");
                return;
            }

            let existente = productos.find(p => p.id == id);

            if (existente) {
                existente.cantidad += cantidad;
            } else {
                productos.push({
                    id,
                    cantidad
                });
            }

            renderTabla();
        }

        function renderTabla() {
            let lista = document.getElementById('lista');
            lista.innerHTML = "";

            let total = 0;

            productos.forEach(p => {

                let select = document.getElementById('producto');
                let option = [...select.options].find(o => o.value == p.id);

                let nombre = option.text;
                let precio = parseFloat(option.dataset.precio);

                let subtotal = precio * p.cantidad;
                total += subtotal;

                lista.innerHTML += `
            <tr>
                <td>${nombre}</td>
                <td>${p.cantidad}</td>
                <td>${precio}</td>
                <td>${subtotal}</td>
            </tr>
        `;
            });

            document.getElementById('total').innerText = total;
            document.getElementById('productosInput').value = JSON.stringify(productos);
        }
    </script>

@endsection
