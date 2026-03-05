<!-- Sidebar Admin/Empleado -->
<a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>

<a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
    <i class="bi bi-box-seam"></i> Productos
</a>

<a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
    <i class="bi bi-people"></i> Clientes
</a>

<a class="nav-link {{ request()->routeIs('pos.*') ? 'active' : '' }}" href="{{ route('pos.index') }}">
    <i class="bi bi-cart3"></i> Punto de Venta
</a>

<a class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}" href="{{ route('sales.index') }}">
    <i class="bi bi-receipt"></i> Ventas
</a>

<a class="nav-link {{ request()->routeIs('cash.*') ? 'active' : '' }}" href="{{ route('cash.index') }}">
    <i class="bi bi-cash-stack"></i> Flujo de Caja
</a>

<hr class="text-white-50">

@if(auth()->user()->isAdmin())
<a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
    <i class="bi bi-person-badge"></i> Usuarios
</a>

<a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
    <i class="bi bi-graph-up"></i> Reportes
</a>

<a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
    <i class="bi bi-gear"></i> Configuración
</a>
@endif
