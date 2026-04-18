<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #3498db;
            margin: 0;
            font-size: 28px;
        }
        .welcome-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 30px;
        }
        .content h2 {
            color: #2c3e50;
            font-size: 22px;
            margin-top: 30px;
        }
        .feature-list {
            background-color: #f8f9fa;
            border-left: 4px solid #3498db;
            padding: 15px 20px;
            margin: 20px 0;
        }
        .feature-list ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .feature-list li {
            margin: 8px 0;
        }
        .cta-button {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .cta-button:hover {
            background-color: #2980b9;
        }
        .info-box {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .info-box strong {
            color: #856404;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
        .trial-badge {
            display: inline-block;
            background-color: #27ae60;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="welcome-icon">🎉</div>
            <h1>¡Bienvenido a Sistema Comercial Pro!</h1>
            <p style="color: #666; font-size: 16px;">
                Hola <strong>{{ $user->name }}</strong>, tu cuenta ha sido creada exitosamente
            </p>
            <span class="trial-badge">✨ 14 días de prueba GRATIS</span>
        </div>

        <div class="content">
            <p style="font-size: 16px;">
                ¡Felicidades! Tu negocio <strong>{{ $user->business->name }}</strong> está listo para despegar. 
                Ahora tienes acceso completo a todas las funcionalidades de nuestro sistema.
            </p>

            <div class="info-box">
                <strong>📅 Tu período de prueba:</strong><br>
                Tienes <strong>14 días gratis</strong> para explorar todas las funcionalidades. 
                No se requiere tarjeta de crédito durante este período.
            </div>

            <h2>🚀 Primeros Pasos</h2>
            <div class="feature-list">
                <ul>
                    <li><strong>Gestiona tu inventario:</strong> Agrega tus productos o servicios</li>
                    <li><strong>Registra clientes:</strong> Crea tu base de datos de clientes</li>
                    <li><strong>Realiza ventas:</strong> Usa nuestro punto de venta intuitivo</li>
                    <li><strong>Genera reportes:</strong> Analiza el rendimiento de tu negocio</li>
                    <li><strong>Configura tu negocio:</strong> Personaliza facturación, impuestos y más</li>
                </ul>
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/admin/dashboard') }}" class="cta-button">
                    🎯 Ir al Dashboard
                </a>
            </div>

            <h2>💡 Consejos para comenzar</h2>
            <p>Al iniciar sesión, verás un <strong>tour guiado interactivo</strong> que te mostrará las funcionalidades principales. Te recomendamos completarlo para familiarizarte con el sistema.</p>

            <div class="feature-list">
                <p><strong>Plan actual:</strong> {{ $user->business->currentPlan()->name ?? 'Sin plan' }}</p>
                @if($user->business->currentSubscription())
                    <p><strong>Estado:</strong> Período de prueba hasta el {{ $user->business->currentSubscription()->ends_at->format('d/m/Y') }}</p>
                @endif
            </div>

            <h2>📞 ¿Necesitas ayuda?</h2>
            <p>Nuestro equipo de soporte está aquí para asistirte:</p>
            <ul>
                <li>📧 Email: soporte@sistemacomercial.com</li>
                <li>💬 Chat en vivo disponible en el dashboard</li>
                <li>📚 Centro de ayuda: <a href="{{ url('/ayuda') }}">ayuda.sistemacomercial.com</a></li>
            </ul>
        </div>

        <div class="footer">
            <p>
                Este email fue enviado porque registraste una cuenta en Sistema Comercial Pro.<br>
                Si no realizaste este registro, por favor contacta con soporte.
            </p>
            <p style="margin-top: 15px;">
                © {{ date('Y') }} Sistema Comercial Pro. Todos los derechos reservados.
            </p>
        </div>
    </div>
</body>
</html>
