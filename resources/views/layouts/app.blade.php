<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistema Comercial Pro')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Driver.js for onboarding tours -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.3.1/dist/driver.css"/>
    
    <style>
        :root {
            --sidebar-width: 240px;
            --navbar-height: 52px;
            --color-primary: #5b5bd6;
            --color-primary-hover: #4a4ac4;
            --color-primary-soft: #ececff;
            --color-bg: #f5f7fb;
            --color-surface: #ffffff;
            --color-text: #1f2937;
            --color-text-muted: #6b7280;
            --color-border: #e5e7eb;
            --color-success: #10b981;
            --color-warning: #f59e0b;
            --color-danger: #ef4444;
            --color-info: #3b82f6;
            --color-sidebar: #22263a;
            --color-sidebar-soft: #2a3048;
            --radius-card: 12px;
            --radius-control: 8px;
            --radius-pill: 999px;
            --shadow-card: 0 10px 25px rgba(15, 23, 42, 0.06);
            --shadow-soft: 0 6px 18px rgba(15, 23, 42, 0.08);
            --content-max-width: 1500px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--color-bg);
            color: var(--color-text);
            -webkit-text-size-adjust: 100%;
            overflow-x: hidden;
            line-height: 1.45;
        }
        h1, h2, h3, h4, h5, h6 {
            color: var(--color-text);
            letter-spacing: -0.01em;
        }
        h2 { font-weight: 700; }
        p, small, .text-muted { color: var(--color-text-muted) !important; }

        /* ===== NAVBAR ===== */
        .top-navbar {
            height: var(--navbar-height);
            background-color: var(--color-surface);
            border-bottom: 1px solid var(--color-border);
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
            position: fixed;
            top: 0; right: 0; left: 0;
            z-index: 1030;
            display: flex;
            align-items: center;
            padding: 0 12px;
        }
        .top-navbar .navbar-brand {
            color: var(--color-text);
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none;
            white-space: nowrap;
            letter-spacing: -0.01em;
        }
        .top-navbar .navbar-brand:hover { color: var(--color-primary); }
        .top-navbar .nav-link {
            color: var(--color-text-muted);
            padding: 6px 10px;
            font-size: 0.9rem;
            white-space: nowrap;
            border-radius: var(--radius-control);
            transition: all 0.2s ease;
        }
        .top-navbar .nav-link:hover {
            color: var(--color-primary);
            background-color: rgba(91, 91, 214, 0.08);
        }
        .sidebar-toggle {
            background: none; border: none; color: var(--color-text);
            font-size: 1.4rem; cursor: pointer; padding: 4px 8px;
            flex-shrink: 0;
            border-radius: 6px;
        }
        .sidebar-toggle:hover {
            background-color: rgba(91, 91, 214, 0.08);
            color: var(--color-primary);
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
            background-color: var(--color-sidebar);
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            transition: transform 0.3s ease;
            z-index: 1020;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
        }
        .sidebar.collapsed { transform: translateX(-100%); }
        .sidebar .nav {
            padding-left: 10px;
            padding-right: 10px;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.82);
            padding: 10px 14px;
            border-left: 3px solid transparent;
            transition: all 0.2s;
            font-size: 0.95rem;
            border-radius: 10px;
            margin-bottom: 2px;
        }
        .sidebar .nav-link:hover {
            background-color: var(--color-sidebar-soft);
            color: white;
            border-left-color: var(--color-primary);
            transform: translateX(2px);
        }
        .sidebar .nav-link.active {
            background-color: rgba(91, 91, 214, 0.18);
            color: white;
            border-left-color: var(--color-primary);
            box-shadow: inset 0 0 0 1px rgba(91, 91, 214, 0.35);
        }
        .sidebar .nav-link i { width: 24px; font-size: 1rem; }

        /* ===== MAIN ===== */
        .main-content {
            margin-top: var(--navbar-height);
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: margin-left 0.3s ease;
            min-height: calc(100vh - var(--navbar-height));
            max-width: var(--content-max-width);
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
            border: 1px solid var(--color-border);
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-card);
            margin-bottom: 16px;
            background-color: var(--color-surface);
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }
        .card:hover { box-shadow: var(--shadow-soft); }
        .card-header {
            background-color: var(--color-surface);
            border-bottom: 1px solid var(--color-border);
            padding: 12px 16px;
            font-weight: 600;
            color: var(--color-text);
        }
        .card-body { color: var(--color-text); }
        .card-title {
            color: var(--color-text);
            font-weight: 600;
        }

        /* ===== BUTTONS ===== */
        .btn { border-radius: var(--radius-control); }
        .btn-primary {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
            box-shadow: 0 4px 12px rgba(91, 91, 214, 0.25);
        }
        .btn-primary:hover {
            background-color: var(--color-primary-hover);
            border-color: var(--color-primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(74, 74, 196, 0.28);
        }
        .btn-outline-primary {
            color: var(--color-primary);
            border-color: var(--color-primary);
            background-color: #fff;
        }
        .btn-outline-primary:hover {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
        }
        .btn-secondary {
            background-color: #eef2ff;
            border-color: #e0e7ff;
            color: #3730a3;
        }
        .btn-secondary:hover {
            background-color: #e0e7ff;
            border-color: #c7d2fe;
            color: #312e81;
        }
        .btn:focus {
            box-shadow: 0 0 0 0.2rem rgba(91, 91, 214, 0.2);
        }

        /* ===== TABLES ===== */
        .table-responsive { -webkit-overflow-scrolling: touch; }
        .table {
            --bs-table-bg: transparent;
        }
        .table thead { background-color: #f3f4f6; color: var(--color-text); }
        .table thead th { font-weight: 500; font-size: 0.85rem; white-space: nowrap; }
        .table td,
        .table th {
            border-color: var(--color-border);
            vertical-align: middle;
        }
        .table tbody tr:nth-child(even) {
            background-color: rgba(15, 23, 42, 0.012);
        }
        .table tbody tr:hover {
            background-color: rgba(91, 91, 214, 0.04);
        }
        .table td {
            font-size: 0.92rem;
        }

        .form-control,
        .form-select {
            border-radius: var(--radius-control);
            border-color: var(--color-border);
            color: var(--color-text);
            background-color: #fff;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            min-height: 40px;
        }
        .form-control::placeholder {
            color: #9ca3af;
        }
        .form-control:focus,
        .form-select:focus {
            border-color: rgba(91, 91, 214, 0.45);
            box-shadow: 0 0 0 0.2rem rgba(91, 91, 214, 0.15);
        }
        .form-control:disabled,
        .form-select:disabled {
            background-color: #f9fafb;
            color: var(--color-text-muted);
            border-color: #edf0f5;
        }
        .form-label {
            color: var(--color-text);
            font-weight: 500;
            margin-bottom: 0.35rem;
        }
        .input-group-text {
            border-color: var(--color-border);
            background-color: #f8fafc;
            color: var(--color-text-muted);
        }
        .form-check-input:checked {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
        }
        .form-check-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(91, 91, 214, 0.15);
            border-color: rgba(91, 91, 214, 0.45);
        }
        .is-invalid {
            border-color: var(--color-danger) !important;
        }
        .invalid-feedback {
            color: #dc2626;
        }
        .is-valid {
            border-color: var(--color-success) !important;
        }
        .valid-feedback {
            color: #059669;
        }

        .badge {
            border-radius: var(--radius-pill);
            font-weight: 600;
            letter-spacing: 0.01em;
            padding: 0.38em 0.62em;
        }
        .bg-success {
            background-color: var(--color-success) !important;
        }
        .bg-warning {
            background-color: var(--color-warning) !important;
            color: #111827 !important;
        }
        .bg-danger {
            background-color: var(--color-danger) !important;
        }
        .bg-info {
            background-color: var(--color-info) !important;
        }

        .alert {
            border-radius: 10px;
            border: 1px solid transparent;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
        }
        .alert-success {
            border-color: rgba(16, 185, 129, 0.25);
            background-color: rgba(16, 185, 129, 0.1);
            color: #065f46;
        }
        .alert-danger {
            border-color: rgba(239, 68, 68, 0.25);
            background-color: rgba(239, 68, 68, 0.1);
            color: #7f1d1d;
        }
        .alert-warning {
            border-color: rgba(245, 158, 11, 0.25);
            background-color: rgba(245, 158, 11, 0.12);
            color: #78350f;
        }
        .alert-info {
            border-color: rgba(59, 130, 246, 0.25);
            background-color: rgba(59, 130, 246, 0.1);
            color: #1e3a8a;
        }

        .dropdown-menu {
            border: 1px solid var(--color-border);
            border-radius: 10px;
            box-shadow: var(--shadow-soft);
            padding: 0.35rem;
        }
        .dropdown-item {
            border-radius: 8px;
            font-size: 0.92rem;
        }
        .dropdown-item:active {
            background-color: var(--color-primary-soft);
            color: var(--color-text);
        }

        /* Scrollbars suaves para panel lateral */
        .sidebar::-webkit-scrollbar {
            width: 8px;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 999px;
        }
        .sidebar::-webkit-scrollbar-track {
            background-color: transparent;
        }

        /* ===== BRAND HELPERS ===== */
        .brand-short { display: none; }

        /* ==============================
           TABLET (769px - 1024px)
           ============================== */
        @media (max-width: 1024px) {
            :root { --sidebar-width: 210px; }
            .main-content {
                padding: 14px;
                max-width: 100%;
            }
            .hide-tablet { display: none !important; }
            .top-navbar .nav-link {
                font-size: 0.86rem;
                padding: 5px 8px;
            }
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
            .card {
                border-radius: 10px;
                margin-bottom: 12px;
            }
            .card-header,
            .card-body {
                padding-left: 12px;
                padding-right: 12px;
            }

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
            .top-navbar {
                padding-left: 8px;
                padding-right: 8px;
            }
            .top-navbar .navbar-brand {
                max-width: 62vw;
                overflow: hidden;
                text-overflow: ellipsis;
            }
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
