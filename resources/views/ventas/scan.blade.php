@extends('template')

@section('title', 'Escanear Código')

@section('content')

    <div class="container mt-4">

        <h3 class="mb-4">📷 Escanear Código de Barras</h3>

        <div class="card p-4 text-center">

            <!-- CÁMARA -->
            <div id="reader" style="width: 100%; max-width: 500px; margin: auto;"></div>

            <!-- RESULTADO -->
            <div class="mt-4">
                <input type="text" id="codigo" class="form-control text-center" placeholder="Código detectado" readonly>
            </div>

            <!-- BOTÓN -->
            <button onclick="buscarProducto()" class="btn btn-primary mt-3">
                Buscar Producto
            </button>

        </div>

    </div>

@endsection

@push('js')
    <!-- LIBRERÍA -->
    <script src="https://unpkg.com/html5-qrcode"></script>

    <script>
        function onScanSuccess(decodedText) {
            document.getElementById('codigo').value = decodedText;

            // sonido opcional
            new Audio('https://www.soundjay.com/buttons/sounds/button-3.mp3').play();
        }

        // INICIAR CÁMARA
        const html5QrCode = new Html5Qrcode("reader");

        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                html5QrCode.start(
                    devices[0].id, {
                        fps: 10,
                        qrbox: 250
                    },
                    onScanSuccess
                );
            }
        });

        // BUSCAR PRODUCTO
        function buscarProducto() {
            let codigo = document.getElementById('codigo').value;

            if (!codigo) {
                alert("Escanea un código primero");
                return;
            }

            window.location.href = "/productos/buscar/" + codigo;
        }
    </script>
@endpush
