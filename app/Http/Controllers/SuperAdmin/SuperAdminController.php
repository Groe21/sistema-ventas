<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\User;
use App\Models\Sale;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    /**
     * Display super admin dashboard.
     */
    public function dashboard()
    {
        $stats = [
            'total_businesses' => Business::count(),
            'active_businesses' => Business::where('status', 'active')->count(),
            'total_users' => User::whereNotNull('business_id')->count(),
            'total_sales_today' => Sale::whereDate('sale_date', today())
                ->where('status', 'completed')
                ->sum('total'),
        ];

        $recentBusinesses = Business::latest()->take(5)->get();

        return view('super-admin.dashboard', compact('stats', 'recentBusinesses'));
    }

    /**
     * Display businesses list.
     */
    public function businesses()
    {
        $businesses = Business::withCount('users')
            ->latest()
            ->paginate(15);

        return view('super-admin.businesses.index', compact('businesses'));
    }

    /**
     * Store a new business.
     */
    public function storeBusiness(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ruc' => 'required|string|size:13|unique:businesses',
            'email' => 'required|email|unique:businesses',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'plan' => 'required|in:trial,basic,pro,enterprise',
        ]);

        $validated['status'] = 'active';
        $validated['subscription_start'] = now();
        $validated['subscription_end'] = now()->addDays(30); // 30 días de prueba

        Business::create($validated);

        return redirect()->route('super-admin.businesses.index')
            ->with('success', 'Negocio creado exitosamente.');
    }
}
