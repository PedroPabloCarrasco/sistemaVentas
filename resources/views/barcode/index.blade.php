@extends('template')

@section('title', 'POS Escáner')

@section('content')

    <div class="container mx-auto px-4 mt-8">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-white">POS Escáner</h1>
                <p class="text-gray-400 text-sm">Escanea productos y agrégalos a la venta</p>
            </div>

            <span id="statusText" class="text-gray-300 text-sm">Listo</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- SCANNER -->
            <div class="md:col-span-1 bg-gray-900 p-4 rounded-2xl">
                <div id="reader" style="min-height:250px;"></div>

                <button id="startBtn" class="w-full mt-4 bg-black text-white py-2 rounded-xl hover:bg-gray-800 transition">
                    Iniciar Cámara
                </button>
            </div>

            <!-- PANEL -->
            <div class="md:col-span-2 bg-gray-900 p-6 rounded-2xl">

                <!-- INPUT -->
                <input type="text" id="barcodeResult"
                    class="w-full mb-4 bg-black text-white border border-gray-700 px-4 py-3 rounded-lg"
                    placeholder="Código escaneado..." readonly>

                <!-- LISTA -->
                <div class="overflow-y-auto max-h-64 mb-4">
                    <table class="w-full text-white">
                        <thead>
                            <tr class="text-gray-400 text-sm">
                                <th class="text-left">Producto</th>
                                <th>Cant</th>
                                <th>Precio</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="carrito"></tbody>
                    </table>
                </div>

                <!-- CLIENTE -->
                <div class="mb-4">
                    <label class="text-gray-300 text-sm">Cliente</label>
                    <select id="cliente" class="w-full bg-black text-white px-4 py-2 rounded-lg">
                        <option value="">Cliente General</option>
                    </select>
                </div>

                <!-- MÉTODO PAGO -->
                <div class="mb-4">
                    <label class="text-gray-300 text-sm">Método de Pago</label>
                    <select id="metodoPago" class="w-full bg-black text-white px-4 py-2 rounded-lg">
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta">Tarjeta</option>
                    </select>
                </div>

                <!-- TOTAL -->
                <div class="flex justify-between items-center">
                    <h2 class="text-xl text-white">Total:</h2>
                    <span id="totalVenta" class="text-2xl font-bold text-green-400">$0</span>
                </div>

                <!-- BOTONES -->
                <div class="flex gap-3 mt-4">
                    <button id="clearBtn" class="bg-black text-white px-6 py-2 rounded-xl hover:bg-gray-800">
                        Limpiar
                    </button>

                    <button id="guardarBtn" class="bg-black text-white px-6 py-2 rounded-xl hover:bg-gray-800">
                        Finalizar Venta
                    </button>
                </div>

            </div>

        </div>

    </div>

@endsection


@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            let scanner;
            let carrito = [];
            let total = 0;

            const carritoHTML = document.getElementById('carrito');
            const totalHTML = document.getElementById('totalVenta');
            const resultInput = document.getElementById('barcodeResult');
            const statusText = document.getElementById('statusText');

            const beep = new Audio("https://www.soundjay.com/buttons/sounds/beep-07.mp3");

            // 🔥 CARGAR CLIENTES
            async function cargarClientes() {
                const res = await fetch('/api/clientes');
                const clientes = await res.json();

                const select = document.getElementById('cliente');

                clientes.forEach(c => {
                    select.innerHTML += `<option value="${c.id}">${c.nombre}</option>`;
                });
            }

            // 🔥 SCANNER
            async function startScanner() {

                const devices = await Html5Qrcode.getCameras();

                if (!devices.length) {
                    statusText.innerText = "No hay cámara ❌";
                    return;
                }

                let cameraId = devices[0].id;

                scanner = new Html5Qrcode("reader");

                statusText.innerText = "Escaneando...";

                await scanner.start(
                    cameraId, {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 150
                        }
                    },
                    async (code) => {

                        beep.play();
                        resultInput.value = code;

                        await buscarProducto(code);
                    }
                );
            }

            // 🔥 BUSCAR PRODUCTO
            async function buscarProducto(codigo) {
                try {
                    const res = await fetch(`/api/producto/${codigo}`);
                    const data = await res.json();

                    if (!data) {
                        statusText.innerText = "Producto no encontrado ❌";
                        return;
                    }

                    agregarAlCarrito(data);
                    statusText.innerText = "Producto agregado ✔";

                } catch {
                    statusText.innerText = "Error buscando producto ❌";
                }
            }

            // 🔥 AGREGAR AL CARRITO
            function agregarAlCarrito(producto) {

                let item = carrito.find(p => p.id === producto.id);

                if (item) {
                    item.cantidad++;
                } else {
                    carrito.push({
                        ...producto,
                        cantidad: 1
                    });
                }

                renderCarrito();
            }

            // 🔥 RENDER
            function renderCarrito() {

                carritoHTML.innerHTML = "";
                total = 0;

                carrito.forEach(p => {

                    let subtotal = p.precio * p.cantidad;
                    total += subtotal;

                    carritoHTML.innerHTML += `
                <tr>
                    <td>${p.nombre}</td>
                    <td>${p.cantidad}</td>
                    <td>$${p.precio}</td>
                    <td>$${subtotal}</td>
                </tr>
            `;
                });

                totalHTML.innerText = "$" + total.toLocaleString('es-CL');
            }

            // 🔥 LIMPIAR
            document.getElementById('clearBtn').addEventListener('click', () => {
                carrito = [];
                renderCarrito();
            });

            // 🔥 GUARDAR VENTA
            document.getElementById('guardarBtn').addEventListener('click', async () => {

                if (carrito.length === 0) return;

                const res = await fetch('/ventas', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        productos: carrito,
                        cliente_id: document.getElementById('cliente').value,
                        metodo_pago: document.getElementById('metodoPago').value
                    })
                });

                const data = await res.json();

                if (data.success) {

                    // 🧾 BOLETA
                    window.open(`/ventas/${data.venta_id}/ticket`, '_blank');

                    carrito = [];
                    renderCarrito();
                    statusText.innerText = "Venta realizada ✔";
                }
            });

            // ▶️ INICIAR
            document.getElementById('startBtn').addEventListener('click', startScanner);

            // 🔥 INIT
            cargarClientes();

        });
    </script>
@endpush
