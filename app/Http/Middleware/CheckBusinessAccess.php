<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\PlanService;

class CheckBusinessAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Super admin can access everything
        if ($user && $user->isSuperAdmin()) {
            return $next($request);
        }

        // Check if user belongs to a business
        if (!$user || !$user->business_id) {
            abort(403, 'No tiene acceso a ningún negocio.');
        }

        // Check if business is active
        if (!$user->business->isActive()) {
            abort(403, 'El negocio está inactivo.');
        }

        // Check if subscription is valid
        if (!$user->business->hasActiveSubscription()) {
            abort(403, 'La suscripción del negocio ha expirado. Contacte al administrador.');
        }

        // Share business data with all views
        view()->share('currentBusiness', $user->business);

        // Share plan info with all views (defensive - don't crash if plan tables don't exist)
        try {
            $planService = app(PlanService::class);
            $currentPlan = $planService->getPlan($user->business);
            view()->share('currentPlan', $currentPlan);
            view()->share('planFeatures', $currentPlan?->features ?? []);
        } catch (\Exception $e) {
            view()->share('currentPlan', null);
            view()->share('planFeatures', []);
        }

        return $next($request);
    }
}
