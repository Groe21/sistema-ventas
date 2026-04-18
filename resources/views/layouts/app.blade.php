<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistema Comercial Pro')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Driver.js for onboarding tours -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.3.1/dist/driver.css"/>
    
    <style>
        :root {
            --sidebar-width: 240px;
            --navbar-height: 52px;
            --primary-dark: #2c3e50;
            --secondary-dark: #34495e;
            --accent-color: #3498db;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            -webkit-text-size-adjust: 100%;
            overflow-x: hidden;
        }

        /* ===== NAVBAR ===== */
        .top-navbar {
            height: var(--navbar-height);
            background-color: var(--primary-dark);
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            position: fixed;
            top: 0; right: 0; left: 0;
            z-index: 1030;
            display: flex;
            align-items: center;
            padding: 0 12px;
        }
        .top-navbar .navbar-brand {
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none;
            white-space: nowrap;
        }
        .top-navbar .nav-link {
            color: rgba(255,255,255,.8);
            padding: 6px 10px;
            font-size: 0.9rem;
            white-space: nowrap;
        }
        .top-navbar .nav-link:hover { color: white; }
        .sidebar-toggle {
            background: none; border: none; color: white;
            font-size: 1.4rem; cursor: pointer; padding: 4px 8px;
            flex-shrink: 0;
        }
        .nav-right {
            display: flex; align-items: center; gap: 4px;
            margin-left: auto; flex-shrink: 0;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: var(--navbar-height);
            left: 0; bottom: 0;
            width: var(--sidebar-width);
            background-color: var(--secondary-dark);
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            transition: transform 0.3s ease;
            z-index: 1020;
        }
        .sidebar.collapsed { transform: translateX(-100%); }
        .sidebar .nav-link {
            color: rgba(255,255,255,.8);
            padding: 11px 18px;
            border-left: 3px solid transparent;
            transition: all 0.2s;
            font-size: 0.95rem;
        }
        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,.05);
            color: white;
            border-left-color: var(--accent-color);
        }
        .sidebar .nav-link.active {
            background-color: rgba(52, 152, 219, 0.15);
            color: white;
            border-left-color: var(--accent-color);
        }
        .sidebar .nav-link i { width: 24px; font-size: 1rem; }

        /* ===== MAIN ===== */
        .main-content {
            margin-top: var(--navbar-height);
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: margin-left 0.3s ease;
            min-height: calc(100vh - var(--navbar-height));
        }
        .main-content.expanded { margin-left: 0; }

        /* ===== OVERLAY ===== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: var(--navbar-height);
            left: 0; right: 0; bottom: 0;
            background-color: rgba(0,0,0,0.5);
            z-index: 1015;
        }
        .sidebar-overlay.show { display: block; }

        /* ===== CARDS ===== */
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,.08);
            margin-bottom: 16px;
        }
        .card-header {
            background-color: white;
            border-bottom: 1px solid #e9ecef;
            padding: 12px 16px;
            font-weight: 600;
        }

        /* ===== BUTTONS ===== */
        .btn { border-radius: 5px; }
        .btn-primary { background-color: var(--accent-color); border-color: var(--accent-color); }
        .btn-primary:hover { background-color: #2980b9; border-color: #2980b9; }

        /* ===== TABLES ===== */
        .table-responsive { -webkit-overflow-scrolling: touch; }
        .table thead { background-color: var(--primary-dark); color: white; }
        .table thead th { font-weight: 500; font-size: 0.85rem; white-space: nowrap; }

        /* ===== BRAND HELPERS ===== */
        .brand-short { display: none; }

        /* ==============================
           TABLET (769px - 1024px)
           ============================== */
        @media (max-width: 1024px) {
            :root { --sidebar-width: 210px; }
            .main-content { padding: 14px; }
            .hide-tablet { display: none !important; }
        }

        /* ==============================
           MÓVIL / TABLET PORTRAIT (<=768px)
           ============================== */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0 !important; padding: 10px; }

            .brand-full { display: none; }
            .brand-short { display: inline; }
            .top-navbar .navbar-brand { font-size: 1rem; }

            h2 { font-size: 1.2rem; }

            /* Modales: fullscreen */
            .modal-dialog {
                margin: 0; max-width: 100%; min-height: 100vh;
            }
            .modal-content { border-radius: 0; min-height: 100vh; }

            /* Touch targets */
            .btn { padding: 8px 14px; min-height: 42px; }
            .btn-sm { min-height: 34px; padding: 5px 10px; }
            .form-control, .form-select { min-height: 42px; font-size: 16px; }

            /* Tablas */
            .table { font-size: 0.82rem; }
            .table td, .table th { padding: 0.45rem 0.35rem; }
            .hide-mobile { display: none !important; }

            /* Stats 2 columnas */
            .stats-row > [class*="col-"] { flex: 0 0 50%; max-width: 50%; }
        }

        /* ==============================
           MÓVIL PEQUEÑO (<=480px)
           ============================== */
        @media (max-width: 480px) {
            .main-content { padding: 6px; }
            h2 { font-size: 1.05rem; }
            .card-body { padding: 10px; }
            .table { font-size: 0.78rem; }
            .btn-text-mobile { display: none; }
        }
    </style>
    @stack('styles')
