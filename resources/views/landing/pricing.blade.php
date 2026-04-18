@extends('layouts.landing')

@section('title', 'Planes y Precios - VentasPro')

@section('content')

<!-- Header -->
<section class="gradient-bg text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl font-bold mb-4">Planes y Precios</h1>
        <p class="text-xl text-purple-100">Elige el plan perfecto para tu negocio. Sin sorpresas, sin costos ocultos.</p>
    </div>
</section>

<!-- Pricing Cards -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @php
            $plans = App\Models\Plan::where('is_active', true)->orderBy('price')->get();
        @endphp
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            @foreach($plans as $plan)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover {{ $plan->slug === 'business' ? 'ring-4 ring-purple-500 transform scale-105' : '' }}">
                @if($plan->slug === 'business')
                    <div class="gradient-bg text-white text-center py-2 font-semibold">
                        <i class="fas fa-star mr-1"></i> RECOMENDADO
                    </div>
                @endif
                
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
                    <div class="mb-6">
                        <span class="text-5xl font-bold gradient-text">${{ number_format($plan->price, 2) }}</span>
                        <span class="text-gray-600">/mes</span>
                    </div>
                    
                    <div class="mb-8">
                        @if($plan->price > 0)
                            <p class="text-sm text-gray-500">+ IVA (si aplica)</p>
                        @else
                            <p class="text-sm text-green-600 font-semibold">Gratis para siempre</p>
                        @endif
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
                        Comenzar con {{ $plan->name }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Comparison Table -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center mb-12">Comparación Detallada</h2>
        
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-4 text-left font-bold text-gray-900">Característica</th>
                        @foreach($plans as $plan)
                            <th class="p-4 text-center font-bold text-gray-900">{{ $plan->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        <td class="p-4 font-semibold">Productos</td>
                        @foreach($plans as $plan)
                            <td class="p-4 text-center">
                                {{ $plan->hasUnlimitedProducts() ? '∞ Ilimitados' : $plan->product_limit }}
                            </td>
                        @endforeach
                    </tr>
                    <tr class="border-b bg-gray-50">
                        <td class="p-4 font-semibold">Usuarios</td>
                        @foreach($plans as $plan)
                            <td class="p-4 text-center">
                                {{ $plan->hasUnlimitedUsers() ? '∞ Ilimitados' : $plan->user_limit }}
                            </td>
                        @endforeach
                    </tr>
                    <tr class="border-b">
                        <td class="p-4 font-semibold">Punto de Venta</td>
                        @foreach($plans as $plan)
                            <td class="p-4 text-center"><i class="fas fa-check text-green-500"></i></td>
                        @endforeach
                    </tr>
                    <tr class="border-b bg-gray-50">
                        <td class="p-4 font-semibold">Gestión de Inventario</td>
                        @foreach($plans as $plan)
                            <td class="p-4 text-center"><i class="fas fa-check text-green-500"></i></td>
                        @endforeach
                    </tr>
                    <tr class="border-b">
                        <td class="p-4 font-semibold">Control de Caja</td>
                        @foreach($plans as $plan)
                            <td class="p-4 text-center"><i class="fas fa-check text-green-500"></i></td>
                        @endforeach
                    </tr>
                    <tr class="border-b bg-gray-50">
                        <td class="p-4 font-semibold">Reportes Básicos</td>
                        @foreach($plans as $plan)
                            <td class="p-4 text-center"><i class="fas fa-check text-green-500"></i></td>
                        @endforeach
                    </tr>
                    <tr class="border-b">
                        <td class="p-4 font-semibold">Reportes Avanzados</td>
                        @foreach($plans as $plan)
                            <td class="p-4 text-center">
                                @if($plan->hasFeature('advanced_reports'))
                                    <i class="fas fa-check text-green-500"></i>
                                @else
                                    <i class="fas fa-times text-gray-300"></i>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr class="border-b bg-gray-50">
                        <td class="p-4 font-semibold">Programa de Lealtad</td>
                        @foreach($plans as $plan)
                            <td class="p-4 text-center">
                                @if($plan->hasFeature('loyalty_program'))
                                    <i class="fas fa-check text-green-500"></i>
                                @else
                                    <i class="fas fa-times text-gray-300"></i>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr class="border-b">
                        <td class="p-4 font-semibold">Control de Billetes Grandes</td>
                        @foreach($plans as $plan)
                            <td class="p-4 text-center">
                                @if($plan->hasFeature('large_bills_tracking'))
                                    <i class="fas fa-check text-green-500"></i>
                                @else
                                    <i class="fas fa-times text-gray-300"></i>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr class="border-b bg-gray-50">
                        <td class="p-4 font-semibold">Soporte Prioritario</td>
                        @foreach($plans as $plan)
                            <td class="p-4 text-center">
                                @if($plan->hasFeature('priority_support'))
                                    <i class="fas fa-check text-green-500"></i>
                                @else
                                    <i class="fas fa-times text-gray-300"></i>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="py-20 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center mb-12">Preguntas Frecuentes</h2>
        
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-bold text-lg mb-2"><i class="fas fa-question-circle text-purple-600 mr-2"></i>¿Puedo cambiar de plan en cualquier momento?</h3>
                <p class="text-gray-600">Sí, puedes actualizar o bajar de plan cuando quieras. Los cambios se aplican inmediatamente y ajustamos la facturación de forma proporcional.</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-bold text-lg mb-2"><i class="fas fa-question-circle text-purple-600 mr-2"></i>¿Qué formas de pago aceptan?</h3>
                <p class="text-gray-600">Aceptamos tarjetas de crédito/débito (Visa, Mastercard, American Express) y transferencias bancarias.</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-bold text-lg mb-2"><i class="fas fa-question-circle text-purple-600 mr-2"></i>¿Hay costos adicionales o tarifas ocultas?</h3>
                <p class="text-gray-600">No. El precio que ves es el precio que pagas. No hay costos de instalación, configuración o soporte.</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-bold text-lg mb-2"><i class="fas fa-question-circle text-purple-600 mr-2"></i>¿Puedo cancelar en cualquier momento?</h3>
                <p class="text-gray-600">Sí, puedes cancelar tu suscripción cuando quieras sin penalizaciones. Tu acceso continuará hasta el final del período pagado.</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-bold text-lg mb-2"><i class="fas fa-question-circle text-purple-600 mr-2"></i>¿Mis datos están seguros?</h3>
                <p class="text-gray-600">Absolutamente. Usamos encriptación SSL de nivel bancario y backups automáticos diarios. Tus datos nunca se comparten con terceros.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-20 gradient-bg">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-6">
            ¿Listo para comenzar?
        </h2>
        <p class="text-xl text-purple-100 mb-8">
            Prueba gratis por 14 días, sin tarjeta de crédito
        </p>
        <a href="{{ route('register.business') }}" class="inline-block bg-white text-purple-700 px-10 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition shadow-xl">
            <i class="fas fa-rocket mr-2"></i> Comenzar Ahora
        </a>
    </div>
</section>

@endsection
