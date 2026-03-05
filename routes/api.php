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

// Rutas API futuras (ejemplo):
// Route::middleware('auth:sanctum')->group(function () {
//     Route::apiResource('products', ProductApiController::class);
//     Route::apiResource('customers', CustomerApiController::class);
//     Route::post('sales', [SaleApiController::class, 'store']);
// });
