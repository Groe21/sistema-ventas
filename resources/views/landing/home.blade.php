@extends('layouts.landing')

@section('title', 'VentasPro - Sistema de Ventas en la Nube')

@section('content')

<!-- Hero Section -->
<section class="gradient-bg text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">
                Gestiona tu Negocio<br>desde Cualquier Lugar
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-purple-100">
                Sistema completo de punto de venta, inventario y clientes para pequeñas y medianas empresas
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('register.business') }}" class="bg-white text-purple-700 px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition shadow-xl">
                    <i class="fas fa-rocket mr-2"></i> Comenzar Gratis - 14 Días
                </a>
                <a href="#features" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-white hover:text-purple-700 transition">
                    <i class="fas fa-play-circle mr-2"></i> Ver Demo
                </a>
            </div>
            <p class="mt-4 text-purple-200 text-sm">
                <i class="fas fa-check-circle mr-1"></i> Sin tarjeta de crédito
                <i class="fas fa-check-circle ml-4 mr-1"></i> Cancelación en cualquier momento
                <i class="fas fa-check-circle ml-4 mr-1"></i> Soporte en español
            </p>
        </div>
        
        <!-- Hero Image / Dashboard Preview -->
        <div class="mt-16">
            <div class="bg-white rounded-lg shadow-2xl p-4 mx-auto max-w-5xl">
                <div class="bg-gray-100 rounded-lg p-8 text-center">
                    <i class="fas fa-desktop text-9xl text-gray-300"></i>
                    <p class="text-gray-500 mt-4">Vista previa del dashboard</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Todo lo que necesitas para tu negocio</h2>
            <p class="text-xl text-gray-600">Una solución completa e integrada</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="text-center p-8 rounded-xl card-hover bg-gray-50">
                <div class="feature-icon bg-purple-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-cash-register text-3xl text-purple-600"></i>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Punto de Venta</h3>
                <p class="text-gray-600">
                    Registra ventas rápidamente con búsqueda inteligente de productos, múltiples formas de pago y emisión de comprobantes.
                </p>
            </div>
            
            <!-- Feature 2 -->
            <div class="text-center p-8 rounded-xl card-hover bg-gray-50">
                <div class="feature-icon bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-boxes text-3xl text-blue-600"></i>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Inventario Inteligente</h3>
                <p class="text-gray-600">
                    Control de stock en tiempo real, alertas de productos bajos, y gestión de categorías y precios.
                </p>
            </div>
            
            <!-- Feature 3 -->
            <div class="text-center p-8 rounded-xl card-hover bg-gray-50">
                <div class="feature-icon bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-3xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Gestión de Clientes</h3>
                <p class="text-gray-600">
                    Base de datos de clientes, historial de compras y programa de lealtad con acumulación de puntos.
                </p>
            </div>
            
            <!-- Feature 4 -->
            <div class="text-center p-8 rounded-xl card-hover bg-gray-50">
                <div class="feature-icon bg-yellow-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-chart-line text-3xl text-yellow-600"></i>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Reportes y Estadísticas</h3>
                <p class="text-gray-600">
                    Visualiza tus ventas, productos más vendidos, clientes top y tendencias con gráficos interactivos.
                </p>
            </div>
            
            <!-- Feature 5 -->
            <div class="text-center p-8 rounded-xl card-hover bg-gray-50">
                <div class="feature-icon bg-red-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-wallet text-3xl text-red-600"></i>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Control de Caja</h3>
                <p class="text-gray-600">
                    Apertura y cierre de caja, registro de movimientos, control de efectivo y consolidado de ventas.
                </p>
            </div>
            
            <!-- Feature 6 -->
            <div class="text-center p-8 rounded-xl card-hover bg-gray-50">
                <div class="feature-icon bg-indigo-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-dollar-sign text-3xl text-indigo-600"></i>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Control de Billetes Grandes</h3>
                <p class="text-gray-600">
                    Registra y rastrea billetes de alta denominación con números de serie para mayor seguridad.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Section -->
<section id="pricing" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Planes para todo tipo de negocio</h2>
            <p class="text-xl text-gray-600">Comienza gratis por 14 días, sin tarjeta de crédito</p>
        </div>
        
        @php
            $plans = App\Models\Plan::where('is_active', true)->orderBy('price')->get();
        @endphp
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            @foreach($plans as $plan)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover {{ $plan->slug === 'business' ? 'ring-4 ring-purple-500' : '' }}">
                @if($plan->slug === 'business')
                    <div class="gradient-bg text-white text-center py-2 font-semibold">
                        <i class="fas fa-star mr-1"></i> MÁS POPULAR
                    </div>
                @endif
                
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
                    <div class="mb-6">
                        <span class="text-5xl font-bold gradient-text">${{ number_format($plan->price, 2) }}</span>
                        <span class="text-gray-600">/mes</span>
                    </div>
                    
                    <ul class="space-y-3 mb-8">
                        @if($plan->hasUnlimitedProducts())
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                <span class="text-gray-700"><strong>Productos ilimitados</strong></span>
                            </li>
                        @else
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                <span class="text-gray-700">Hasta <strong>{{ $plan->product_limit }}</strong> productos</span>
                            </li>
                        @endif
                        
                        @if($plan->hasUnlimitedUsers())
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                <span class="text-gray-700"><strong>Usuarios ilimitados</strong></span>
                            </li>
                        @else
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                <span class="text-gray-700">Hasta <strong>{{ $plan->user_limit }}</strong> usuarios</span>
                            </li>
                        @endif
                        
                        @foreach($plan->features as $feature)
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                <span class="text-gray-700">{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                    
                    <a href="{{ route('register.business', ['plan' => $plan->slug]) }}" 
                       class="block w-full text-center {{ $plan->slug === 'business' ? 'gradient-bg text-white' : 'bg-gray-900 text-white' }} py-3 rounded-lg font-semibold hover:opacity-90 transition">
                        Comenzar Ahora
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        
        <p class="text-center mt-8 text-gray-600">
            <i class="fas fa-shield-alt mr-1"></i> Todos los planes incluyen 14 días de prueba gratuita
        </p>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 gradient-bg">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-6">
            ¿Listo para transformar tu negocio?
        </h2>
        <p class="text-xl text-purple-100 mb-8">
            Únete a cientos de negocios que ya confían en VentasPro
        </p>
        <a href="{{ route('register.business') }}" class="inline-block bg-white text-purple-700 px-10 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition shadow-xl">
            <i class="fas fa-rocket mr-2"></i> Comenzar Gratis Ahora
        </a>
    </div>
</section>

@endsection
