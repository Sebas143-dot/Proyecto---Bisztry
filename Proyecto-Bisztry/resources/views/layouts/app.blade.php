<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'BIZSTRY') - Sistema de Gestión</title>

    {{-- Hoja de Estilos Principal del Proyecto --}}
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    
    {{-- Iconos de Font Awesome (desde CDN para facilidad) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- CSS de Select2 (para buscadores en desplegables) --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    {{-- Alpine.js para interactividad (se carga con 'defer' para no bloquear la renderización de la página) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    {{-- Espacio para que las vistas individuales añadan sus propios estilos si lo necesitan --}}
    @stack('styles')
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-shopping-bag"></i>
                    <h2>BIZSTRY</h2>
                </div>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    {{-- La función request()->routeIs() resalta el link de la página actual --}}
                    <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}"><i class="fas fa-home fa-fw"></i><span>Dashboard</span></a>
                    </li>
                    <li class="{{ request()->routeIs('pedidos.*') ? 'active' : '' }}">
                        <a href="{{ route('pedidos.index') }}"><i class="fas fa-clipboard-list fa-fw"></i><span>Pedidos</span></a>
                    </li>
                    <li class="{{ request()->routeIs('clientes.*') ? 'active' : '' }}">
                        <a href="{{ route('clientes.index') }}"><i class="fas fa-users fa-fw"></i><span>Clientes</span></a>
                    </li>
                    <li class="{{ request()->routeIs('productos.*') || request()->routeIs('categorias.*') || request()->routeIs('variantes.*') ? 'active' : '' }}">
                        <a href="{{ route('productos.index') }}"><i class="fas fa-box fa-fw"></i><span>Productos</span></a>
                    </li>
                    <li class="{{ request()->routeIs('proveedores.*') ? 'active' : '' }}">
                        <a href="{{ route('proveedores.index') }}"><i class="fas fa-truck fa-fw"></i><span>Proveedores</span></a>
                    </li>
                     <li class="{{ request()->routeIs('reportes.*') ? 'active' : '' }}">
                        <a href="{{ route('reportes.index') }}"><i class="fas fa-chart-bar fa-fw"></i><span>Reportes</span></a>
                    </li>
                    <li class="{{ request()->routeIs('roles.*') ? 'active' : '' }}">
                        <a href="{{ route('roles.index') }}"><i class="fas fa-cog fa-fw"></i><span>Usuarios y Roles</span></a>
                    </li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="avatar"><span>JP</span></div>
                    <div class="user-details">
                        <h4>Juan D.</h4>
                        <span>Administrador</span>
                    </div>
                </div>
            </div>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <div class="header-title">
                    <h1>@yield('page-title', 'Página')</h1>
                    <p>@yield('page-description', 'Bienvenido.')</p>
                </div>
            </header>
            <div class="content-wrapper">
                {{-- Sistema de Alertas para mostrar mensajes de éxito o error --}}
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                {{-- Aquí se inyectará el contenido de cada página (index, create, etc.) --}}
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    
    {{-- Espacio para que las vistas individuales añadan sus propios scripts (como el de los gráficos o Select2) --}}
    @stack('scripts')
</body>
</html>