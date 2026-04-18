@extends('layouts.landing')

@section('title', 'Características - VentasPro')

@section('content')

<!-- Header -->
<section class="gradient-bg text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl font-bold mb-4">Características Completas</h1>
        <p class="text-xl text-purple-100">Todo lo que necesitas para gestionar tu negocio de forma profesional</p>
    </div>
</section>

<!-- Features Grid -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- POS Features -->
        <div class="mb-20">
            <h2 class="text-3xl font-bold text-center mb-12">
                <i class="fas fa-cash-register gradient-text mr-2"></i> Punto de Venta
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-search text-3xl text-purple-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Búsqueda Inteligente</h3>
                    <p class="text-gray-600">Encuentra productos al instante por nombre, código o escaneo de código de barras.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-credit-card text-3xl text-purple-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Múltiples Formas de Pago</h3>
                    <p class="text-gray-600">Efectivo, tarjeta, transferencia y pagos mixtos en una sola venta.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-receipt text-3xl text-purple-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Comprobantes Digitales</h3>
                    <p class="text-gray-600">Genera e imprime facturas y boletas al instante con logo personalizado.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-shopping-cart text-3xl text-purple-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Carrito Flexible</h3>
                    <p class="text-gray-600">Ajusta cantidades, aplica descuentos y elimina productos fácilmente.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-percent text-3xl text-purple-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Descuentos Instantáneos</h3>
                    <p class="text-gray-600">Aplica descuentos por producto o al total de la venta.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-clock text-3xl text-purple-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Ventas Rápidas</h3>
                    <p class="text-gray-600">Interfaz optimizada para atender clientes en segundos.</p>
                </div>
            </div>
        </div>
        
        <!-- Inventory Features -->
        <div class="mb-20">
            <h2 class="text-3xl font-bold text-center mb-12">
                <i class="fas fa-boxes gradient-text mr-2"></i> Inventario
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-warehouse text-3xl text-blue-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Control de Stock</h3>
                    <p class="text-gray-600">Seguimiento en tiempo real de existencias por producto.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-bell text-3xl text-blue-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Alertas de Stock Bajo</h3>
                    <p class="text-gray-600">Notificaciones automáticas cuando productos están por agotarse.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-tags text-3xl text-blue-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Categorías</h3>
                    <p class="text-gray-600">Organiza productos en categorías personalizadas.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-dollar-sign text-3xl text-blue-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Gestión de Precios</h3>
                    <p class="text-gray-600">Define precios de compra, venta y márgenes de ganancia.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-barcode text-3xl text-blue-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Códigos de Barras</h3>
                    <p class="text-gray-600">Genera e imprime códigos de barras para tus productos.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-sync text-3xl text-blue-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Actualización Automática</h3>
                    <p class="text-gray-600">Stock actualizado automáticamente con cada venta.</p>
                </div>
            </div>
        </div>
        
        <!-- Customer Features -->
        <div class="mb-20">
            <h2 class="text-3xl font-bold text-center mb-12">
                <i class="fas fa-users gradient-text mr-2"></i> Clientes
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-address-book text-3xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Base de Datos</h3>
                    <p class="text-gray-600">Registra información completa de cada cliente.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-star text-3xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Programa de Lealtad</h3>
                    <p class="text-gray-600">Los clientes acumulan puntos por cada compra.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-history text-3xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Historial de Compras</h3>
                    <p class="text-gray-600">Consulta todas las compras de cada cliente.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-gift text-3xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Redención de Puntos</h3>
                    <p class="text-gray-600">Convierte puntos acumulados en descuentos.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-mobile-alt text-3xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Portal del Cliente</h3>
                    <p class="text-gray-600">Tus clientes pueden consultar sus puntos en línea.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-chart-pie text-3xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Clientes Top</h3>
                    <p class="text-gray-600">Identifica tus mejores clientes por ventas.</p>
                </div>
            </div>
        </div>
        
        <!-- Reports Features -->
        <div class="mb-20">
            <h2 class="text-3xl font-bold text-center mb-12">
                <i class="fas fa-chart-line gradient-text mr-2"></i> Reportes
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-chart-bar text-3xl text-yellow-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Dashboard Interactivo</h3>
                    <p class="text-gray-600">Visualiza métricas clave de tu negocio en tiempo real.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-money-bill-wave text-3xl text-yellow-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Reporte de Ventas</h3>
                    <p class="text-gray-600">Analiza ventas por día, semana, mes o período personalizado.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-trophy text-3xl text-yellow-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Productos Más Vendidos</h3>
                    <p class="text-gray-600">Identifica qué productos generan más ingresos.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-crown text-3xl text-yellow-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Top Clientes</h3>
                    <p class="text-gray-600">Conoce quiénes son tus mejores compradores.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-calendar-day text-3xl text-yellow-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Ventas por Período</h3>
                    <p class="text-gray-600">Compara rendimiento entre diferentes períodos.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-file-export text-3xl text-yellow-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Exportar Datos</h3>
                    <p class="text-gray-600">Descarga reportes en PDF o Excel.</p>
                </div>
            </div>
        </div>
        
        <!-- Cash Management Features -->
        <div>
            <h2 class="text-3xl font-bold text-center mb-12">
                <i class="fas fa-wallet gradient-text mr-2"></i> Control de Caja
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-cash-register text-3xl text-red-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Apertura/Cierre</h3>
                    <p class="text-gray-600">Registra apertura y cierre de caja con monto inicial.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-exchange-alt text-3xl text-red-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Movimientos de Efectivo</h3>
                    <p class="text-gray-600">Registra ingresos y egresos adicionales.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-calculator text-3xl text-red-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Arqueo de Caja</h3>
                    <p class="text-gray-600">Cuadre automático de efectivo vs. ventas registradas.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-money-check-alt text-3xl text-red-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Consolidado de Ventas</h3>
                    <p class="text-gray-600">Resumen completo de ventas por forma de pago.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-file-invoice-dollar text-3xl text-red-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Billetes de Alta Denominación</h3>
                    <p class="text-gray-600">Control especial de billetes de $50 y $100 con serie.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-history text-3xl text-red-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Historial Completo</h3>
                    <p class="text-gray-600">Consulta movimientos de efectivo de períodos anteriores.</p>
                </div>
            </div>
        </div>
        
    </div>
