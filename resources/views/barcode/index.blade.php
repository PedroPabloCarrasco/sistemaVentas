@extends('template')

@section('title', 'POS Scanner PRO')

@section('content')
    <div class="mobile-app">

        <!-- HEADER -->
        <div class="header">
            <h1>POS Scanner</h1>
        </div>

        <!-- SCANNER -->
        <div id="scannerWrapper" class="scanner-wrapper"></div>

        <!-- BOTÓN -->
        <button id="scanButton">Iniciar Escaneo</button>

        <!-- LISTA DE PRODUCTOS -->
        <div class="product-list">
            <h3>Productos Escaneados</h3>
            <ul id="productItems"></ul>
        </div>

    </div>
@endsection

@push('css')
    <style>
        body {
            background: #f1f5f9;
            font-family: 'Inter', sans-serif;
        }

        .mobile-app {
            max-width: 400px;
            margin: 20px auto;
            text-align: center;
        }

        .header h1 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .scanner-wrapper {
            width: 100%;
            height: 300px;
            background: #000;
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 15px;
            border: 4px solid transparent;
            transition: border 0.3s;
            position: relative;
        }

        #scanButton {
            width: 90%;
            padding: 14px;
            background: #ef4444;
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .product-list {
            text-align: left;
            background: #fff;
            padding: 16px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .product-list h3 {
            margin-bottom: 12px;
            font-weight: 600;
        }

        .product-list ul {
            list-style: none;
            padding: 0;
            max-height: 200px;
            overflow-y: auto;
        }

        .product-list li {
            padding: 8px 12px;
            margin-bottom: 6px;
            border-radius: 8px;
            background: #f3f4f6;
            font-size: 14px;
        }
    </style>
@endpush

@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", async () => {

            const scannerWrapper = document.getElementById("scannerWrapper");
            const scanButton = document.getElementById("scanButton");
            const productItems = document.getElementById("productItems");
            const beep = new Audio("https://www.soundjay.com/buttons/sounds/beep-07.mp3");

            let scannedCodes = [];
            let videoStream;
            let scanning = false;

            async function startScanner() {
                if (scanning) return stopScanner();

                scanning = true;
                scanButton.textContent = "Detener Escaneo";

                try {
                    videoStream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: "environment"
                        }
                    });
                    const video = document.createElement("video");
                    video.srcObject = videoStream;
                    video.setAttribute("playsinline", true);
                    video.autoplay = true;
                    video.style.width = "100%";
                    video.style.height = "100%";
                    scannerWrapper.innerHTML = "";
                    scannerWrapper.appendChild(video);

                    const barcodeDetector = ("BarcodeDetector" in window) ? new BarcodeDetector({
                        formats: ["ean_13", "code_128", "upc_a", "upc_e"]
                    }) : null;

                    if (!barcodeDetector) {
                        alert(
                            "Tu navegador no soporta BarcodeDetector nativo. Por favor usa Chrome o Edge moderno.");
                        return;
                    }

                    const detectLoop = async () => {
                        if (!scanning) return;
                        try {
                            const barcodes = await barcodeDetector.detect(video);
                            if (barcodes.length > 0) {
                                for (const barcode of barcodes) {
                                    const code = barcode.rawValue;
                                    if (!scannedCodes.includes(code)) {
                                        scannedCodes.push(code);
                                        addProductToList(code);
                                        beep.play();
                                        scannerWrapper.style.border = "4px solid #10b981"; // verde
                                    }
                                }
                            } else {
                                scannerWrapper.style.border = "4px solid #ef4444"; // rojo
                            }
                        } catch (e) {
                            console.error(e);
                        }
                        requestAnimationFrame(detectLoop);
                    };

                    video.play();
                    detectLoop();

                } catch (e) {
                    console.error("Error accediendo a la cámara", e);
                    alert("No se pudo acceder a la cámara");
                }
            }

            function stopScanner() {
                scanning = false;
                scanButton.textContent = "Iniciar Escaneo";
                if (videoStream) {
                    videoStream.getTracks().forEach(track => track.stop());
                    scannerWrapper.innerHTML = "";
                }
                scannerWrapper.style.border = "4px solid transparent";
            }

            function addProductToList(code) {
                const li = document.createElement("li");
                li.textContent = code;
                productItems.appendChild(li);
            }

            scanButton.addEventListener("click", startScanner);

        });
    </script>
@endpush
