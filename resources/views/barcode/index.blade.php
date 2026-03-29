@extends('template')

@section('title', 'Leer Código de Barra')

@section('content')

    <div class="container mx-auto px-4 mt-8">

        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-white">Escáner</h1>
                <p class="text-gray-400 text-sm">Captura automática de códigos de barras</p>
            </div>

            <div class="flex items-center gap-2 mt-4 md:mt-0">
                <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
                <span id="statusText" class="text-gray-300 text-sm">Inicializando...</span>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-gray-900 rounded-2xl shadow-2xl p-6">

            <!-- Scanner -->
            <div id="reader" class="w-full mb-6 rounded-xl overflow-hidden border-2 border-dashed border-gray-700"
                style="min-height:300px;">
            </div>

            <!-- Resultado -->
            <div class="mb-6">
                <label class="block text-gray-300 text-sm mb-2">Código detectado</label>
                <input type="text" id="barcodeResult"
                    class="w-full bg-black text-white border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500"
                    placeholder="Esperando escaneo..." readonly>
            </div>

            <!-- Botón -->
            <div class="flex justify-end">
                <button id="restartBtn"
                    class="bg-black text-white font-bold px-6 py-2 rounded-xl shadow-lg 
                       hover:bg-gray-800 hover:shadow-2xl transition-all">
                    Reiniciar
                </button>
            </div>

        </div>

    </div>

@endsection


@push('js')
    {{-- 🔴 IMPORTANTE: debe coincidir con tu template --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const resultInput = document.getElementById('barcodeResult');
            const statusText = document.getElementById('statusText');
            const restartBtn = document.getElementById('restartBtn');

            let html5Qrcode = null;
            let isScanning = false;

            if (typeof Html5Qrcode === "undefined") {
                statusText.innerText = "Error cargando librería ❌";
                return;
            }

            async function startScanner() {
                try {
                    statusText.innerText = "Solicitando cámara...";

                    const devices = await Html5Qrcode.getCameras();

                    if (!devices.length) {
                        statusText.innerText = "No hay cámara ❌";
                        return;
                    }

                    // 🔥 fallback inteligente
                    const cameraId = devices[0].id;

                    html5Qrcode = new Html5Qrcode("reader");

                    statusText.innerText = "Escaneando...";

                    await html5Qrcode.start(
                        cameraId, {
                            fps: 10,
                            qrbox: 250
                        },
                        (decodedText) => {

                            if (!isScanning) return;

                            resultInput.value = decodedText;
                            resultInput.classList.add("border-green-500");

                            statusText.innerText = "Código detectado ✔";
                            isScanning = false;

                            stopScanner();
                        },
                        () => {}
                    );

                    isScanning = true;

                } catch (error) {
                    console.error(error);

                    if (error.name === "NotAllowedError") {
                        statusText.innerText = "Permiso denegado ❌";
                    } else {
                        statusText.innerText = "Error al iniciar cámara ❌";
                    }
                }
            }

            async function stopScanner() {
                if (html5Qrcode) {
                    try {
                        await html5Qrcode.stop();
                        await html5Qrcode.clear(); // 🔥 clave para evitar bugs
                        html5Qrcode = null;
                        isScanning = false;
                    } catch (e) {}
                }
            }

            // ▶️ iniciar
            startScanner();

            // 🔄 reiniciar
            restartBtn.addEventListener('click', async () => {
                resultInput.value = "";
                resultInput.classList.remove("border-green-500");

                await stopScanner();
                startScanner();
            });

            // 🛑 limpiar al salir
            window.addEventListener('beforeunload', stopScanner);

        });
    </script>
@endpush
