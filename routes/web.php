<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\SuperAdmin\PlanController;
use App\Http\Controllers\SuperAdmin\SubscriptionController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\CustomerPortalController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirect root to login or dashboard
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Customer Points Portal (public)
Route::get('/customer-points', [CustomerPortalController::class, 'index'])->name('customer-points');
Route::post('/customer-points', [CustomerPortalController::class, 'lookup'])->name('customer-points.lookup');

// Temporary diagnostic route (remove after fixing)
Route::get('/debug-plans', function () {
    $results = [];
    
    // Test DB connection
    try {
        \DB::connection()->getPdo();
        $results['db'] = 'OK';
    } catch (\Exception $e) {
        $results['db'] = 'FAIL: ' . $e->getMessage();
    }
    
    // Test each table
    foreach (['businesses', 'users', 'plans', 'subscriptions', 'customer_points', 'point_transactions'] as $table) {
        try {
            $count = \DB::table($table)->count();
            $results["table_{$table}"] = "OK ({$count} rows)";
        } catch (\Exception $e) {
            $results["table_{$table}"] = 'FAIL: ' . $e->getMessage();
        }
    }
    
    // Test view rendering
    try {
        $businesses = \App\Models\Business::withCount('users')->latest()->paginate(15);
        $plans = \App\Models\Plan::where('is_active', true)->orderBy('price')->get();
        $html = view('super-admin.businesses.index', compact('businesses', 'plans'))->render();
        $results['view_businesses'] = 'OK (rendered ' . strlen($html) . ' bytes)';
    } catch (\Exception $e) {
        $results['view_businesses'] = 'FAIL: ' . $e->getMessage() . ' at ' . basename($e->getFile()) . ':' . $e->getLine();
    }
    
    try {
        $plans = \App\Models\Plan::withCount('subscriptions')->get();
        $html = view('super-admin.plans.index', compact('plans'))->render();
        $results['view_plans'] = 'OK (rendered ' . strlen($html) . ' bytes)';
    } catch (\Exception $e) {
        $results['view_plans'] = 'FAIL: ' . $e->getMessage() . ' at ' . basename($e->getFile()) . ':' . $e->getLine();
    }

    // Check Laravel log for recent errors
    try {
        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            $results['last_log_lines'] = implode("\n", array_slice(file($logFile), -20));
        } else {
            $results['last_log_lines'] = 'No log file';
        }
    } catch (\Exception $e) {
        $results['last_log_lines'] = 'Cannot read log';
    }
    
    return response()->json($results, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
});

/*
|--------------------------------------------------------------------------
| Super Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'super-admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    
    // Businesses Management
    Route::get('/businesses', [SuperAdminController::class, 'businesses'])->name('businesses.index');
    Route::post('/businesses', [SuperAdminController::class, 'storeBusiness'])->name('businesses.store');
    
    // Users Management
    Route::get('/users', [SuperAdminController::class, 'users'])->name('users.index');
    Route::post('/users', [SuperAdminController::class, 'storeUser'])->name('users.store');
    Route::put('/users/{user}', [SuperAdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [SuperAdminController::class, 'destroyUser'])->name('users.destroy');
    
    // Subscriptions Management
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::post('/subscriptions/{subscription}/activate', [SubscriptionController::class, 'activate'])->name('subscriptions.activate');
    Route::post('/subscriptions/{subscription}/deactivate', [SubscriptionController::class, 'deactivate'])->name('subscriptions.deactivate');
    Route::post('/subscriptions/{subscription}/renew', [SubscriptionController::class, 'renew'])->name('subscriptions.renew');

    // Plans Management
    Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
    Route::post('/plans', [PlanController::class, 'store'])->name('plans.store');
    Route::put('/plans/{plan}', [PlanController::class, 'update'])->name('plans.update');
    Route::delete('/plans/{plan}', [PlanController::class, 'destroy'])->name('plans.destroy');
    
    // Reports (placeholder)
    Route::get('/reports', function () {
        return view('super-admin.reports.index');
    })->name('reports.index');
    
    // Settings (placeholder)
    Route::get('/settings', function () {
        return view('super-admin.settings.index');
    })->name('settings.index');
});

/*
|--------------------------------------------------------------------------
| Business Admin & Employee Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'business'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Products Management
    Route::resource('products', ProductController::class)->except(['show', 'create', 'edit']);
    
    // Customers Management
    Route::resource('customers', CustomerController::class)->except(['show', 'create', 'edit']);
    
    // Point of Sale
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos', [POSController::class, 'store'])->name('pos.store');
    
    // Cash Register
    Route::get('/cash', [CashController::class, 'index'])->name('cash.index');
    Route::post('/cash/open', [CashController::class, 'open'])->name('cash.open');
    Route::post('/cash/{cashRegister}/close', [CashController::class, 'close'])->name('cash.close');
    Route::post('/cash/movement', [CashController::class, 'movement'])->name('cash.movement');
    
    // Sales
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    
    // Admin Only Routes
    Route::middleware('admin')->group(function () {
        // Users Management
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // Loyalty / Points Management
        Route::get('/loyalty', [LoyaltyController::class, 'index'])->name('loyalty.index');
        Route::get('/loyalty/{customer}/history', [LoyaltyController::class, 'history'])->name('loyalty.history');
        Route::post('/loyalty/{customer}/adjust', [LoyaltyController::class, 'adjustPoints'])->name('loyalty.adjust');
        
        // Reports (placeholder)
        Route::get('/reports', function () {
            return view('admin.reports.index');
        })->name('reports.index');
        
        // Settings (placeholder)
        Route::get('/settings', function () {
            return view('admin.settings.index');
        })->name('settings.index');
    });
});
