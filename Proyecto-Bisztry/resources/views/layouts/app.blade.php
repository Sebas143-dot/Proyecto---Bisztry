<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-t">
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
    
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div class="container bg-gray-100">
        <!-- ============ MENÚ LATERAL (SIDEBAR) =========== -->
        <aside class="sidebar">
            <div class="sidebar-header"><div class="logo"><i class="fas fa-shopping-bag"></i><h2>BIZSTRY</h2></div></div>
            
            {{-- ======================================================= --}}
            {{--         INICIO DE LA MEJORA DEL SIDEBAR               --}}
            {{-- ======================================================= --}}
            <div class="sidebar-content">
                <nav class="sidebar-nav">
                    <ul>
                        <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><a href="{{ route('dashboard') }}"><i class="fas fa-home fa-fw"></i><span>Dashboard</span></a></li>
                        <li class="{{ request()->routeIs('pedidos.*') ? 'active' : '' }}"><a href="{{ route('pedidos.index') }}"><i class="fas fa-clipboard-list fa-fw"></i><span>Pedidos</span></a></li>
                        <li class="{{ request()->routeIs('clientes.*') ? 'active' : '' }}"><a href="{{ route('clientes.index') }}"><i class="fas fa-users fa-fw"></i><span>Clientes</span></a></li>
                        <li class="{{ request()->routeIs('productos.*') || request()->routeIs('categorias.*') || request()->routeIs('variantes.*') ? 'active' : '' }}"><a href="{{ route('productos.index') }}"><i class="fas fa-box fa-fw"></i><span>Productos</span></a></li>
                        <li class="{{ request()->routeIs('proveedores.*') ? 'active' : '' }}"><a href="{{ route('proveedores.index') }}"><i class="fas fa-truck fa-fw"></i><span>Proveedores</span></a></li>
                        @can('ver_reportes')
                            <li class="{{ request()->routeIs('reportes.*') ? 'active' : '' }}">
                                <a href="{{ route('reportes.index') }}">
                                    <i class="fas fa-chart-bar fa-fw"></i><span>Reportes</span>
                                </a>
                            </li>
                        @endcan
                        @role('Super-Admin')
                        <li class="{{ request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'active' : '' }}"><a href="{{ route('users.index') }}"><i class="fas fa-cog fa-fw"></i><span>Usuarios y Roles</span></a></li>
                        @endrole
                    </ul>
                </nav>

                <div class="sidebar-footer">
                    <!-- Botón estático para Cerrar Sesión -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-button">
                            <i class="fas fa-sign-out-alt fa-fw"></i>
                            <span>Cerrar Sesión</span>
                        </button>
                    </form>
                    <hr class="footer-divider">
                    <!-- Información del usuario (ahora no es un botón) -->
                    <div class="user-info-display">
                        <div class="avatar"><span>{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span></div>
                        <div class="user-details">
                            <h4>{{ Auth::user()->name }}</h4>
                            <span>{{ Auth::user()->getRoleNames()->first() ?? 'Usuario' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            {{-- ======================================================= --}}
            {{--              FIN DE LA MEJORA                           --}}
            {{-- ======================================================= --}}
        </aside>

        <!-- ============ CONTENIDO PRINCIPAL ============== -->
        <main class="main-content">
            <header class="main-header">
                <div class="header-title">
                    <h1>@yield('page-title', 'Página')</h1>
                    <p>@yield('page-description', 'Bienvenido.')</p>
                </div>
            </header>
            <div class="content-wrapper">@yield('content')</div>
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
    .sidebar-content {
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow-y: auto;
    }
    .sidebar-nav {
        flex-grow: 1;
    }
    .sidebar-footer {
        padding: 1rem;
        margin-top: auto; /* Empuja el footer hacia abajo */
    }
    .footer-divider {
        margin: 1rem 0;
        border: none;
        border-top: 1px solid var(--border-color);
    }
    .user-info-display {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem;
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
        overflow: hidden;
        white-space: nowrap;
    }
    .user-details h4 { font-size: 0.875rem; font-weight: 600; margin: 0; }
    .user-details span { font-size: 0.75rem; color: var(--text-secondary); }
    
    /* --- NUEVOS ESTILOS PARA EL BOTÓN DE CERRAR SESIÓN --- */
    .logout-button {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.8rem 1rem;
        border-radius: var(--radius-md);
        text-decoration: none;
        color: var(--text-secondary);
        width: 100%;
        background: none;
        border: none;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition-fast);
    }
    .logout-button:hover {
        background-color: #fee2e2; /* Un fondo rojo muy claro */
        color: #b91c1c; /* Un rojo más oscuro al pasar el mouse */
    }
    .logout-button:hover i {
        color: #b91c1c;
    }
    .logout-button i {
        font-size: 1.25rem;
        width: 1.5rem;
        text-align: center;
        color: var(--text-secondary);
        transition: var(--transition-fast);
    }
</style>
