# 🚀 ROADMAP: Convertir el Sistema en SaaS Completo

> **Estado Actual:** Sistema multitenancy funcional con registro público automático  
> **Objetivo:** SaaS público con registro automático, pagos recurrentes y autogestión

---

## 📊 Estado Actual: 85% Completo ✨

### ✅ Ya Implementado
- [x] Arquitectura multitenancy (business_id en todas las tablas)
- [x] Modelo de planes con features y límites
- [x] Sistema de suscripciones con estados
- [x] Panel Super Admin completo
- [x] Middleware de control de acceso y límites
- [x] Validación automática de features por plan
- [x] Períodos de prueba (trial)
- [x] Gestión de configuraciones por negocio (BusinessSetting)
- [x] **Landing page pública profesional**
- [x] **Sistema de registro público automático**
- [x] **Creación automática de suscripciones trial (14 días)**
- [x] **Creación automática de cliente "Consumidor Final"**
- [x] **Campo onboarding_completed en businesses**
- [x] **Tour guiado interactivo con Driver.js**
- [x] **Email de bienvenida automático**

---

## 🎉 FASE 1: Onboarding Público ✅ COMPLETADA

> **Dividida en dos subfases para mejor organización:**
> - **1A:** Captación y Registro (Landing + Registro público)
> - **1B:** Activación y Onboarding (Tour + Email bienvenida)

---

### 🌐 FASE 1A: Captación y Registro Público ✅ COMPLETADA

**Objetivo:** Permitir que nuevos clientes descubran el sistema y se registren sin intervención manual

#### 1.1 Landing Page Pública ✅
**Archivos creados:**
```
resources/views/landing/
├── home.blade.php          # Página principal con precios ✅
├── pricing.blade.php       # Tabla comparativa de planes ✅
├── features.blade.php      # Demo de funcionalidades ✅
└── layouts/
    └── landing.blade.php   # Layout sin auth ✅
```

**Características implementadas:**
- [x] Layout público (sin sidebar/navbar de admin)
- [x] Hero section con propuesta de valor
- [x] Tabla de comparación de planes (Starter/Business/Premium)
- [x] Sección de características principales
- [x] Testimonios de clientes
- [x] FAQs
- [x] Footer con enlaces legales

**Rutas implementadas:**
```php
Route::get('/', [LandingController::class, 'home'])->name('landing.home');
Route::get('/planes', [LandingController::class, 'pricing'])->name('landing.pricing');
Route::get('/caracteristicas', [LandingController::class, 'features'])->name('landing.features');
```

---

#### 1.2 Formulario de Registro Público ✅
**Archivo:** `app/Http/Controllers/RegisterController.php` ✅

**Proceso automático implementado:**
1. ✅ Validar datos del negocio + admin
2. ✅ Crear Business
3. ✅ Crear suscripción en 'trial' (14 días)
4. ✅ Crear usuario admin con hash de contraseña
5. ✅ Crear cliente "Consumidor Final" por defecto
6. ✅ Enviar email de bienvenida
7. ✅ Login automático del usuario
8. ✅ Redirigir al dashboard con tour activado

**Campos del formulario implementados:**
- ✅ Datos del negocio (nombre, RUC, dirección, teléfono, email, ciudad, provincia)
- ✅ Datos del admin (nombre, email, contraseña confirmada)
- ✅ Plan seleccionado (tarjetas radio con precios dinámicos)
- ✅ Checkbox: Términos y condiciones
- ✅ Validaciones completas con mensajes en español

**Vista:** `resources/views/auth/register-business.blade.php` ✅

**Rutas implementadas:**
```php
Route::get('/registro', [RegisterController::class, 'showRegistrationForm'])->name('register.business');
Route::post('/registro', [RegisterController::class, 'register'])->name('register.business.store');
```

---

### 🚀 FASE 1B: Activación y Onboarding ✅ COMPLETADA

**Objetivo:** Guiar y activar a nuevos usuarios en su primer uso del sistema

