<?php

namespace App\Http\Middleware;

use App\Services\PlanService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanFeature
{
    public function __construct(protected PlanService $planService) {}

    /**
     * Usage: middleware('plan.feature:advanced_reports')
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = $request->user();

        if (!$user || $user->isSuperAdmin()) {
            return $next($request);
        }

        if (!$user->business_id) {
            abort(403, 'Sin acceso a negocio.');
        }

        if (!$this->planService->hasFeature($user->business, $feature)) {
            abort(403, 'Tu plan actual no incluye esta funcionalidad. Contacta al administrador para mejorar tu plan.');
        }

        return $next($request);
    }
}
