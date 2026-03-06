<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $businessId = auth()->user()->business_id;

        $query = User::where('business_id', $businessId);

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

        $users = $query->orderBy('name')->paginate(15);

        $stats = [
            'total' => User::where('business_id', $businessId)->count(),
            'admins' => User::where('business_id', $businessId)->where('role', 'admin')->count(),
            'employees' => User::where('business_id', $businessId)->where('role', 'employee')->count(),
            'active' => User::where('business_id', $businessId)->where('is_active', true)->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'identification' => 'nullable|string|max:13',
            'address' => 'nullable|string|max:500',
            'role' => ['required', Rule::in(['admin', 'employee'])],
        ]);

        User::create([
            'business_id' => auth()->user()->business_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'identification' => $request->identification,
            'address' => $request->address,
            'role' => $request->role,
            'is_active' => true,
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function update(Request $request, User $user)
    {
        if ($user->business_id !== auth()->user()->business_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'identification' => 'nullable|string|max:13',
            'address' => 'nullable|string|max:500',
            'role' => ['required', Rule::in(['admin', 'employee'])],
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'identification', 'address', 'role']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:6']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado.');
    }

    public function destroy(User $user)
    {
        if ($user->business_id !== auth()->user()->business_id) {
            abort(403);
        }

        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'No puede eliminarse a sí mismo.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado.');
    }
}
