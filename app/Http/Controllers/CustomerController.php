<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(Request $request)
    {
        $query = Customer::where('business_id', auth()->user()->business_id);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('email', 'ilike', "%{$search}%")
                  ->orWhere('identification', 'ilike', "%{$search}%")
                  ->orWhere('phone', 'ilike', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(15);

        $stats = [
            'total' => Customer::where('business_id', auth()->user()->business_id)->count(),
            'activos' => Customer::where('business_id', auth()->user()->business_id)->where('is_active', true)->count(),
            'con_email' => Customer::where('business_id', auth()->user()->business_id)->whereNotNull('email')->where('email', '!=', '')->count(),
        ];

        return view('admin.customers.index', compact('customers', 'stats'));
    }

    /**
     * Store a newly created customer.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'identification_type' => 'required|in:cedula,ruc,pasaporte,consumidor_final',
            'identification' => 'nullable|string|max:13',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'province' => 'nullable|string',
            'credit_limit' => 'nullable|numeric|min:0',
            'credit_days' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ];

        // Identificación obligatoria si no es consumidor final
        if ($request->identification_type !== 'consumidor_final') {
            $rules['identification'] = 'required|string|max:13';
        }

        $validated = $request->validate($rules);

        $validated['business_id'] = auth()->user()->business_id;

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Cliente creado exitosamente.');
    }

    /**
     * Update the specified customer.
     */
    public function update(Request $request, Customer $customer)
    {
        // Check business access
        if ($customer->business_id !== auth()->user()->business_id) {
            abort(403);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'identification_type' => 'required|in:cedula,ruc,pasaporte,consumidor_final',
            'identification' => 'nullable|string|max:13',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'province' => 'nullable|string',
            'credit_limit' => 'nullable|numeric|min:0',
            'credit_days' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ];

        if ($request->identification_type !== 'consumidor_final') {
            $rules['identification'] = 'required|string|max:13';
        }

        $validated = $request->validate($rules);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Cliente actualizado exitosamente.');
    }

    /**
     * Remove the specified customer.
     */
    public function destroy(Customer $customer)
    {
        // Check business access
        if ($customer->business_id !== auth()->user()->business_id) {
            abort(403);
        }

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Cliente eliminado exitosamente.');
    }
}
