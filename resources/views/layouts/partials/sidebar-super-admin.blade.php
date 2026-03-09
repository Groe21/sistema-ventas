<!-- Sidebar Super Admin -->
<a class="nav-link {{ request()->routeIs('super-admin.dashboard') ? 'active' : '' }}" href="{{ route('super-admin.dashboard') }}">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>

<a class="nav-link {{ request()->routeIs('super-admin.businesses.*') ? 'active' : '' }}" href="{{ route('super-admin.businesses.index') }}">
    <i class="bi bi-building"></i> Negocios
</a>

<a class="nav-link {{ request()->routeIs('super-admin.users.*') ? 'active' : '' }}" href="{{ route('super-admin.users.index') }}">
    <i class="bi bi-people"></i> Usuarios
</a>

<a class="nav-link {{ request()->routeIs('super-admin.subscriptions.*') ? 'active' : '' }}" href="{{ route('super-admin.subscriptions.index') }}">
    <i class="bi bi-credit-card"></i> Suscripciones
</a>

<a class="nav-link {{ request()->routeIs('super-admin.plans.*') ? 'active' : '' }}" href="{{ route('super-admin.plans.index') }}">
    <i class="bi bi-collection"></i> Planes
</a>

<a class="nav-link {{ request()->routeIs('super-admin.reports.*') ? 'active' : '' }}" href="{{ route('super-admin.reports.index') }}">
    <i class="bi bi-graph-up"></i> Reportes Globales
</a>

<hr class="text-white-50">

<a class="nav-link {{ request()->routeIs('super-admin.settings.*') ? 'active' : '' }}" href="{{ route('super-admin.settings.index') }}">
    <i class="bi bi-gear"></i> Configuración
</a>