</section>

<!-- Additional Features -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center mb-12">Y mucho más...</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
            <div class="flex items-start bg-white p-6 rounded-lg shadow">
                <i class="fas fa-users-cog text-2xl text-purple-600 mr-4 mt-1"></i>
                <div>
                    <h3 class="font-bold mb-1">Multi-usuario</h3>
                    <p class="text-gray-600 text-sm">Crea múltiples usuarios con diferentes roles y permisos.</p>
                </div>
            </div>
            
            <div class="flex items-start bg-white p-6 rounded-lg shadow">
                <i class="fas fa-cloud text-2xl text-purple-600 mr-4 mt-1"></i>
                <div>
                    <h3 class="font-bold mb-1">100% en la Nube</h3>
                    <p class="text-gray-600 text-sm">Accede desde cualquier dispositivo con internet.</p>
                </div>
            </div>
            
            <div class="flex items-start bg-white p-6 rounded-lg shadow">
                <i class="fas fa-mobile-alt text-2xl text-purple-600 mr-4 mt-1"></i>
                <div>
                    <h3 class="font-bold mb-1">Responsive</h3>
                    <p class="text-gray-600 text-sm">Funciona perfectamente en PC, tablet y móvil.</p>
                </div>
            </div>
            
            <div class="flex items-start bg-white p-6 rounded-lg shadow">
                <i class="fas fa-shield-alt text-2xl text-purple-600 mr-4 mt-1"></i>
                <div>
                    <h3 class="font-bold mb-1">Seguridad Garantizada</h3>
                    <p class="text-gray-600 text-sm">Encriptación SSL y backups automáticos diarios.</p>
                </div>
            </div>
            
            <div class="flex items-start bg-white p-6 rounded-lg shadow">
                <i class="fas fa-headset text-2xl text-purple-600 mr-4 mt-1"></i>
                <div>
                    <h3 class="font-bold mb-1">Soporte en Español</h3>
                    <p class="text-gray-600 text-sm">Equipo de soporte listo para ayudarte.</p>
                </div>
            </div>
            
            <div class="flex items-start bg-white p-6 rounded-lg shadow">
                <i class="fas fa-sync-alt text-2xl text-purple-600 mr-4 mt-1"></i>
                <div>
                    <h3 class="font-bold mb-1">Actualizaciones Automáticas</h3>
                    <p class="text-gray-600 text-sm">Nuevas características sin costo adicional.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-20 gradient-bg">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-6">
            ¿Listo para probar todas estas características?
        </h2>
        <p class="text-xl text-purple-100 mb-8">
            Comienza gratis por 14 días, sin tarjeta de crédito
        </p>
        <a href="{{ route('register.business') }}" class="inline-block bg-white text-purple-700 px-10 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition shadow-xl">
            <i class="fas fa-rocket mr-2"></i> Comenzar Ahora
        </a>
    </div>
</section>

@endsection
