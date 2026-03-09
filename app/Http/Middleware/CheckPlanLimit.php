<?php

namespace App\Http\Middleware;

use App\Services\PlanService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanLimit
{
    public function __construct(protected PlanService $planService) {}

    /**
     * Usage: middleware('plan.limit:users') or middleware('plan.limit:products')
     */
    public function handle(Request $request, Closure $next, string $resource): Response
    {
        $user = $request->user();

        if (!$user || $user->isSuperAdmin()) {
            return $next($request);
        }

        if (!$user->business_id) {
            abort(403, 'Sin acceso a negocio.');
        }

        $business = $user->business;

        $exceeded = match ($resource) {
            'users' => !$this->planService->canAddUser($business),
            'products' => !$this->planService->canAddProduct($business),
            default => false,
        };

        if ($exceeded) {
            $limits = $this->planService->getLimitsInfo($business);
            $info = $limits[$resource] ?? null;
            $limitText = $info ? "{$info['current']}/{$info['limit']}" : '';

            return redirect()->back()->with('error',
                "Has alcanzado el límite de {$resource} de tu plan ({$limitText}). Mejora tu plan para agregar más."
            );
        }

        return $next($request);
    }
}
