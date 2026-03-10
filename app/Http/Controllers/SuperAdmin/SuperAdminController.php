<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\User;
use App\Models\Sale;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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

        try {
            $plans = Plan::where('is_active', true)->orderBy('price')->get();
        } catch (\Exception $e) {
            $plans = collect();
        }

        return view('super-admin.businesses.index', compact('businesses', 'plans'));
    }

    /**
     * Store a new business.
     */
    public function storeBusiness(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'ruc' => 'required|string|size:13|unique:businesses',
            'email' => 'required|email|unique:businesses',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'plan_id' => 'required|exists:plans,id',
            'status' => 'nullable|in:active,inactive,suspended',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:6|confirmed',
        ], [
            'ruc.unique' => 'Ya existe un negocio con ese RUC.',
            'ruc.size' => 'El RUC debe tener exactamente 13 dígitos.',
            'email.unique' => 'Ya existe un negocio con ese email.',
            'admin_email.unique' => 'Ya existe un usuario con ese email.',
            'admin_password.confirmed' => 'Las contraseñas no coinciden.',
            'admin_password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'plan_id.required' => 'Debe seleccionar un plan.',
            'plan_id.exists' => 'El plan seleccionado no es válido.',
        ]);

        $plan = Plan::findOrFail($request->plan_id);

        $business = Business::create([
            'name' => $request->name,
            'ruc' => $request->ruc,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'plan' => $plan->slug,
            'status' => $request->status ?? 'active',
            'subscription_start' => now(),
            'subscription_end' => now()->addDays(30),
        ]);

        // Crear suscripción
        Subscription::create([
            'business_id' => $business->id,
            'plan_id' => $plan->id,
            'status' => 'trial',
            'trial_ends_at' => now()->addDays(30),
            'starts_at' => now(),
            'ends_at' => now()->addDays(30),
        ]);

        // Crear usuario administrador del negocio
        User::create([
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'password' => Hash::make($request->admin_password),
            'role' => 'admin',
            'business_id' => $business->id,
            'is_active' => true,
        ]);

        return redirect()->route('super-admin.businesses.index')
            ->with('success', 'Negocio, suscripción y administrador creados exitosamente.');
    }

    /**
     * Display all users across all businesses.
     */
    public function users(Request $request)
    {
        $query = User::with('business');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('email', 'ilike', "%{$search}%")
                  ->orWhere('phone', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('business_id')) {
            $query->where('business_id', $request->business_id);
        }

        $users = $query->orderBy('name')->paginate(15);

        $stats = [
            'total' => User::count(),
            'super_admins' => User::where('role', 'super_admin')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'employees' => User::where('role', 'employee')->count(),
            'active' => User::where('is_active', true)->count(),
        ];

        $businesses = Business::orderBy('name')->get();

        return view('super-admin.users.index', compact('users', 'stats', 'businesses'));
    }

    /**
     * Store a new user (super admin context).
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'identification' => 'nullable|string|max:13',
            'address' => 'nullable|string|max:500',
            'role' => ['required', Rule::in(['super_admin', 'admin', 'employee'])],
            'business_id' => 'nullable|exists:businesses,id',
        ]);

        $businessId = $request->role === 'super_admin' ? null : $request->business_id;

        User::create([
            'business_id' => $businessId,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'identification' => $request->identification,
            'address' => $request->address,
            'role' => $request->role,
            'is_active' => true,
        ]);

        return redirect()->route('super-admin.users.index')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Update a user (super admin context).
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'identification' => 'nullable|string|max:13',
            'address' => 'nullable|string|max:500',
            'role' => ['required', Rule::in(['super_admin', 'admin', 'employee'])],
            'business_id' => 'nullable|exists:businesses,id',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'identification', 'address', 'role']);
        $data['is_active'] = $request->boolean('is_active');
        $data['business_id'] = $request->role === 'super_admin' ? null : $request->business_id;

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:6']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('super-admin.users.index')->with('success', 'Usuario actualizado.');
    }

    /**
     * Delete a user (super admin context).
     */
    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('super-admin.users.index')->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();

        return redirect()->route('super-admin.users.index')->with('success', 'Usuario eliminado.');
    }
}
