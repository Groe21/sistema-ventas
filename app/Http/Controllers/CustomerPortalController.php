<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerPoint;
use App\Models\PointTransaction;
use Illuminate\Http\Request;

class CustomerPortalController extends Controller
{
    public function index()
    {
        return view('portal.points');
    }

    public function lookup(Request $request)
    {
        $validated = $request->validate([
            'identification' => 'required|string|max:13',
        ]);

        $identification = $validated['identification'];

        $customer = Customer::where('identification', $identification)
            ->where('is_active', true)
            ->first();

        if (!$customer) {
            return view('portal.points', [
                'searched' => true,
                'identification' => $identification,
                'customer' => null,
            ]);
        }

        $points = CustomerPoint::where('customer_id', $customer->id)
            ->where('business_id', $customer->business_id)
            ->first();

        $recentTransactions = PointTransaction::where('customer_id', $customer->id)
            ->where('business_id', $customer->business_id)
            ->latest()
            ->take(10)
            ->get();

        return view('portal.points', [
            'searched' => true,
            'identification' => $identification,
            'customer' => $customer,
            'points' => $points,
            'transactions' => $recentTransactions,
        ]);
    }
}
