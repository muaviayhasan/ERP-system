<?php

use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (v1)
|--------------------------------------------------------------------------
|
| All API routes are versioned under the "v1" prefix. Public auth endpoints
| live here; every feature module registers its own routes file under
| routes/api/ and is auto-loaded inside the authenticated group below.
|
*/

Route::prefix('v1')->group(function () {

    // Public authentication endpoints (rate-limited to deter brute force).
    Route::prefix('auth')->middleware('throttle:10,1')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });

    // Authenticated endpoints (Sanctum token required).
    Route::middleware(['auth:sanctum', 'throttle:120,1'])->group(function () {

        Route::prefix('auth')->group(function () {
            Route::get('/me', [AuthController::class, 'me']);
            Route::post('/logout', [AuthController::class, 'logout']);
        });

        // Feature modules: every request is authorized against a
        // {resource}.{action} permission (fail-closed) and audit-logged.
        Route::middleware(['api.permission', 'api.audit'])->group(function () {
            foreach (glob(__DIR__.'/api/*.php') as $moduleRoutes) {
                require $moduleRoutes;
            }
        });
    });
});
