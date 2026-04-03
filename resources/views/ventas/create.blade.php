@extends('template')

@section('title', 'Nueva Venta')

@section('content')

    <div class="container py-4">

        <h2 class="mb-4 fw-bold">🧾 Nueva Venta</h2>

        <div id="alert-container"></div>

        <form id="ventaForm">
            @csrf

            <!-- AGREGAR PRODUCTO -->
            <div class="card app-card mb-4">
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Producto</label>
                            <select id="producto" class="form-select">
                                @foreach ($productos as $producto)
                                    <option value="{{ $producto->id }}" data-precio="{{ $producto->precio }}"
                                        data-stock="{{ $producto->stock }}">
                                        {{ $producto->nombre }} (Stock: {{ $producto->stock }}) -
                                        ${{ number_format($producto->precio, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Cantidad</label>
                            <input type="number" id="cantidad" class="form-control" value="1" min="1">
                        </div>

                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" onclick="agregarProducto()" class="btn btn-primary w-100">
                                ➕ Agregar
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <!-- LISTA PRODUCTOS -->
            <div class="card app-card">
                <div class="card-body">

                    <div id="lista" class="lista-productos"></div>

                    <!-- TOTAL -->
                    <div class="total-box mt-4">
                        <span>Total</span>
                        <strong>$<span id="total">0</span></strong>
                    </div>

                    <input type="hidden" name="productos" id="productosInput">

                    <button type="submit" class="btn btn-success w-100 mt-4">
                        💳 Finalizar Venta
                    </button>

                </div>
            </div>

        </form>

    </div>

    <!-- BOLETA -->
    <div id="modalBoleta" class="modal-boleta">
        <div class="boleta-card">

            <div id="boletaPrintable"></div>

            <div class="no-print mt-3">
                <button onclick="imprimirBoleta()" class="btn btn-dark w-100 mb-2">
                    🖨️ Imprimir
                </button>
                <button onclick="cerrarBoleta()" class="btn btn-outline-secondary w-100">
                    Cerrar
                </button>
            </div>

        </div>
    </div>

@endsection

@push('css')
    <style>
        .boleta {
            width: 280px;
            margin: 0 auto;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            color: #000;
        }

        .boleta-header {
            text-align: center;
            font-size: 12px;
        }

        .boleta-header .empresa {
            font-weight: bold;
            font-size: 14px;
        }

        .boleta-info {
            font-size: 11px;
        }

        .boleta-divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        .boleta-table {
            font-size: 11px;
        }

        .boleta-head {
            font-weight: bold;
        }

        .boleta-item {
            margin-bottom: 6px;
        }

        .item-nombre {
            font-weight: 600;
            margin-bottom: 2px;
        }

        .boleta-row {
            display: flex;
            justify-content: space-between;
        }

        .boleta-total-row {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 14px;
        }

        .boleta-footer {
            text-align: center;
            font-size: 11px;
        }




        /* FONDO */
        body {
            background: linear-gradient(135deg, #eef2ff, #f8fafc);
        }

        /* CARD */
        .app-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
        }

        /* LISTA PRODUCTOS */
        .lista-productos {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        /* ITEM */
        .item-producto {
            display: grid;
            grid-template-columns: 1fr 80px 100px 100px 40px;
            align-items: center;
            gap: 10px;
            padding: 12px;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.05);
        }

        /* INPUT */
        .item-producto input {
            width: 70px;
        }

        /* TOTAL */
        .total-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #0f172a;
            color: #fff;
            padding: 16px 20px;
            border-radius: 12px;
            font-size: 18px;
        }

        .total-box strong {
            font-size: 24px;
        }

        /* MODAL */
        .modal-boleta {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 9999;
        }

        /* BOLETA */
        .boleta-card {
            background: #fff;
            width: 360px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 14px;
        }

        /* BOLETA TEXTO */
        .boleta {
            font-family: 'Courier New', monospace;
            font-size: 13px;
        }

        .boleta-divider {
            border-top: 1px dashed #999;
            margin: 8px 0;
        }

        .boleta-row {
            display: flex;
            justify-content: space-between;
        }

        .boleta-total {
            font-weight: bold;
            font-size: 15px;
        }

        @media print {

            /* RESET TOTAL */
            body * {
                visibility: hidden !important;
            }

            /* MOSTRAR SOLO BOLETA */
            #boletaPrintable,
            #boletaPrintable * {
                visibility: visible !important;
            }

            /* POSICIONAR BOLETA */
            #boletaPrintable {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                padding: 10px;
                background: #fff;
            }

            /* OCULTAR BOTONES SIEMPRE */
            .no-print {
                display: none !important;
            }

            #boletaPrintable {
                width: 280px;
                margin: 0 auto;
                font-size: 12px;
            }

        }
    </style>
@endpush

