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
    
    // Subscriptions (placeholder)
    Route::get('/subscriptions', function () {
        return view('super-admin.subscriptions.index');
    })->name('subscriptions.index');
    
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
