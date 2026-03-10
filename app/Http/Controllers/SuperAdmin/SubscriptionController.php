<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Business;
use App\Models\Plan;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Subscription::with(['business', 'plan']);

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('plan_id')) {
                $query->where('plan_id', $request->plan_id);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('business', function ($q) use ($search) {
                    $q->where('name', 'ilike', "%{$search}%")
                      ->orWhere('ruc', 'ilike', "%{$search}%");
                });
            }

            $subscriptions = $query->latest()->paginate(15);

            $stats = [
                'total' => Subscription::count(),
                'active' => Subscription::where('status', 'active')->where('ends_at', '>=', now())->count(),
                'trial' => Subscription::where('status', 'trial')->where('ends_at', '>=', now())->count(),
                'expired' => Subscription::where('ends_at', '<', now())->count(),
            ];

            $plans = Plan::where('is_active', true)->orderBy('price')->get();
            $businesses = Business::orderBy('name')->get();
        } catch (\Exception $e) {
            $subscriptions = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
            $stats = ['total' => 0, 'active' => 0, 'trial' => 0, 'expired' => 0];
            $plans = collect();
            $businesses = Business::orderBy('name')->get();
        }

        return view('super-admin.subscriptions.index', compact('subscriptions', 'stats', 'plans', 'businesses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'plan_id' => 'required|exists:plans,id',
            'status' => 'required|in:active,trial',
            'duration_days' => 'required|integer|min:1|max:730',
        ]);

        // Deactivate existing active subscriptions for this business
        Subscription::where('business_id', $validated['business_id'])
            ->whereIn('status', ['active', 'trial'])
            ->update(['status' => 'expired']);

        $startsAt = now();
        $endsAt = now()->addDays($validated['duration_days']);

        Subscription::create([
            'business_id' => $validated['business_id'],
            'plan_id' => $validated['plan_id'],
            'status' => $validated['status'],
            'trial_ends_at' => $validated['status'] === 'trial' ? $endsAt : null,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
        ]);

        // Also update the legacy business fields
        $business = Business::find($validated['business_id']);
        $plan = Plan::find($validated['plan_id']);
        $business->update([
            'plan' => $plan->slug,
            'subscription_start' => $startsAt,
            'subscription_end' => $endsAt,
        ]);

        return redirect()->route('super-admin.subscriptions.index')
            ->with('success', 'Suscripción asignada exitosamente.');
    }

    public function activate(Subscription $subscription)
    {
        $subscription->update(['status' => 'active']);

        return redirect()->route('super-admin.subscriptions.index')
            ->with('success', 'Suscripción activada.');
    }

    public function deactivate(Subscription $subscription)
    {
        $subscription->update(['status' => 'expired']);

        return redirect()->route('super-admin.subscriptions.index')
            ->with('success', 'Suscripción desactivada.');
    }

    public function renew(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'duration_days' => 'required|integer|min:1|max:730',
        ]);

        $newEnd = now()->addDays($validated['duration_days']);

        $subscription->update([
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => $newEnd,
        ]);

        // Sync legacy fields
        $subscription->business->update([
            'subscription_start' => now(),
            'subscription_end' => $newEnd,
        ]);

        return redirect()->route('super-admin.subscriptions.index')
            ->with('success', 'Suscripción renovada.');
    }
}
