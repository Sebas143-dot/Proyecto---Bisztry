<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'BIZSTRY') - Sistema de Gestión</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Iconos y Librerías CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- Scripts y Estilos de Vite (de Breeze) y CSS Personalizado -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    
    <!-- Alpine.js para interactividad (ya no es necesario en el head si se carga con Vite) -->
    
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div class="container bg-gray-100">
        <!-- ============ MENÚ LATERAL (SIDEBAR) =========== -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo"><i class="fas fa-shopping-bag"></i><h2>BIZSTRY</h2></div>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><a href="{{ route('dashboard') }}"><i class="fas fa-home fa-fw"></i><span>Dashboard</span></a></li>
                    <li class="{{ request()->routeIs('pedidos.*') ? 'active' : '' }}"><a href="{{ route('pedidos.index') }}"><i class="fas fa-clipboard-list fa-fw"></i><span>Pedidos</span></a></li>
                    <li class="{{ request()->routeIs('clientes.*') ? 'active' : '' }}"><a href="{{ route('clientes.index') }}"><i class="fas fa-users fa-fw"></i><span>Clientes</span></a></li>
                    <li class="{{ request()->routeIs('productos.*') || request()->routeIs('categorias.*') || request()->routeIs('variantes.*') ? 'active' : '' }}"><a href="{{ route('productos.index') }}"><i class="fas fa-box fa-fw"></i><span>Productos</span></a></li>
                    <li class="{{ request()->routeIs('proveedores.*') ? 'active' : '' }}"><a href="{{ route('proveedores.index') }}"><i class="fas fa-truck fa-fw"></i><span>Proveedores</span></a></li>
                    <li class="{{ request()->routeIs('reportes.*') ? 'active' : '' }}"><a href="{{ route('reportes.index') }}"><i class="fas fa-chart-bar fa-fw"></i><span>Reportes</span></a></li>
                    <li class="{{ request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'active' : '' }}"><a href="{{ route('users.index') }}"><i class="fas fa-cog fa-fw"></i><span>Usuarios y Roles</span></a></li>
                </ul>
            </nav>

            <!-- ======================================================= -->
            <!--   INICIO DE LA MEJORA: MENÚ DE USUARIO EN SIDEBAR       -->
            <!-- ======================================================= -->
            <div class="sidebar-footer" x-data="{ open: false }">
                <!-- El menú emergente -->
                <div x-show="open" @click.away="open = false" class="logout-menu" x-transition>
                    <a href="{{ route('profile.edit') }}" class="logout-link">
                        <i class="fas fa-user-edit fa-fw"></i><span>Mi Perfil</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-link">
                            <i class="fas fa-sign-out-alt fa-fw"></i><span>Cerrar Sesión</span>
                        </button>
                    </form>
                </div>
                
                <!-- El botón que muestra el nombre y abre el menú -->
                <div @click="open = !open" class="user-info-clickable">
                    <div class="avatar"><span>{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span></div>
                    <div class="user-details">
                        <h4>{{ Auth::user()->name }}</h4>
                        <span>{{ Auth::user()->getRoleNames()->first() ?? 'Usuario' }}</span>
                    </div>
                    <i class="fas fa-chevron-up icon-chevron" :class="{'rotate-180': open}"></i>
                </div>
            </div>
            <!-- ======================================================= -->
            <!--             FIN DE LA MEJORA                          -->
            <!-- ======================================================= -->
        </aside>

        <!-- ============ CONTENIDO PRINCIPAL ============== -->
        <main class="main-content">
            <header class="main-header">
                <div class="header-title">
                    <h1>@yield('page-title', 'Página')</h1>
                    <p>@yield('page-description', 'Bienvenido.')</p>
                </div>
                {{-- Hemos eliminado el menú de usuario de aquí para ponerlo en el sidebar --}}
            </header>
            <div class="content-wrapper">
                @yield('content')
            </div>
        </main>
    </div>
    
    <!-- SCRIPTS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    @stack('scripts')
</body>
</html>

{{-- Estilos para el nuevo menú de usuario --}}
<style>
    .sidebar-footer {
        padding: 1rem;
        border-top: 1px solid var(--border-color);
        margin-top: auto;
        position: relative; /* Clave para el posicionamiento del menú */
    }
    .user-info-clickable {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem;
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: var(--transition-fast);
    }
    .user-info-clickable:hover {
        background-color: var(--bg-color);
    }
    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--primary-color);
        color: var(--text-on-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        flex-shrink: 0;
    }
    .user-details {
        flex-grow: 1;
        overflow: hidden;
        white-space: nowrap;
    }
    .user-details h4 { font-size: 0.875rem; font-weight: 600; margin: 0; }
    .user-details span { font-size: 0.75rem; color: var(--text-secondary); }
    .icon-chevron {
        margin-left: auto;
        transition: transform 0.3s ease;
    }
    .rotate-180 {
        transform: rotate(180deg);
    }
    .logout-menu {
        position: absolute;
        bottom: calc(100% + 0.5rem); /* Se posiciona encima del footer */
        left: 1rem;
        right: 1rem;
        background-color: var(--surface-color);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        z-index: 10;
        padding: 0.5rem;
        border: 1px solid var(--border-color);
    }
    .logout-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        border-radius: var(--radius-md);
        text-decoration: none;
        color: var(--text-primary);
        width: 100%;
        background: none;
        border: none;
        font-size: 0.875rem;
        cursor: pointer;
    }
    .logout-link:hover {
        background-color: var(--bg-color);
        color: var(--primary-color);
    }
    .logout-link i {
        color: var(--text-secondary);
    }
</style>