<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Show the business registration form.
     */
    public function showRegistrationForm(Request $request)
    {
        // Get all active plans
        $plans = Plan::where('is_active', true)->orderBy('price')->get();
        
        // Get selected plan from URL parameter
        $selectedPlan = $request->query('plan');
        
        return view('auth.register-business', compact('plans', 'selectedPlan'));
    }
    
    /**
     * Handle business registration.
     */
    public function register(Request $request)
    {
        // Validate all input
        $validated = $request->validate([
            // Business data
            'business_name' => 'required|string|max:255',
            'business_ruc' => 'required|string|max:13|unique:businesses,ruc',
            'business_email' => 'required|email|max:255',
            'business_phone' => 'required|string|max:20',
            'business_address' => 'required|string|max:500',
            'business_city' => 'required|string|max:100',
            'business_province' => 'nullable|string|max:100',
            
            // Admin user data
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            
            // Plan selection
            'plan_id' => 'required|exists:plans,id',
            
            // Terms acceptance
            'terms' => 'accepted',
        ], [
            'business_ruc.unique' => 'Este RUC ya está registrado en el sistema.',
            'admin_email.unique' => 'Este email ya está en uso.',
            'terms.accepted' => 'Debes aceptar los términos y condiciones.',
        ]);
        
        try {
            DB::beginTransaction();
            
            // 1. Create the business
            $business = Business::create([
                'name' => $validated['business_name'],
                'ruc' => $validated['business_ruc'],
                'email' => $validated['business_email'],
                'phone' => $validated['business_phone'],
                'address' => $validated['business_address'],
                'city' => $validated['business_city'],
                'province' => $validated['business_province'] ?? $validated['business_city'],
                'status' => 'active',
                'onboarding_completed' => false,
            ]);
            
            // 2. Get the selected plan
            $plan = Plan::findOrFail($validated['plan_id']);
            
            // 3. Create subscription in trial status (14 days)
            $trialDays = 14;
            $subscription = Subscription::create([
                'business_id' => $business->id,
                'plan_id' => $plan->id,
                'status' => 'trial',
                'trial_ends_at' => now()->addDays($trialDays),
                'starts_at' => now(),
                'ends_at' => now()->addDays($trialDays),
            ]);
            
            // 4. Create admin user
            $user = User::create([
                'business_id' => $business->id,
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'password' => Hash::make($validated['password']),
                'role' => 'admin',
            ]);
            
            // 5. Create default "Consumidor Final" customer
            Customer::create([
                'business_id' => $business->id,
                'name' => 'Consumidor Final',
                'identification' => '9999999999999',
                'email' => 'consumidorfinal@' . str_replace(' ', '', strtolower($business->name)) . '.com',
                'phone' => 'N/A',
                'address' => 'N/A',
                'city' => $business->city,
            ]);
            
            DB::commit();
            
            // 6. Send welcome email
            try {
                Mail::to($user)->send(new WelcomeMail($user));
            } catch (\Exception $e) {
                // Log email error but don't fail the registration
                \Log::warning('Welcome email failed to send', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
            
            // 7. Log in the new user
            Auth::login($user);
            
            // 8. Redirect to dashboard with welcome message and show tour
            return redirect()->route('dashboard')
                ->with('success', 
                    "¡Bienvenido a VentasPro! Tu cuenta ha sido creada exitosamente. " .
                    "Tienes {$trialDays} días de prueba gratis para explorar todas las funcionalidades."
                )
                ->with('show_onboarding_tour', true);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log error
            \Log::error('Business registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return back()->withInput()->withErrors([
                'registration' => 'Ocurrió un error al crear tu cuenta. Por favor intenta nuevamente.'
            ]);
        }
    }
}
