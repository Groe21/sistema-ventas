<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Estas rutas están preparadas para futuras implementaciones de API REST.
| Por ahora, el sistema utiliza únicamente rutas web tradicionales.
|
| Para habilitar API en el futuro:
| 1. Instalar Laravel Sanctum: composer require laravel/sanctum
| 2. Configurar autenticación API
| 3. Implementar controladores API
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Onboarding completion endpoint (uses web session authentication)
Route::middleware(['web', 'auth'])->post('/complete-onboarding', function (Request $request) {
    $business = auth()->user()->business;
    
    if (!$business) {
        return response()->json(['success' => false, 'message' => 'Business not found'], 404);
    }
    
    $business->update(['onboarding_completed' => true]);
    
    return response()->json([
        'success' => true,
        'message' => 'Onboarding marcado como completado'
    ]);
});

// Rutas API futuras (ejemplo):
// Route::middleware('auth:sanctum')->group(function () {
//     Route::apiResource('products', ProductApiController::class);
//     Route::apiResource('customers', CustomerApiController::class);
//     Route::post('sales', [SaleApiController::class, 'store']);
// });
