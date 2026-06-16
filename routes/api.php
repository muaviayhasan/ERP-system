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

    // Public authentication endpoints.
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });

    // Authenticated endpoints (Sanctum token required).
    Route::middleware('auth:sanctum')->group(function () {

        Route::prefix('auth')->group(function () {
            Route::get('/me', [AuthController::class, 'me']);
            Route::post('/logout', [AuthController::class, 'logout']);
        });

        // Auto-load every module route file in routes/api/*.php.
        foreach (glob(__DIR__.'/api/*.php') as $moduleRoutes) {
            require $moduleRoutes;
        }
    });
});
