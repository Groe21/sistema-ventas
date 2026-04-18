@extends('layouts.landing')

@section('title', 'Registrar Nueva Cuenta - VentasPro')

@section('content')

<section class="py-20 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Crea tu Cuenta Gratis</h1>
            <p class="text-xl text-gray-600">Comienza tu prueba gratuita de 14 días sin tarjeta de crédito</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-8 md:p-12">
            
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-semibold">Por favor corrige los siguientes errores:</p>
                            <ul class="mt-2 text-sm text-red-600 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            
            <form method="POST" action="{{ route('register.business.store') }}" class="space-y-8">
                @csrf
                
                <!-- Business Information -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-store text-purple-600 mr-2"></i>
                        Información del Negocio
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Business Name -->
                        <div>
                            <label for="business_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre del Negocio <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="business_name" 
                                   name="business_name" 
                                   value="{{ old('business_name') }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                   placeholder="Ej: Tienda El Ahorro">
                            @error('business_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- RUC -->
                        <div>
                            <label for="business_ruc" class="block text-sm font-medium text-gray-700 mb-2">
                                RUC <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="business_ruc" 
                                   name="business_ruc" 
                                   value="{{ old('business_ruc') }}"
                                   required
                                   maxlength="13"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                   placeholder="1234567890001">
                            @error('business_ruc')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Email -->
                        <div>
                            <label for="business_email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email del Negocio <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   id="business_email" 
                                   name="business_email" 
                                   value="{{ old('business_email') }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                   placeholder="contacto@tunegocio.com">
                            @error('business_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Phone -->
                        <div>
                            <label for="business_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Teléfono <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" 
                                   id="business_phone" 
                                   name="business_phone" 
                                   value="{{ old('business_phone') }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                   placeholder="0991234567">
                            @error('business_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label for="business_address" class="block text-sm font-medium text-gray-700 mb-2">
                                Dirección <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="business_address" 
                                   name="business_address" 
                                   value="{{ old('business_address') }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                   placeholder="Av. Principal #123 y Secundaria">
                            @error('business_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- City -->
                        <div>
                            <label for="business_city" class="block text-sm font-medium text-gray-700 mb-2">
                                Ciudad <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="business_city" 
                                   name="business_city" 
                                   value="{{ old('business_city') }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                   placeholder="Quito">
                            @error('business_city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Province -->
                        <div>
                            <label for="business_province" class="block text-sm font-medium text-gray-700 mb-2">
                                Provincia
                            </label>
                            <input type="text" 
                                   id="business_province" 
                                   name="business_province" 
                                   value="{{ old('business_province') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                   placeholder="Pichincha">
                            @error('business_province')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Admin User Information -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-user-shield text-purple-600 mr-2"></i>
                        Información del Administrador
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Admin Name -->
                        <div>
                            <label for="admin_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre Completo <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="admin_name" 
                                   name="admin_name" 
                                   value="{{ old('admin_name') }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                   placeholder="Juan Pérez">
                            @error('admin_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Admin Email -->
                        <div>
                            <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   id="admin_email" 
                                   name="admin_email" 
                                   value="{{ old('admin_email') }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                   placeholder="tu@email.com">
                            @error('admin_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                   placeholder="Mínimo 8 caracteres">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Password Confirmation -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirmar Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                   placeholder="Repite la contraseña">
                        </div>
                    </div>
                </div>
                
                <!-- Plan Selection -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-tags text-purple-600 mr-2"></i>
                        Selecciona tu Plan
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($plans as $plan)
                            <label for="plan_{{ $plan->id }}" 
                                   class="cursor-pointer">
                                <input type="radio" 
                                       id="plan_{{ $plan->id }}" 
                                       name="plan_id" 
                                       value="{{ $plan->id }}"
                                       class="peer hidden"
                                       {{ ($selectedPlan === $plan->slug || (!$selectedPlan && $plan->slug === 'business')) ? 'checked' : '' }}
                                       required>
                                <div class="border-2 border-gray-300 rounded-lg p-6 peer-checked:border-purple-500 peer-checked:bg-purple-50 transition hover:border-purple-300">
                                    <h3 class="text-xl font-bold mb-2">{{ $plan->name }}</h3>
                                    <p class="text-3xl font-bold gradient-text mb-3">
                                        ${{ number_format($plan->price, 2) }}<span class="text-sm text-gray-600">/mes</span>
                                    </p>
                                    <ul class="text-sm space-y-1 text-gray-600">
                                        <li><i class="fas fa-check text-green-500 mr-1"></i> 
                                            {{ $plan->hasUnlimitedProducts() ? 'Productos ilimitados' : $plan->product_limit . ' productos' }}
                                        </li>
                                        <li><i class="fas fa-check text-green-500 mr-1"></i> 
                                            {{ $plan->hasUnlimitedUsers() ? 'Usuarios ilimitados' : $plan->user_limit . ' usuarios' }}
                                        </li>
                                    </ul>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('plan_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Terms and Conditions -->
                <div>
                    <label class="flex items-start">
                        <input type="checkbox" 
                               name="terms" 
                               id="terms"
                               required
                               class="mt-1 mr-3 h-5 w-5 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                        <span class="text-sm text-gray-700">
                            Acepto los <a href="#" class="text-purple-600 hover:underline">Términos y Condiciones</a> 
                            y la <a href="#" class="text-purple-600 hover:underline">Política de Privacidad</a> de VentasPro
                            <span class="text-red-500">*</span>
                        </span>
                    </label>
                    @error('terms')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="w-full gradient-bg text-white py-4 rounded-lg font-bold text-lg hover:opacity-90 transition shadow-lg">
                        <i class="fas fa-rocket mr-2"></i> Crear Cuenta y Comenzar Prueba Gratis
                    </button>
                    
                    <p class="mt-4 text-center text-sm text-gray-600">
                        <i class="fas fa-lock mr-1 text-green-500"></i>
                        Tu información está segura. 14 días gratis, sin tarjeta de crédito requerida.
                    </p>
                </div>
            </form>
            
            <!-- Already have account -->
            <div class="mt-6 text-center border-t pt-6">
                <p class="text-gray-600">
                    ¿Ya tienes una cuenta? 
                    <a href="{{ route('login') }}" class="text-purple-600 font-semibold hover:underline">
                        Inicia Sesión
                    </a>
                </p>
            </div>
            
        </div>
        
    </div>
</section>

@endsection