#### 1.3 Tour Guiado Interactivo ✅
**Librería utilizada:** [Driver.js v1.3.1](https://driverjs.com/)

**Implementación:**
- ✅ CDN de Driver.js incluido en layout principal
- ✅ Script `public/js/onboarding-tour.js` creado
- ✅ 7 pasos del tour implementados:
  1. Bienvenida al dashboard
  2. Gestión de Productos
  3. Registro de Clientes
  4. Punto de Venta (POS)
  5. Reportes y Estadísticas
  6. Configuración
  7. Mensaje final de éxito

**Funcionalidades:**
- [x] Detección automática si mostrar tour (basado en sesión)
- [x] Indicador de progreso "Paso X de 7"
- [x] Botones: Siguiente, Anterior, Cerrar
- [x] Overlay oscuro para destacar elementos
- [x] Textos en español personalizados
- [x] Responsive (adaptado a móvil/tablet)

**Endpoint API creado:**
```php
POST /api/complete-onboarding
// Marca business.onboarding_completed = true
// Usa middleware: ['web', 'auth']
```

**Integración en layout:**
```javascript
// resources/views/layouts/app.blade.php
<body data-show-onboarding-tour="{{ session('show_onboarding_tour') ? 'true' : 'false' }}">
<script>window.business = {...};</script>
```

---

#### 1.4 Email de Bienvenida ✅
**Archivo:** `app/Mail/WelcomeMail.php` ✅  
**Vista:** `resources/views/emails/welcome.blade.php` ✅

**Contenido del email:**
- ✅ Saludo personalizado con nombre del usuario
- ✅ Badges de período de prueba (14 días gratis)
- ✅ Información del plan seleccionado
- ✅ Guía de primeros pasos:
  - Gestionar inventario
  - Registrar clientes
  - Realizar ventas en POS
  - Generar reportes
  - Configurar negocio
- ✅ Botón CTA "Ir al Dashboard"
- ✅ Menciona el tour guiado interactivo
- ✅ Información de soporte (email, chat, centro de ayuda)
- ✅ Footer profesional con copyright

**Características del diseño:**
- [x] HTML responsive compatible con todos los clientes de email
- [x] Estilos inline para máxima compatibilidad
- [x] Paleta de colores consistente con la marca
- [x] Iconos emoji para mejor engagement
- [x] Cajas informativas destacadas

**Envío automático:**
```php
// En RegisterController::register()
Mail::to($user)->send(new WelcomeMail($user));
```

---

## 📋 Resumen de Implementación FASE 1

| Subfase | Componente | Estado | Archivos Creados |
|---------|-----------|--------|------------------|
| **1A** | Landing Pages | ✅ | 4 vistas Blade |
| **1A** | Controlador Landing | ✅ | LandingController.php |
| **1A** | Formulario Registro | ✅ | RegisterController.php |
| **1A** | Vista Registro | ✅ | register-business.blade.php |
| **1B** | Tour Guiado | ✅ | onboarding-tour.js |
| **1B** | API Onboarding | ✅ | Ruta en api.php |
| **1B** | Email Bienvenida | ✅ | WelcomeMail.php + vista |
| **1B** | Integración Layout | ✅ | app.blade.php actualizado |

**Total de archivos nuevos:** 8  
**Total de archivos modificados:** 3  
**Tiempo estimado de implementación:** ✅ Completado  

---

## 💳 FASE 2: Integración de Pagos (Prioridad ALTA)
**Objetivo:** Cobrar automáticamente las suscripciones

### 2.1 Elegir Proveedor de Pagos

**Opciones para Latinoamérica:**

| Proveedor | ✅ Pros | ❌ Contras | Recurrencia |
|-----------|---------|------------|-------------|
| **Stripe** | API excelente, bien documentado | Comisiones más altas (3.6% + $0.30) | ✅ Nativa |
| **MercadoPago** | Popular en LATAM, comisiones menores | API menos robusta | ✅ Con plan |
| **PayPal** | Reconocimiento mundial | UX complicada | ✅ Suscripciones |
| **Wompi (Colombia)** | Comisiones bajas local | Solo Colombia | ⚠️ Manual |
| **Kushki** | Multi-país LATAM | Requiere aprobación | ✅ Recurrente |

**Recomendación:** Empezar con **Stripe** (más fácil) + **MercadoPago** (opcional para LATAM)

---

### 2.2 Implementar Stripe

**Instalación:**
```bash
composer require stripe/stripe-php
```

**Migración:**
```php
// database/migrations/xxxx_add_payment_fields_to_subscriptions.php
Schema::table('subscriptions', function (Blueprint $table) {
    $table->string('stripe_subscription_id')->nullable()->after('ends_at');
    $table->string('stripe_customer_id')->nullable();
    $table->string('stripe_payment_method_id')->nullable();
    $table->timestamp('next_billing_date')->nullable();
});

Schema::table('businesses', function (Blueprint $table) {
    $table->string('stripe_customer_id')->nullable();
});
```

**Controlador de pagos:**
```php
// app/Http/Controllers/PaymentController.php
class PaymentController extends Controller
{
    public function showPaymentForm()
    {
        $business = auth()->user()->business;
        $currentPlan = $business->currentPlan();
        
        $intent = auth()->user()->createSetupIntent(); // Laravel Cashier
        
        return view('billing.payment-method', compact('intent', 'currentPlan'));
    }
    
    public function addPaymentMethod(Request $request)
    {
        // Guardar payment_method_id en Stripe
        // Actualizar subscription para cobro automático
    }
}
```

**Vista con Stripe Elements:**
```blade
{{-- resources/views/billing/payment-method.blade.php --}}
<div id="card-element"></div>
<button id="card-button" data-secret="{{ $intent->client_secret }}">
    Guardar Método de Pago
</button>

<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ config("services.stripe.key") }}');
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');
    
    document.getElementById('card-button').addEventListener('click', async (e) => {
        const { setupIntent, error } = await stripe.confirmCardSetup(
            e.target.dataset.secret, {
                payment_method: { card: cardElement }
            }
        );
        
        if (error) {
            alert(error.message);
        } else {
            // Enviar setupIntent.payment_method al servidor
        }
    });
</script>
```

---

### 2.3 Laravel Cashier (Opcional pero recomendado)

**Instalación:**
```bash
composer require laravel/cashier
php artisan vendor:publish --tag="cashier-migrations"
php artisan migrate
```

**Configurar modelos:**
```php
// app/Models/Business.php
use Laravel\Cashier\Billable;

class Business extends Model
{
    use Billable; // Agrega métodos: newSubscription(), charge(), etc
}
```

**Crear suscripción:**
```php
$business->newSubscription('default', 'price_xxxxx') // Stripe Price ID
    ->trialDays(14)
    ->create($paymentMethodId);
```

**Ventajas de Cashier:**
- Maneja webhooks automáticamente
- Sincroniza estados con Stripe
- Métodos helper: `subscribed()`, `onTrial()`, `cancelled()`

---

### 2.4 Webhooks de Stripe

**Ruta:**
```php
// routes/web.php
Route::post('/webhook/stripe', [StripeWebhookController::class, 'handleWebhook'])
    ->name('stripe.webhook');
```

**Controlador:**
```php
// app/Http/Controllers/StripeWebhookController.php
class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');
        
        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }
        
        switch ($event->type) {
            case 'invoice.payment_succeeded':
                $this->handlePaymentSucceeded($event->data->object);
                break;
            case 'invoice.payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;
            case 'customer.subscription.deleted':
                $this->handleSubscriptionCancelled($event->data->object);
                break;
        }
        
        return response()->json(['status' => 'success']);
    }
    
    private function handlePaymentSucceeded($invoice)
    {
        $subscription = Subscription::where('stripe_subscription_id', $invoice->subscription)->first();
        if ($subscription) {
            $subscription->update([
                'status' => 'active',
                'ends_at' => now()->addMonth(),
                'next_billing_date' => now()->addMonth()
            ]);
            
            // Crear registro de pago en tabla 'invoices' (opcional)
        }
    }
}
```

**Configurar en Stripe Dashboard:**
1. Ir a `Developers` → `Webhooks`
2. Agregar endpoint: `https://tudominio.com/webhook/stripe`
3. Seleccionar eventos:
   - `invoice.payment_succeeded`
   - `invoice.payment_failed`
   - `customer.subscription.deleted`
   - `customer.subscription.updated`

---

## 📧 FASE 3: Notificaciones Automáticas (Prioridad MEDIA)
**Objetivo:** Mantener a clientes informados sobre su suscripción

### 3.1 Emails Transaccionales

**Eventos a notificar:**
- ✉️ Bienvenida al registrarse
- ✉️ Trial expira en 3 días
- ✉️ Trial expirado
- ✉️ Pago exitoso (recibo mensual)
- ✉️ Pago fallido (con enlace para actualizar método)
- ✉️ Suscripción cancelada
- ✉️ Límite de plan alcanzado (ej: 500 productos)

**Implementación con Mailables:**
```php
// app/Mail/TrialExpiringMail.php
class TrialExpiringMail extends Mailable
{
    public function __construct(public Business $business) {}
    
    public function content(): Content
    {
        return new Content(view: 'emails.trial-expiring');
    }
}
```

**Vista:**
```blade
{{-- resources/views/emails/trial-expiring.blade.php --}}
<p>Hola {{ $business->name }},</p>
<p>Tu período de prueba expira en <strong>3 días</strong>.</p>
<p>Para continuar usando el sistema, agrega un método de pago:</p>
<a href="{{ route('billing.show') }}" class="btn">Agregar Método de Pago</a>
```

**Comando programado:**
```php
// app/Console/Commands/NotifyExpiringTrials.php
class NotifyExpiringTrials extends Command
{
    public function handle()
    {
        $subscriptions = Subscription::where('status', 'trial')
            ->whereBetween('ends_at', [now()->addDays(3), now()->addDays(3)->endOfDay()])
            ->with('business')
            ->get();
        
        foreach ($subscriptions as $sub) {
            Mail::to($sub->business->email)->send(new TrialExpiringMail($sub->business));
        }
    }
}
```

**Programar en `app/Console/Kernel.php`:**
```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('notify:expiring-trials')->daily();
}
```

---

### 3.2 Notificaciones In-App

**Banner sticky en dashboard:**
```blade
{{-- En layout principal --}}
@if(auth()->user()->business->activeSubscription?->isTrial())
    <div class="alert alert-warning sticky-top">
        <i class="bi bi-clock"></i> 
        Tu período de prueba expira en <strong>{{ auth()->user()->business->activeSubscription->ends_at->diffInDays() }} días</strong>.
        <a href="{{ route('billing.show') }}" class="btn btn-sm btn-dark">Agregar Método de Pago</a>
    </div>
@endif
```

---

## 🏢 FASE 4: Portal de Autogestión (Prioridad MEDIA)
**Objetivo:** Que clientes gestionen su cuenta sin contactar soporte

### 4.1 Sección de Facturación/Billing

**Controlador:**
```php
// app/Http/Controllers/BillingController.php
class BillingController extends Controller
{
    public function show()
    {
        $business = auth()->user()->business;
        $currentSubscription = $business->activeSubscription;
        $invoices = $business->invoices(); // Si usas Cashier
        
        return view('billing.index', compact('currentSubscription', 'invoices'));
    }
    
    public function changePlan(Request $request)
    {
        // Cambiar de plan (upgrade/downgrade)
    }
    
    public function cancelSubscription()
    {
        // Cancelar al final del período actual
    }
    
    public function resumeSubscription()
    {
        // Reactivar suscripción cancelada
    }
}
```

**Vista:**
```blade
{{-- resources/views/billing/index.blade.php --}}
<h2>Mi Suscripción</h2>

<!-- Plan Actual -->
<div class="card">
    <h5>Plan: {{ $currentSubscription->plan->name }}</h5>
    <p>Precio: ${{ $currentSubscription->plan->price }}/mes</p>
    <p>Próximo cobro: {{ $currentSubscription->ends_at->format('d/m/Y') }}</p>
    
    <a href="{{ route('billing.change-plan') }}" class="btn btn-primary">Cambiar Plan</a>
    <button class="btn btn-danger" onclick="cancelSub()">Cancelar Suscripción</button>
</div>

<!-- Métodos de Pago -->
<div class="card mt-3">
    <h5>Método de Pago</h5>
    @if($business->hasDefaultPaymentMethod())
        <p>💳 Tarjeta: •••• {{ $business->card_last_four }}</p>
        <button>Actualizar Tarjeta</button>
    @else
        <p>No hay método de pago configurado</p>
        <a href="{{ route('payment.add') }}">Agregar Tarjeta</a>
    @endif
</div>

<!-- Historial de Facturas -->
<div class="card mt-3">
    <h5>Facturas</h5>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Monto</th>
                <th>Estado</th>
                <th>Descargar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
            <tr>
                <td>{{ $invoice->date()->format('d/m/Y') }}</td>
                <td>${{ $invoice->total() }}</td>
                <td>{{ $invoice->status }}</td>
                <td><a href="{{ $invoice->downloadUrl() }}">PDF</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
```

---

### 4.2 Cambio de Plan (Upgrade/Downgrade)

**Lógica:**
```php
public function changePlan(Request $request)
{
    $business = auth()->user()->business;
    $newPlanId = $request->plan_id;
    $newPlan = Plan::findOrFail($newPlanId);
    $currentSub = $business->activeSubscription;
    
    // Validar límites del nuevo plan
    if ($newPlan->user_limit > 0 && $business->users()->count() > $newPlan->user_limit) {
        return back()->with('error', 'Tienes más usuarios de los permitidos en el nuevo plan');
    }
    
    // Si usa Cashier:
    $business->subscription('default')->swap($newPlan->stripe_price_id);
    
    // O manualmente:
    $currentSub->update(['plan_id' => $newPlan->id]);
    
    // Prorratear si es upgrade inmediato
    
    return back()->with('success', 'Plan actualizado correctamente');
}
```

**Vista de selección de plan:**
```blade
<div class="row">
    @foreach($plans as $plan)
    <div class="col-md-4">
        <div class="card {{ $plan->id === $currentPlan->id ? 'border-primary' : '' }}">
            <h4>{{ $plan->name }}</h4>
            <h2>${{ $plan->price }}<small>/mes</small></h2>
            <ul>
                <li>{{ $plan->user_limit ?: '∞' }} usuarios</li>
                <li>{{ $plan->product_limit ?: '∞' }} productos</li>
                @foreach($plan->features as $feature)
                <li>✓ {{ ucfirst(str_replace('_', ' ', $feature)) }}</li>
                @endforeach
            </ul>
            
            @if($plan->id === $currentPlan->id)
                <button class="btn btn-secondary" disabled>Plan Actual</button>
            @else
                <form method="POST" action="{{ route('billing.change-plan') }}">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                    <button class="btn btn-primary">
                        {{ $plan->price > $currentPlan->price ? 'Mejorar Plan' : 'Cambiar Plan' }}
                    </button>
                </form>
            @endif
        </div>
    </div>
    @endforeach
</div>
```

---

## 🌐 FASE 5: Subdominios (Prioridad BAJA)
**Objetivo:** Cada negocio tiene su propio subdominio (ej: negocio1.tuapp.com)

### 5.1 Configuración DNS

**Registro wildcard en tu dominio:**
```
Type: A
Name: *
Value: IP_DE_TU_SERVIDOR
```

Esto permite que `cualquier-cosa.tuapp.com` apunte a tu servidor.

---

### 5.2 Middleware de Subdominio

```php
// app/Http/Middleware/IdentifyTenant.php
class IdentifyTenant
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0];
        
        // Excepciones (dominio principal, www, admin)
        if (in_array($subdomain, ['www', 'admin', config('app.main_domain')])) {
            return $next($request);
        }
        
        // Buscar negocio por subdominio
        $business = Business::where('subdomain', $subdomain)->first();
        
        if (!$business) {
            abort(404, 'Negocio no encontrado');
        }
        
        // Compartir en toda la app
        app()->instance('current_tenant', $business);
        config(['app.current_tenant' => $business->id]);
        
        return $next($request);
    }
}
```

**Agregar a `app/Http/Kernel.php`:**
```php
protected $middlewareGroups = [
    'web' => [
        // ...
        \App\Http\Middleware\IdentifyTenant::class,
    ],
];
```

---

### 5.3 Migración

```php
Schema::table('businesses', function (Blueprint $table) {
    $table->string('subdomain')->unique()->nullable();
});
```

**Al crear negocio:**
```php
$subdomain = Str::slug($request->business_name);
// Verificar unicidad y guardar
$business->subdomain = $subdomain;
```

---

### 5.4 Global Scope por Tenant

**Automáticamente filtrar queries:**
```php
// app/Models/Scopes/TenantScope.php
class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if ($tenant = app('current_tenant')) {
            $builder->where('business_id', $tenant->id);
        }
    }
}

// En modelos con multitenancy:
class Product extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new TenantScope);
    }
}
```

Ahora `Product::all()` solo devuelve productos del tenant actual.

---

## 📊 FASE 6: Métricas y Analytics (Prioridad BAJA)

### 6.1 Dashboard para Super Admin

**Métricas clave:**
- MRR (Monthly Recurring Revenue)
- Churn rate
- Nuevos registros por mes
- Conversión de trial a pago
- Negocios activos por plan

**Consultas:**
```php
// app/Services/AnalyticsService.php
public function getMRR()
{
    return Subscription::where('status', 'active')
        ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
        ->sum('plans.price');
}

public function getChurnRate()
{
    $cancelled = Subscription::where('status', 'cancelled')
        ->whereMonth('updated_at', now()->month)
        ->count();
    
    $total = Subscription::whereIn('status', ['active', 'cancelled'])
        ->count();
    
    return $total > 0 ? ($cancelled / $total) * 100 : 0;
}
```

**Vista:**
```blade
{{-- resources/views/super-admin/analytics.blade.php --}}
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <h3>MRR</h3>
            <h2>${{ number_format($mrr, 2) }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <h3>Churn Rate</h3>
            <h2>{{ number_format($churnRate, 1) }}%</h2>
        </div>
    </div>
    <!-- Más métricas... -->
</div>

<!-- Gráfico de crecimiento -->
<canvas id="growthChart"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('growthChart'), {
        type: 'line',
        data: {
            labels: @json($months),
            datasets: [{
                label: 'Ingresos Mensuales',
                data: @json($revenue)
            }]
        }
    });
</script>
```

---

## 🔧 FASE 7: Mejoras Adicionales (Opcional)

### 7.1 API Pública

**Para clientes que quieran integraciones:**
```php
// routes/api.php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/products', [ApiProductController::class, 'index']);
    Route::post('/sales', [ApiSaleController::class, 'store']);
    // ...
});
```

**Documentación:** Usar [Scribe](https://scribe.knuckles.wtf/laravel/) o Swagger.

---

### 7.2 Sistema de Referidos

**Tabla:**
```php
Schema::create('referrals', function (Blueprint $table) {
    $table->id();
    $table->foreignId('referrer_id')->constrained('businesses');
    $table->foreignId('referred_id')->constrained('businesses');
    $table->decimal('commission', 8, 2);
    $table->enum('status', ['pending', 'paid']);
    $table->timestamps();
});
```

**Lógica:**
- Cada negocio tiene un código de referido único
- Si un nuevo negocio se registra con ese código, el referidor recibe comisión
- Comisión: % del primer pago o crédito en su cuenta

---

### 7.3 Multi-moneda

**Migración:**
```php
Schema::table('plans', function (Blueprint $table) {
    $table->string('currency', 3)->default('USD');
});

Schema::table('businesses', function (Blueprint $table) {
    $table->string('currency', 3)->default('USD');
});
```

**Precios por moneda:**
- USD: $9.99
- EUR: €8.99
- MXN: $199
- COP: $39,900

---

### 7.4 Planes Anuales con Descuento

**Agregar campo:**
```php
Schema::table('plans', function (Blueprint $table) {
    $table->decimal('annual_price', 8, 2)->nullable();
    $table->string('stripe_annual_price_id')->nullable();
});
```

**Lógica:**
- Si paga anual: 2 meses gratis (precio × 10)
- Mejor LTV (Lifetime Value)

---

## 📅 Cronograma Actualizado

| Fase | Estado | Duración Original | Tiempo Real |
|------|--------|-------------------|-------------|
| **FASE 1: Onboarding** | ✅ 80% Completada | 1-2 semanas | ~8 horas |
| **FASE 2: Pagos (Stripe)** | ⏳ Siguiente | 2-3 semanas | 60h |
| **FASE 3: Notificaciones** | ⏳ Pendiente | 1 semana | 20h |
| **FASE 4: Portal Autogestión** | ⏳ Pendiente | 1-2 semanas | 40h |
| **FASE 5: Subdominios** | ⏳ Pendiente | 1 semana | 20h |
| **FASE 6: Analytics** | ⏳ Pendiente | 1 semana | 20h |
| **FASE 7: Extras** | ⏳ Pendiente | Variable | Variable |

**Progreso actual:** 6-10 semanas → **~4-8 semanas restantes**

**Lo que falta para MVP SaaS funcional:**
1. ⏳ Tour guiado (1-2 días)
2. ⏳ Integración de pagos Stripe (1-2 semanas)
3. ⏳ Email de bienvenida (1 día)
4. ⏳ Notificaciones de trial expirando (2-3 días)

---

## 💰 Costos Operacionales

**Servicios necesarios:**
- **Hosting:** $20-100/mes (VPS o cloud como DigitalOcean, AWS, Heroku)
- **Stripe fees:** 3.6% + $0.30 por transacción
- **Email:** $0-30/mes (SendGrid, Mailgun, SES)
- **Storage:** $5-20/mes (para logos, backups)
- **Dominio:** $10-15/año

**Total mensual estimado:** $30-150/mes + comisiones

---

## 🎯 KPIs para Medir Éxito

1. **Tasa de conversión Trial → Pago:** >30% es bueno
2. **Churn mensual:** <5% es excelente
3. **MRR (Monthly Recurring Revenue):** Debe crecer consistentemente
4. **LTV/CAC ratio:** Lifetime Value / Customer Acquisition Cost >3
5. **Tiempo de onboarding:** <10 minutos ideal

---

## 🚨 Consideraciones Legales

**Antes de lanzar públicamente:**
- [ ] Términos y Condiciones
- [ ] Política de Privacidad (GDPR/CCPA compliance)
- [ ] Política de Cookies
- [ ] Acuerdo de Nivel de Servicio (SLA)
- [ ] Política de Reembolsos
- [ ] Avisos de Propiedad Intelectual

**Herramientas:** [Termly](https://termly.io/), [TermsFeed](https://www.termsfeed.com/)

---

## 📚 Recursos Adicionales

**Librerías útiles:**
- [Laravel Cashier](https://laravel.com/docs/billing) - Stripe/Paddle integration
- [Tenancy for Laravel](https://tenancyforlaravel.com/) - Multitenancy avanzado
- [Laravel Spark](https://spark.laravel.com/) - Boilerplate SaaS (paid)
- [Filament](https://filamentphp.com/) - Admin panels rápidos

**Lecturas recomendadas:**
- "The SaaS Playbook" - Rob Walling
- "Traction" - Gabriel Weinberg
- Blog: [SaaS Metrics 2.0](https://www.forentrepreneurs.com/saas-metrics-2/)

---

## ✅ Checklist Pre-Lanzamiento

**Fase 1 - Registro y Onboarding:**
- [x] Landing page lista
- [x] Registro público funcional
- [x] Creación automática de trial
- [x] Cliente por defecto creado
- [ ] Tour guiado implementado
- [ ] Email de bienvenida funcionando

**Fase 2 - Pagos:**
- [ ] Stripe conectado y probado
- [ ] Webhooks configurados
- [ ] Portal de facturación operativo
- [ ] Cambio de plan funcional

**Fase 3 - Legal y Seguridad:**
- [ ] Términos legales publicados
- [ ] Política de privac80% SaaS! 🚀

**✅ Completado:**
- ✅ Landing page profesional con 3 vistas
- ✅ Sistema de registro público automático
- ✅ Suscripciones trial de 14 días
- ✅ Login automático post-registro
- ✅ Cliente por defecto creado

**⏳ Para tener un SaaS funcional completo:**
1. ⏳ Tour guiado para nuevos usuarios (1-2 días)
2. ⏳ Integrar Stripe para pagos (1-2 semanas)
3. ⏳ Configurar webhooks de Stripe (2-3 días)
4. ⏳ Email de bienvenida (1 día)
5. ⏳ Notificaciones trial expirando (1 día)

**🎯 Próximo paso recomendado:** 
Implementar la integración de pagos con Stripe (FASE 2) para comenzar a monetizar.

---

**Última actualización:** 16 de abril de 2026  
**Estado del proyecto:** 80% completado - Sistema funcional con registro público  
**Siguiente milestone:** Integración de pagos recurrentes
Solo necesitas implementar el **onboarding público** y la **integración de pagos** para tener un SaaS funcional. El resto son mejoras incrementales.

**¿Por dónde empezar?**
1. Crea la landing page con precios
2. Implementa el registro público
3. Integra Stripe para pagos
4. Configura webhooks
5. Lanza en beta con clientes reales

---

**Documentado:** `{{ date('Y-m-d') }}`  
**Autor:** Roadmap generado para "Sistema de Ventas"
