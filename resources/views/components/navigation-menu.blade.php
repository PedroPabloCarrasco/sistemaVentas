<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">

        <div class="sb-sidenav-menu">
            <div class="nav">

                <!-- INICIO -->
                <div class="sb-sidenav-menu-heading">Inicio</div>
                <a class="nav-link {{ request()->routeIs('panel') ? 'active' : '' }}" href="{{ route('panel') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    Panel de Control
                </a>

                <!-- MÓDULOS -->
                <div class="sb-sidenav-menu-heading">Módulos</div>

                <!-- CATEGORÍAS -->
                <a class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}"
                    href="{{ route('categorias.index') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fa-solid fa-tag"></i>
                    </div>
                    Categorías
                </a>

                <!-- PRODUCTOS -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapseProductos" aria-expanded="false">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    Productos
                    <div class="sb-sidenav-collapse-arrow">
                        <i class="fas fa-angle-down"></i>
                    </div>
                </a>

                <div class="collapse" id="collapseProductos" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link {{ request()->routeIs('productos.index') ? 'active' : '' }}"
                            href="{{ route('productos.index') }}">
                            Listar Productos
                        </a>
                        <a class="nav-link {{ request()->routeIs('productos.create') ? 'active' : '' }}"
                            href="{{ route('productos.create') }}">
                            Crear Producto
                        </a>
                    </nav>
                </div>

                <!-- VENTAS -->
                <a class="nav-link collapsed {{ request()->routeIs('ventas.*') ? 'active' : '' }}" href="#"
                    data-bs-toggle="collapse" data-bs-target="#collapseVentas" aria-expanded="false">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-cash-register"></i>
                    </div>
                    Ventas
                    <div class="sb-sidenav-collapse-arrow">
                        <i class="fas fa-angle-down"></i>
                    </div>
                </a>

                <div class="collapse {{ request()->routeIs('ventas.*') ? 'show' : '' }}" id="collapseVentas"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link {{ request()->routeIs('ventas.index') ? 'active' : '' }}"
                            href="{{ route('ventas.index') }}">
                            Listar Ventas
                        </a>
                        <a class="nav-link {{ request()->routeIs('ventas.create') ? 'active' : '' }}"
                            href="{{ route('ventas.create') }}">
                            Nueva Venta
                        </a>
                    </nav>
                </div>

                <!-- NUEVO: CÓDIGO DE BARRAS -->
                <a class="nav-link {{ request()->routeIs('barcode.*') ? 'active' : '' }}"
                    href="{{ route('barcode.index') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-barcode"></i>
                    </div>
                    Leer Código de Barra
                </a>

            </div>
        </div>

        <!-- FOOTER -->
        <div class="sb-sidenav-footer">
            <div class="small">Bienvenido:</div>
            {{ auth()->user()->name ?? 'Usuario' }}
        </div>

    </nav>
</div>
