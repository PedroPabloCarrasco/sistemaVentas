@extends('template')

@section('title', 'Escanear Código y Registrar Productos')

@section('content')
    <div class="container mt-4">
        <h3 class="mb-4">📷 Escanear Código de Barras</h3>

        <div class="card p-4 text-center shadow-lg rounded-lg">

            <!-- CÁMARA -->
            <div id="reader" style="width: 100%; max-width: 500px; margin: auto;"></div>

            <!-- RESULTADO -->
            <div class="mt-4">
                <input type="text" id="codigo" class="form-control text-center" placeholder="Código detectado" readonly>
            </div>

            <!-- BOTÓN BUSCAR -->
            <button onclick="buscarProducto()" class="btn btn-primary mt-3">
                Agregar al Carrito
            </button>

            <!-- RESULTADO DEL PRODUCTO -->
            <div id="resultado" class="mt-4 p-3 bg-gray-100 rounded shadow-sm" style="display: none;"></div>

            <!-- CARRITO -->
            <div id="carrito" class="mt-6">
                <h5>Productos Escaneados:</h5>
                <table class="table table-bordered mt-2">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Código</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="carrito-body"></tbody>
                </table>
                <h6 class="text-right font-bold">Total General: $<span id="total-general">0</span></h6>
            </div>

        </div>
    </div>
@endsection

@push('js')
    <!-- HTML5 QR Code (funciona para 1D y QR) -->
    <script src="https://unpkg.com/html5-qrcode"></script>

    <script>
        let carrito = [];

        // Función al detectar código
        function onScanSuccess(decodedText) {
            document.getElementById('codigo').value = decodedText;
            new Audio('https://www.soundjay.com/buttons/sounds/button-3.mp3').play();
        }

        // Iniciar la cámara con html5-qrcode
        const html5QrCode = new Html5Qrcode("reader");

        Html5Qrcode.getCameras().then(cameras => {
            if (cameras && cameras.length) {
                const cameraId = cameras[0].id;
                html5QrCode.start(
                    cameraId, {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 100
                        }, // zona central (para barras largas)
                        formatsToSupport: [Html5QrcodeSupportedFormats.CODE_128,
                            Html5QrcodeSupportedFormats.EAN_13,
                            Html5QrcodeSupportedFormats.EAN_8,
                            Html5QrcodeSupportedFormats.UPC_A,
                            Html5QrcodeSupportedFormats.UPC_E
                        ]
                    },
                    onScanSuccess
                );
            }
        }).catch(err => console.error(err));

        // BUSCAR PRODUCTO Y AGREGAR AL CARRITO
        function buscarProducto() {
            let codigo = document.getElementById('codigo').value;
            if (!codigo) {
                alert("Escanea un código primero");
                return;
            }

            fetch('/buscar-producto', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        codigo_barra: codigo
                    })
                })
                .then(res => res.json())
                .then(data => {
                    const resultadoDiv = document.getElementById('resultado');
                    if (data.success) {
                        resultadoDiv.innerHTML = `<strong>${data.producto.nombre}</strong> agregado al carrito`;
                        resultadoDiv.style.display = 'block';
                        agregarAlCarrito(data.producto);
                    } else {
                        resultadoDiv.innerHTML = `<p class="text-danger">${data.message}</p>`;
                        resultadoDiv.style.display = 'block';
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Ocurrió un error al buscar el producto');
                });
        }

        // AGREGAR PRODUCTO AL CARRITO
        function agregarAlCarrito(producto) {
            let existente = carrito.find(p => p.id === producto.id);
            if (existente) {
                existente.cantidad++;
                existente.total = existente.cantidad * producto.precio;
            } else {
                carrito.push({
                    id: producto.id,
                    nombre: producto.nombre,
                    codigo_barra: producto.codigo_barra,
                    precio: producto.precio,
                    cantidad: 1,
                    total: producto.precio
                });
            }
            actualizarCarrito();
        }

        // ACTUALIZAR TABLA CARRITO
        function actualizarCarrito() {
            const tbody = document.getElementById('carrito-body');
            tbody.innerHTML = '';
            let totalGeneral = 0;

            carrito.forEach((p, index) => {
                totalGeneral += p.total;
                tbody.innerHTML += `
                <tr>
                    <td>${p.nombre}</td>
                    <td>${p.codigo_barra}</td>
                    <td>$${p.precio}</td>
                    <td>${p.cantidad}</td>
                    <td>$${p.total}</td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="eliminarProducto(${index})">Eliminar</button>
                    </td>
                </tr>
            `;
            });

            document.getElementById('total-general').innerText = totalGeneral;
        }

        // ELIMINAR PRODUCTO DEL CARRITO
        function eliminarProducto(index) {
            carrito.splice(index, 1);
            actualizarCarrito();
        }
    </script>
@endpush
