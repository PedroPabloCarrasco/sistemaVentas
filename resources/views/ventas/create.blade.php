@extends('template')

@section('title', 'Nueva Venta')

@push('css')
    <style>
        body {
            background: linear-gradient(135deg, #eef2ff, #f8fafc);
        }

        /* Boleta */
        .boleta-card {
            max-width: 700px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .boleta-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .boleta-header h2 {
            margin: 0;
            font-size: 26px;
            font-weight: bold;
            color: #111827;
        }

        .boleta-header p {
            color: #6b7280;
            margin-top: 5px;
            font-size: 14px;
        }

        .boleta-info {
            margin-bottom: 20px;
            font-size: 14px;
            color: #374151;
        }

        .boleta-info strong {
            color: #111827;
        }

        .boleta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .boleta-table th,
        .boleta-table td {
            padding: 12px 15px;
            border: 1px solid #e5e7eb;
        }

        .boleta-table th {
            background: #111827;
            color: white;
            text-align: left;
        }

        .boleta-total {
            display: flex;
            justify-content: flex-end;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #111827;
        }

        .boleta-actions {
            text-align: center;
        }

        .boleta-actions .btn {
            margin: 0 10px;
            padding: 10px 25px;
            font-size: 14px;
            border-radius: 8px;
            transition: .25s;
        }

        .boleta-actions .btn:hover {
            transform: translateY(-2px);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn .3s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-content {
            background-color: #fefefe;
            margin: 80px auto;
            padding: 20px;
            border-radius: 16px;
            width: 90%;
            max-width: 750px;
            position: relative;
        }

        .close-modal {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-modal:hover {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        @media print {
            body {
                background: white !important;
            }

            .modal {
                display: block !important;
                background: white !important;
            }

            .close-modal,
            .boleta-actions .btn {
                display: none !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <h2>Nueva Venta</h2>
        <div id="alert-container"></div>

        <form id="ventaForm">
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
                    <input type="number" id="cantidad" class="form-control" value="1" min="1">
                </div>
                <div class="col d-flex align-items-end">
                    <button type="button" onclick="agregarProducto()" class="btn btn-primary">Agregar</button>
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

            <button type="submit" class="btn btn-success">Guardar Venta</button>
        </form>
    </div>

    <!-- Modal Boleta -->
    <div id="boletaModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="cerrarModal()">&times;</span>
            <div class="boleta-card" id="boletaContent"></div>
        </div>
    </div>

    <script>
        let productos = [];

        function formatearCLP(valor) {
            return Number(valor).toLocaleString('es-CL', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }

        function agregarProducto() {
            let select = document.getElementById('producto');
            let cantidad = parseInt(document.getElementById('cantidad').value);
            if (cantidad < 1) return;

            let id = select.value;
            let existente = productos.find(p => p.id == id);
            if (existente) existente.cantidad += cantidad;
            else productos.push({
                id,
                cantidad
            });

            renderTabla();
        }

        function renderTabla() {
            let lista = document.getElementById('lista');
            lista.innerHTML = "";

            let total = 0;
            productos.forEach(p => {
                let option = [...document.getElementById('producto').options].find(o => o.value == p.id);
                let nombre = option.text;
                let precio = parseFloat(option.dataset.precio);
                let subtotal = precio * p.cantidad;
                total += subtotal;

                lista.innerHTML += `<tr>
            <td>${nombre}</td>
            <td>${p.cantidad}</td>
            <td>${formatearCLP(precio)}</td>
            <td>${formatearCLP(subtotal)}</td>
        </tr>`;
            });

            document.getElementById('total').innerText = formatearCLP(total);
            document.getElementById('productosInput').value = JSON.stringify(productos);
        }

        document.getElementById('ventaForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            fetch("{{ route('ventas.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(async res => {
                    const text = await res.text();

                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error("Laravel devolvió HTML:", text);
                        throw new Error("Respuesta no es JSON");
                    }
                })
                .then(data => {
                    if (data.success) {
                        mostrarBoleta(data.venta);
                        productos = [];
                        renderTabla();

                        document.getElementById('alert-container').innerHTML =
                            `<div class="alert alert-success">${data.message}</div>`;
                    } else {
                        document.getElementById('alert-container').innerHTML =
                            `<div class="alert alert-danger">${data.error ?? 'Error desconocido'}</div>`;
                    }
                })
                .catch(err => {
                    console.error(err);

                    document.getElementById('alert-container').innerHTML =
                        `<div class="alert alert-danger">Error real del servidor (revisa consola)</div>`;
                });
        });

        function mostrarBoleta(venta) {
            let html = `
    <div class="boleta-header">
        <h2>Boleta de Venta</h2>
        <p>${new Date(venta.created_at).toLocaleString('es-CL')}</p>
    </div>
    <div class="boleta-info">
        <div><strong>Cliente:</strong> ${venta.cliente ?? 'Consumidor Final'}</div>
        <div><strong>Venta ID:</strong> #${venta.id}</div>
    </div>
    <table class="boleta-table">
        <thead>
            <tr>
                <th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            ${venta.detalles.map(function(d){
                return `
                            <tr>
                                <td>${d.producto}</td>
                                <td>${d.cantidad}</td>
                                <td>${formatearCLP(d.precio)}</td>
                                <td>${formatearCLP(d.precio * d.cantidad)}</td>
                            </tr>
                        `;
            }).join('')}
        </tbody>
    </table>
    <div class="boleta-total">
        Total: $${formatearCLP(venta.total)}
    </div>
    <div class="boleta-actions">
        <button onclick="window.print()" class="btn btn-primary">🖨️ Imprimir Boleta</button>
        <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Volver</a>
    </div>`;

            document.getElementById('boletaContent').innerHTML = html;
            document.getElementById('boletaModal').style.display = 'block';
        }

        function cerrarModal() {
            document.getElementById('boletaModal').style.display = 'none';
        }
        window.onclick = function(event) {
            if (event.target == document.getElementById('boletaModal')) cerrarModal();
        }
    </script>
@endsection
