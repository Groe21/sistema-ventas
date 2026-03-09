<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerPoint;
use App\Models\PointTransaction;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{
    public function index(Request $request)
    {
        $businessId = auth()->user()->business_id;

        $query = CustomerPoint::where('business_id', $businessId)
            ->with('customer')
            ->where('points_balance', '>', 0);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('identification', 'ilike', "%{$search}%");
            });
        }

        $customerPoints = $query->orderByDesc('points_balance')->paginate(15);

        $stats = [
            'total_customers_with_points' => CustomerPoint::where('business_id', $businessId)->where('points_balance', '>', 0)->count(),
            'total_points_issued' => PointTransaction::where('business_id', $businessId)->sum('points_earned'),
            'total_points_redeemed' => PointTransaction::where('business_id', $businessId)->sum('points_used'),
            'total_points_balance' => CustomerPoint::where('business_id', $businessId)->sum('points_balance'),
        ];

        return view('admin.loyalty.index', compact('customerPoints', 'stats'));
    }

    public function history(Customer $customer)
    {
        $businessId = auth()->user()->business_id;

        if ($customer->business_id !== $businessId) {
            abort(403);
        }

        $transactions = PointTransaction::where('business_id', $businessId)
            ->where('customer_id', $customer->id)
            ->with('sale')
            ->latest()
            ->paginate(20);

        $points = $customer->points;

        return view('admin.loyalty.history', compact('customer', 'transactions', 'points'));
    }

    public function adjustPoints(Request $request, Customer $customer)
    {
        $businessId = auth()->user()->business_id;

        if ($customer->business_id !== $businessId) {
            abort(403);
        }

        $validated = $request->validate([
            'type' => 'required|in:add,subtract',
            'points' => 'required|integer|min:1',
            'description' => 'required|string|max:255',
        ]);

        $pointRecord = CustomerPoint::firstOrCreate(
            ['business_id' => $businessId, 'customer_id' => $customer->id],
            ['points_balance' => 0]
        );

        if ($validated['type'] === 'subtract' && $pointRecord->points_balance < $validated['points']) {
            return redirect()->back()->with('error', 'El cliente no tiene suficientes puntos.');
        }

        $earned = $validated['type'] === 'add' ? $validated['points'] : 0;
        $used = $validated['type'] === 'subtract' ? $validated['points'] : 0;

        PointTransaction::create([
            'business_id' => $businessId,
            'customer_id' => $customer->id,
            'points_earned' => $earned,
            'points_used' => $used,
            'description' => $validated['description'],
        ]);

        if ($validated['type'] === 'add') {
            $pointRecord->increment('points_balance', $validated['points']);
        } else {
            $pointRecord->decrement('points_balance', $validated['points']);
        }

        return redirect()->back()->with('success', 'Puntos ajustados correctamente.');
    }
}
