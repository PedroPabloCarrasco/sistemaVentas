@extends('template')

@section('title', 'Productos')

@section('content')

    <div class="container-fluid px-4">

        <h1 class="mt-4">Productos</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Productos</li>
        </ol>

        <!-- BOTÓN -->
        <a href="{{ route('productos.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Nuevo Producto
        </a>

        <!-- MENSAJE -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                Lista de productos
            </div>

            <div class="card-body">

                <!-- 🔍 BUSCADOR (NUEVO) -->
                <input type="text" id="buscadorTabla" class="form-control mb-3" placeholder="🔍 Buscar producto..."
                    onkeyup="filtrarTabla()">

                <script>
                    let productos = [];

                    function clp(n) {
                        return Math.round(Number(n)).toLocaleString('es-CL');
                    }

                    // 🔍 BUSCADOR TABLA (NUEVO - NO TOCA LO DEMÁS)
                    function filtrarTabla() {
                        let input = document.getElementById('buscadorTabla').value.toLowerCase();
                        let filas = document.querySelectorAll("table tbody tr");

                        filas.forEach(fila => {
                            let texto = fila.innerText.toLowerCase();

                            if (texto.includes(input)) {
                                fila.style.display = '';
                            } else {
                                fila.style.display = 'none';
                            }
                        });
                    }

                    // 🔍 FILTRO PRODUCTOS (NO MODIFICADO)
                    function filtrarProductos() {
                        let input = document.getElementById('buscadorProducto').value.toLowerCase().trim();
                        let select = document.getElementById('producto');
                        let options = select.options;

                        let firstVisibleIndex = -1;

                        for (let i = 0; i < options.length; i++) {
                            let texto = options[i].text.toLowerCase();

                            if (texto.includes(input)) {
                                options[i].style.display = '';
                                if (firstVisibleIndex === -1) {
                                    firstVisibleIndex = i;
                                }
                            } else {
                                options[i].style.display = 'none';
                            }
                        }

                        if (firstVisibleIndex !== -1) {
                            select.selectedIndex = firstVisibleIndex;
                        }
                    }

                    document.addEventListener('DOMContentLoaded', () => {
                        const buscador = document.getElementById('buscadorProducto');

                        if (buscador) {
                            buscador.addEventListener('keypress', function(e) {
                                if (e.key === 'Enter') {
                                    e.preventDefault();
                                    agregarProducto();
                                }
                            });
                        }
                    });

                    function agregarProducto() {

                        let select = document.getElementById('producto');
                        let cantidadInput = document.getElementById('cantidad');

                        let cantidad = parseInt(cantidadInput.value);

                        if (cantidad < 1) return;

                        let option = select.options[select.selectedIndex];

                        if (!option || option.style.display === 'none') {
                            alert('❌ No hay producto seleccionado');
                            return;
                        }

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

                        cantidadInput.value = 1;
                        if (document.getElementById('buscadorProducto')) {
                            document.getElementById('buscadorProducto').value = '';
                        }
                        resetSelect();
                    }

                    function resetSelect() {
                        let select = document.getElementById('producto');
                        if (!select) return;

                        let options = select.options;

                        for (let i = 0; i < options.length; i++) {
                            options[i].style.display = '';
                        }

                        select.selectedIndex = 0;
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
                        if (!lista) return;

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

                        if (document.getElementById('total')) {
                            document.getElementById('total').innerText = clp(total);
                        }

                        if (document.getElementById('productosInput')) {
                            document.getElementById('productosInput').value = JSON.stringify(productos);
                        }
                    }

                    document.getElementById('ventaForm')?.addEventListener('submit', function(e) {
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
                </script>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Código</th> <!-- ✅ NUEVO -->
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Categoría</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($productos as $producto)
                            <tr>
                                <td>{{ $producto->id }}</td>
                                <td>{{ $producto->nombre }}</td>

                                <!-- ✅ NUEVO -->
                                <td>
                                    <span class="badge bg-dark">
                                        {{ $producto->codigo_barra }}
                                    </span>
                                </td>

                                <td>${{ $producto->precio }}</td>
                                <td>{{ $producto->stock }}</td>
                                <td>{{ $producto->categoria->nombre ?? '-' }}</td>
                                <td>
                                    @if ($producto->estado == 1)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-warning btn-sm">
                                        Editar
                                    </a>

                                    <form action="{{ route('productos.destroy', $producto->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger btn-sm">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

    </div>

@endsection
