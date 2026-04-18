<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistema de Ventas - Tu negocio en la nube')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            transition: all 0.3s ease;
        }
        
        .feature-icon:hover {
            transform: scale(1.1);
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('landing') }}" class="flex items-center">
                        <i class="fas fa-store text-3xl gradient-text mr-2"></i>
                        <span class="text-2xl font-bold gradient-text">VentasPro</span>
                    </a>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('landing') }}" class="text-gray-700 hover:text-purple-600 transition">Inicio</a>
                    <a href="{{ route('landing.pricing') }}" class="text-gray-700 hover:text-purple-600 transition">Planes</a>
                    <a href="{{ route('landing.features') }}" class="text-gray-700 hover:text-purple-600 transition">Características</a>
                    <a href="{{ route('customer-points') }}" class="text-gray-700 hover:text-purple-600 transition">Portal Clientes</a>
                    
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-purple-600 transition">
                            <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-purple-600 transition">Iniciar Sesión</a>
                        <a href="{{ route('register.business') }}" class="gradient-bg text-white px-6 py-2 rounded-lg hover:opacity-90 transition font-semibold">
                            Probar Gratis
                        </a>
                    @endauth
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-button" class="text-gray-700 hover:text-purple-600">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
            <div class="px-4 pt-2 pb-4 space-y-2">
                <a href="{{ route('landing') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">Inicio</a>
                <a href="{{ route('landing.pricing') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">Planes</a>
                <a href="{{ route('landing.features') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">Características</a>
                <a href="{{ route('customer-points') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">Portal Clientes</a>
                
                @auth
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">Iniciar Sesión</a>
                    <a href="{{ route('register.business') }}" class="block px-3 py-2 gradient-bg text-white rounded text-center font-semibold">Probar Gratis</a>
                @endauth
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div>
                    <div class="flex items-center mb-4">
                        <i class="fas fa-store text-3xl text-purple-400 mr-2"></i>
                        <span class="text-2xl font-bold text-white">VentasPro</span>
                    </div>
                    <p class="text-sm text-gray-400">
                        La solución completa para gestionar tu negocio. Punto de venta, inventario, clientes y más.
                    </p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Enlaces Rápidos</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('landing') }}" class="hover:text-purple-400 transition">Inicio</a></li>
                        <li><a href="{{ route('landing.pricing') }}" class="hover:text-purple-400 transition">Planes y Precios</a></li>
                        <li><a href="{{ route('landing.features') }}" class="hover:text-purple-400 transition">Características</a></li>
                    </ul>
                </div>
                
                <!-- Legal -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Legal</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-purple-400 transition">Términos de Servicio</a></li>
                        <li><a href="#" class="hover:text-purple-400 transition">Política de Privacidad</a></li>
                        <li><a href="#" class="hover:text-purple-400 transition">Política de Reembolso</a></li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Contacto</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-2 text-purple-400"></i>
                            <a href="mailto:soporte@ventaspro.com" class="hover:text-purple-400 transition">soporte@ventaspro.com</a>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-2 text-purple-400"></i>
                            <span>+593 99 123 4567</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-purple-400"></i>
                            <span>Quito, Ecuador</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} VentasPro. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
