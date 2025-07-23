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
        <!-- Botón hamburguesa solo visible en móvil -->
        <button class="hamburger-btn" id="hamburgerBtn" aria-label="Abrir menú">
            <i class="fas fa-bars"></i>
        </button>
        <!-- ============ MENÚ LATERAL (SIDEBAR) =========== -->
        <aside class="sidebar">
            <div class="sidebar-header"><div class="logo"><i class="fas fa-shopping-bag"></i><h2>BIZSTRY</h2></div></div>
            <div class="sidebar-content">
                <nav class="sidebar-nav">
                    <ul>
                        <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><a href="{{ route('dashboard') }}"><i class="fas fa-home fa-fw"></i><span>Dashboard</span></a></li>
                        <li class="{{ request()->routeIs('pedidos.*') ? 'active' : '' }}"><a href="{{ route('pedidos.index') }}"><i class="fas fa-clipboard-list fa-fw"></i><span>Pedidos</span></a></li>
                        <li class="{{ request()->routeIs('clientes.*') ? 'active' : '' }}"><a href="{{ route('clientes.index') }}"><i class="fas fa-users fa-fw"></i><span>Clientes</span></a></li>
                        <li class="{{ request()->routeIs('productos.*') || request()->routeIs('categorias.*') || request()->routeIs('variantes.*') ? 'active' : '' }}"><a href="{{ route('productos.index') }}"><i class="fas fa-box fa-fw"></i><span>Productos</span></a></li>
                        <li class="{{ request()->routeIs('proveedores.*') ? 'active' : '' }}"><a href="{{ route('proveedores.index') }}"><i class="fas fa-truck fa-fw"></i><span>Proveedores</span></a></li>
                        
                            <li class="{{ request()->routeIs('reportes.*') ? 'active' : '' }}">
                                <a href="{{ route('reportes.index') }}">
                                    <i class="fas fa-chart-bar fa-fw"></i><span>Reportes</span>
                                </a>
                            </li>
                        @role('Super-Admin')
                        <li class="{{ request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'active' : '' }}"><a href="{{ route('users.index') }}"><i class="fas fa-cog fa-fw"></i><span>Usuarios y Roles</span></a></li>
                        {{-- ENLACE PARA AUDITORÍA --}}
                        <li class="{{ request()->routeIs('audits.*') ? 'active' : '' }}">
                            <a href="{{ route('audits.index') }}">
                                <i class="fas fa-history fa-fw"></i><span>Auditoría</span> {{-- Usé 'fa-history' para el icono, puedes cambiarlo --}}
                            </a>
                        </li>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const hamburgerBtn = document.getElementById('hamburgerBtn');
            const sidebar = document.querySelector('.sidebar');

            hamburgerBtn.addEventListener('click', () => {
                sidebar.classList.toggle('open');
            });

            // Opcional: cerrar el menú al hacer clic fuera del sidebar
            document.addEventListener('click', function(e) {
                if (
                    sidebar.classList.contains('open') &&
                    !sidebar.contains(e.target) &&
                    e.target !== hamburgerBtn
                ) {
                    sidebar.classList.remove('open');
                }
            });
        });
    </script>
</body>
</html>

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
        margin-top: auto;
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
        background-color: #fee2e2;
        color: #b91c1c;
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
    /* --- ESTILOS PARA EL BOTÓN HAMBURGUESA --- */
    .hamburger-btn {
        display: none;
        position: fixed;
        top: 1.5rem;
        left: 1.5rem;
        z-index: 2000;
        background: var(--primary-color, #2563eb);
        color: #fff;
        border: none;
        border-radius: 8px;
        width: 48px;
        height: 48px;
        align-items: center;
        justify-content: center;
        font-size: 1.7rem;
        cursor: pointer;
        transition: background 0.2s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
    }
    .hamburger-btn:active,
    .hamburger-btn:focus {
        background: #0056b3;
    }
    .hamburger-btn i {
        font-size: 1.7rem;
    }
    /* ===================== RESPONSIVE DESIGN ===================== */
    @media (max-width: 900px) {
        .hamburger-btn {
            display: flex;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: -100%;
            height: 100vh;
            z-index: 1099;
            transition: left 0.18s cubic-bezier(0.4,0,0.2,1);
            box-shadow: 2px 0 8px rgba(0,0,0,0.12);
            width: 220px;
            min-width: 0;
            border-radius: 0 1rem 1rem 0;
            margin-bottom: 0;
        }
        .sidebar.open {
            left: 0;
        }
        .main-content {
            margin-left: 0;
            width: 100%;
        }
        .container {
            flex-direction: column;
        }
    }
    @media (max-width: 600px) {
        .sidebar-header {
            padding: 0.75rem 0.5rem;
        }
        .logo {
            gap: 0.5rem;
        }
        .sidebar-header h2 {
            font-size: 1rem;
            margin-left: 0.5rem;
        }
        .sidebar-nav ul {
            flex-direction: column;
            gap: 0.25rem;
            padding: 0;
        }
        .sidebar-nav li {
            width: 100%;
            margin-bottom: 0.25rem;
        }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            border-radius: 0.5rem;
            transition: background 0.2s;
            min-width: 0;
            word-break: break-word;
        }
        .sidebar-nav a i {
            font-size: 1.2rem;
            min-width: 1.5rem;
            text-align: center;
        }
    }
    @media (max-width: 400px) {
        .sidebar-nav a span {
            display: none;
        }
        .sidebar-header h2 {
            display: none;
        }
    }
    .container {
        display: flex;
        min-height: 100vh;
    }
    .sidebar {
        min-width: 220px;
        max-width: 260px;
        background: #fff;
        border-radius: 0 1rem 1rem 0;
        box-shadow: 2px 0 8px rgba(0,0,0,0.04);
    }
    .main-content {
        flex: 1;
        margin-left: 260px;
        padding: 2rem;
        background: #f9fafb;
        min-width: 0;
    }
    @media (max-width: 900px) {
        .main-content {
            margin-left: 0;
            padding: 1rem;
        }
        .sidebar {
            max-width: 100%;
        }
    }
    @media (max-width: 900px) {
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
        .table-responsive table {
            width: 100%;
            min-width: 600px;
        }
        form, .card, .box, .panel {
            width: 100% !important;
            min-width: 0 !important;
            box-sizing: border-box;
        }
        input, select, textarea, button {
            width: 100%;
            box-sizing: border-box;
        }
    }
    img {
        max-width: 100%;
        height: auto;
    }
</style>