</head>
<body data-show-onboarding-tour="{{ session('show_onboarding_tour') ? 'true' : 'false' }}">
@auth
    <script>
        // Pass business data to JavaScript
        window.business = {
            id: {{ auth()->user()->business_id ?? 'null' }},
            onboarding_completed: {{ auth()->user()->business && auth()->user()->business->onboarding_completed ? 'true' : 'false' }}
        };
    </script>
@endauth

<!-- Navbar -->
<nav class="top-navbar">
    <button class="sidebar-toggle" id="sidebarToggle"><i class="bi bi-list"></i></button>
    <a class="navbar-brand ms-2" href="{{ route('dashboard') }}">
        <i class="bi bi-shop"></i>
        <span class="brand-full">Sistema Comercial Pro</span>
        <span class="brand-short">SCP</span>
    </a>
    <div class="nav-right">
        @auth
            @if(!auth()->user()->isSuperAdmin() && isset($currentBusiness))
            <div class="dropdown d-none d-md-block">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-building"></i> {{ $currentBusiness->name }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><span class="dropdown-item-text"><small>RUC: {{ $currentBusiness->ruc }}</small></span></li>
                </ul>
            </div>
            @endif
            <div class="dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i> <span class="d-none d-sm-inline">{{ auth()->user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><h6 class="dropdown-header">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</h6></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</button>
                        </form>
                    </li>
                </ul>
            </div>
        @endauth
    </div>
</nav>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <nav class="nav flex-column py-2">
        @auth
            @if(auth()->user()->isSuperAdmin())
                @include('layouts.partials.sidebar-super-admin')
            @else
                @include('layouts.partials.sidebar-admin')
            @endif
        @endauth
    </nav>
</aside>

<!-- Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Main Content -->
<main class="main-content" id="mainContent">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const toggle = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('sidebarOverlay');

    function isMobile() { return window.innerWidth <= 768; }
    function isTablet() { return window.innerWidth > 768 && window.innerWidth <= 1024; }

    function closeSidebar() {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
        document.body.style.overflow = '';
    }

    toggle.addEventListener('click', function() {
        if (isMobile() || isTablet()) {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
            document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
        } else {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }
    });

    overlay.addEventListener('click', closeSidebar);

    sidebar.querySelectorAll('.nav-link').forEach(function(link) {
        link.addEventListener('click', function() {
            if (isMobile() || isTablet()) closeSidebar();
        });
    });

    window.addEventListener('resize', function() {
        if (window.innerWidth > 1024) {
            closeSidebar();
            sidebar.classList.remove('collapsed');
            mainContent.classList.remove('expanded');
        }
    });

    // Init: en móvil/tablet sidebar oculto
    if (isMobile() || isTablet()) {
        mainContent.style.marginLeft = '0';
    }
});

setTimeout(function() {
    document.querySelectorAll('.alert').forEach(function(a) {
        try { new bootstrap.Alert(a).close(); } catch(e) {}
    });
}, 5000);
</script>

<!-- Driver.js for onboarding tours -->
<script src="https://cdn.jsdelivr.net/npm/driver.js@1.3.1/dist/driver.iife.js"></script>
<script src="{{ asset('js/onboarding-tour.js') }}"></script>

@stack('scripts')
</body>
</html>
