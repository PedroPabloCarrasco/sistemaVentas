<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">

    <!-- Logo / Nombre -->
    <a class="navbar-brand ps-3" href="{{ route('panel') }}">
        Sistema de Ventas
    </a>

    <!-- Toggle Sidebar -->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Buscador -->
    <form class="d-none d-md-inline-block form-inline ms-auto me-3 my-2 my-md-0">
        <div class="input-group">

            <span>
                <div class=" text-white text-center">

                    <h2 class="m-0 fw-bold">Vitaco Ventas</h2>

                </div>
            </span>


            </button>
        </div>
    </form>

    <!-- Usuario -->
    <ul class="navbar-nav ms-auto me-3 me-lg-4">
        <li class="nav-item dropdown">

            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" id="navbarDropdown" href="#"
                role="button" data-bs-toggle="dropdown">

                <i class="fas fa-user fa-fw"></i>

                {{--  Nombre del usuario --}}
                <span>{{ auth()->user()->name ?? 'Usuario' }}</span>
            </a>

            <ul class="dropdown-menu dropdown-menu-end">

                <li>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-cog me-2"></i> Configuración
                    </a>
                </li>

                <li>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-history me-2"></i> Actividad
                    </a>
                </li>

                <li>
                    <hr class="dropdown-divider">
                </li>

                <!-- Logout REAL -->
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="dropdown-item" type="submit">
                            <i class="fas fa-sign-out-alt me-2"></i> Cerrar sesión
                        </button>
                    </form>
                </li>

            </ul>
        </li>
    </ul>

</nav>