@push('js')
    <script>
        let productos = [];

        function clp(n) {
            return Number(n).toLocaleString('es-CL');
        }

        function agregarProducto() {

            let select = document.getElementById('producto');
            let cantidad = parseInt(document.getElementById('cantidad').value);

            if (cantidad < 1) return;

            let option = select.options[select.selectedIndex];
            let id = select.value;
            let stock = parseInt(option.dataset.stock || 999999);

            let existente = productos.find(p => p.id == id);

            if (existente) {

                if (existente.cantidad + cantidad > stock) {
                    alert('❌ Stock insuficiente');
                    return;
                }

                existente.cantidad += cantidad;

            } else {

                if (cantidad > stock) {
                    alert('❌ Stock insuficiente');
                    return;
                }

                productos.push({
                    id,
                    cantidad
                });
            }

            renderTabla();
        }

        function eliminarProducto(index) {
            productos.splice(index, 1);
            renderTabla();
        }

        function actualizarCantidad(index, nuevaCantidad) {

            nuevaCantidad = parseInt(nuevaCantidad);

            let p = productos[index];

            let option = [...document.getElementById('producto').options]
                .find(o => o.value == p.id);

            let stock = parseInt(option.dataset.stock || 999999);

            if (nuevaCantidad > stock) {
                alert('❌ Stock insuficiente');
                renderTabla();
                return;
            }

            if (nuevaCantidad < 1) {
                eliminarProducto(index);
                return;
            }

            p.cantidad = nuevaCantidad;
            renderTabla();
        }

        function renderTabla() {

            let lista = document.getElementById('lista');
            lista.innerHTML = "";

            let total = 0;

            productos.forEach((p, index) => {

                let option = [...document.getElementById('producto').options]
                    .find(o => o.value == p.id);

                let nombre = option.text.split(' (Stock')[0];
                let precio = parseFloat(option.dataset.precio);
                let subtotal = precio * p.cantidad;

                total += subtotal;

                lista.innerHTML += `
        <div class="item-producto">
            <div>${nombre}</div>
            <input type="number" value="${p.cantidad}" min="1"
                onchange="actualizarCantidad(${index}, this.value)">
            <div>$${clp(precio)}</div>
            <div>$${clp(subtotal)}</div>
            <button class="btn btn-sm btn-outline-danger"
                onclick="eliminarProducto(${index})">✕</button>
        </div>`;
            });

            document.getElementById('total').innerText = clp(total);
            document.getElementById('productosInput').value = JSON.stringify(productos);
        }

        document.getElementById('ventaForm').addEventListener('submit', function(e) {
            e.preventDefault();

            fetch("{{ route('ventas.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: new FormData(this)
                })
                .then(res => res.json())
                .then(data => {

                    if (data.success) {

                        mostrarBoleta(data.venta);

                        productos = [];
                        renderTabla();

                    } else {
                        alert(data.message);
                    }

                })
                .catch(() => alert('Error servidor'));
        });

        function mostrarBoleta(venta) {

            let fecha = new Date(venta.created_at).toLocaleString('es-CL');

            let html = `
    <div class="boleta">

        <!-- HEADER -->
        <div class="boleta-header">
            <div class="empresa">EL ESFUERZO SPA</div>
            <div>RUT: 12.345.678-9</div>
            <div>Saturnino Epulef 1071</div>
        </div>

        <div class="boleta-divider"></div>

        <!-- INFO -->
        <div class="boleta-info">
            <div>Boleta: <strong>#${venta.id}</strong></div>
            <div>${fecha}</div>
        </div>

        <div class="boleta-divider"></div>

        <!-- TABLA -->
        <div class="boleta-table">

            <div class="boleta-row boleta-head">
                <span>Item</span>
                <span>Total</span>
            </div>

            <div class="boleta-divider"></div>

            ${venta.detalles.map(d => `
                            <div class="boleta-item">

                                <div class="item-nombre">${d.producto}</div>

                                <div class="boleta-row">
                                    <span>${d.cantidad} x $${clp(d.precio)}</span>
                                    <span>$${clp(d.cantidad * d.precio)}</span>
                                </div>

                            </div>
                        `).join('')}

        </div>

        <div class="boleta-divider"></div>

        <!-- TOTAL -->
        <div class="boleta-total-row">
            <span>TOTAL</span>
            <span>$${clp(venta.total)}</span>
        </div>

        <div class="boleta-divider"></div>

        <!-- FOOTER -->
        <div class="boleta-footer">
            Gracias por su compra
            <br>
            Vuelva pronto 🙌
        </div>

    </div>
    `;

            document.getElementById('boletaPrintable').innerHTML = html;
            document.getElementById('modalBoleta').style.display = 'block';
        }

        function imprimirBoleta() {

            let contenido = document.getElementById('boletaPrintable').innerHTML;

            let ventana = window.open('', '_blank', 'width=400,height=600');

            ventana.document.write(`
        <html>
        <head>
            <title>Boleta</title>
            <style>
                body {
                    margin: 0;
                    padding: 10px;
                    font-family: 'Courier New', monospace;
                    font-size: 12px;
                    color: #000;
                }

                .boleta {
                    width: 280px;
                    margin: 0 auto;
                }

                .boleta-header {
                    text-align: center;
                }

                .empresa {
                    font-weight: bold;
                    font-size: 14px;
                }

                .boleta-divider {
                    border-top: 1px dashed #000;
                    margin: 6px 0;
                }

                .boleta-row {
                    display: flex;
                    justify-content: space-between;
                }

                .boleta-total-row {
                    display: flex;
                    justify-content: space-between;
                    font-weight: bold;
                    font-size: 14px;
                }

                .boleta-footer {
                    text-align: center;
                    margin-top: 10px;
                }

            </style>
        </head>
        <body>

            ${contenido}

            <script>
                window.onload = function() {
                    window.print();
                    window.close();
                }
            <\/script>

        </body>
        </html>
    `);

            ventana.document.close();
        }

        function cerrarBoleta() {
            document.getElementById('modalBoleta').style.display = 'none';
        }
    </script>
@endpush
