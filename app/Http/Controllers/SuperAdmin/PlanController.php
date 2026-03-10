<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Business;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        try {
            $plans = Plan::withCount('subscriptions')->get();
        } catch (\Exception $e) {
            $plans = collect();
        }

        return view('super-admin.plans.index', compact('plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:50|unique:plans,slug',
            'price' => 'required|numeric|min:0',
            'product_limit' => 'required|integer|min:0',
            'user_limit' => 'required|integer|min:0',
            'features' => 'nullable|array',
            'features.*' => 'string',
        ]);

        $validated['is_active'] = true;

        Plan::create($validated);

        return redirect()->route('super-admin.plans.index')
            ->with('success', 'Plan creado exitosamente.');
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'product_limit' => 'required|integer|min:0',
            'user_limit' => 'required|integer|min:0',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $plan->update($validated);

        return redirect()->route('super-admin.plans.index')
            ->with('success', 'Plan actualizado.');
    }

    public function destroy(Plan $plan)
    {
        if ($plan->subscriptions()->whereIn('status', ['active', 'trial'])->exists()) {
            return redirect()->route('super-admin.plans.index')
                ->with('error', 'No se puede eliminar un plan con suscripciones activas.');
        }

        $plan->delete();

        return redirect()->route('super-admin.plans.index')
            ->with('success', 'Plan eliminado.');
    }
}
