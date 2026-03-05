<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configurar paginación con Bootstrap
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        // Directivas Blade personalizadas (opcional)
        Blade::if('superadmin', function () {
            return auth()->check() && auth()->user()->isSuperAdmin();
        });

        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->canManageBusiness();
        });
    }
}
