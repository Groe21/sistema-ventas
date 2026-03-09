<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Business;
use App\Models\User;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\Subscription;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin (sin negocio)
        User::firstOrCreate(
            ['email' => 'admin@sistema.com'],
            [
                'name' => 'Super Administrador',
                'password' => Hash::make('admin123'),
                'role' => 'super_admin',
                'is_active' => true,
            ]
        );

        // 2. Negocio de prueba
        $business = Business::firstOrCreate(
            ['ruc' => '1234567890001'],
            [
                'name' => 'Mi Negocio Demo',
                'commercial_name' => 'Negocio Demo',
                'email' => 'demo@negocio.com',
                'phone' => '0991234567',
                'address' => 'Quito, Ecuador',
                'city' => 'Quito',
                'province' => 'Pichincha',
                'status' => 'active',
                'plan' => 'professional',
                'subscription_start' => now(),
                'subscription_end' => now()->addYears(5),
            ]
        );

        // 3. Admin del negocio
        User::firstOrCreate(
            ['email' => 'emilio@negocio.com'],
            [
                'name' => 'Emilio Admin',
                'password' => Hash::make('admin123'),
                'phone' => '0991234567',
                'role' => 'admin',
                'business_id' => $business->id,
                'is_active' => true,
            ]
        );

        // 4. Cliente Demo (acceso al sistema como empleado)
        User::firstOrCreate(
            ['email' => 'cliente@demo.com'],
            [
                'name' => 'Cliente Demo',
                'password' => Hash::make('demo1234'),
                'role' => 'employee',
                'business_id' => $business->id,
                'is_active' => true,
            ]
        );

        // 5. Cliente "Consumidor Final" por defecto
        Customer::firstOrCreate(
            [
                'identification' => '9999999999999',
                'business_id' => $business->id,
            ],
            [
                'name' => 'Consumidor Final',
                'identification_type' => 'consumidor_final',
                'is_active' => true,
            ]
        );

        // 6. Cliente demo de ejemplo
        Customer::firstOrCreate(
            [
                'identification' => '1712345678',
                'business_id' => $business->id,
            ],
            [
                'name' => 'Juan Pérez (Demo)',
                'identification_type' => 'cedula',
                'email' => 'juan@demo.com',
                'phone' => '0998765432',
                'address' => 'Av. Amazonas N23-45',
                'city' => 'Quito',
                'province' => 'Pichincha',
                'is_active' => true,
            ]
        );

        // 7. Planes del sistema
        $starterPlan = Plan::firstOrCreate(
            ['slug' => 'starter'],
            [
                'name' => 'Starter',
                'price' => 9.99,
                'user_limit' => 2,
                'product_limit' => 500,
                'features' => ['pos', 'products', 'inventory', 'customers', 'basic_reports', 'cash_register'],
                'is_active' => true,
            ]
        );

        $businessPlan = Plan::firstOrCreate(
            ['slug' => 'business'],
            [
                'name' => 'Business',
                'price' => 24.99,
                'user_limit' => 5,
                'product_limit' => 5000,
                'features' => ['pos', 'products', 'inventory', 'customers', 'basic_reports', 'cash_register', 'advanced_reports', 'export_excel', 'export_pdf', 'advanced_dashboard', 'low_stock_alerts'],
                'is_active' => true,
            ]
        );

        $premiumPlan = Plan::firstOrCreate(
            ['slug' => 'premium'],
            [
                'name' => 'Premium',
                'price' => 49.99,
                'user_limit' => 0, // unlimited
                'product_limit' => 0, // unlimited
                'features' => ['pos', 'products', 'inventory', 'customers', 'basic_reports', 'cash_register', 'advanced_reports', 'export_excel', 'export_pdf', 'advanced_dashboard', 'low_stock_alerts', 'loyalty_points', 'customer_portal', 'promotions'],
                'is_active' => true,
            ]
        );

        // 8. Suscripción del negocio demo (Premium para demostrar todas las funcionalidades)
        Subscription::firstOrCreate(
            ['business_id' => $business->id, 'plan_id' => $premiumPlan->id],
            [
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addYears(5),
            ]
        );
    }
}
