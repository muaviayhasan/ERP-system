<?php

use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (v1)
|--------------------------------------------------------------------------
|
| All API routes are versioned under the "v1" prefix. Future ERP modules
| should register their own route groups here (or via the Modules folder)
| inside the authenticated group below. Keep this file clean — no business
| logic, only route definitions.
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

        /*
        |----------------------------------------------------------------------
        | Module route groups (empty placeholders)
        |----------------------------------------------------------------------
        | Register feature/module routes below as the ERP grows.
        */

        // Route::prefix('students')->group(base_path('routes/modules/students.php'));
        // Route::prefix('staff')->group(base_path('routes/modules/staff.php'));
        // Route::prefix('academics')->group(base_path('routes/modules/academics.php'));

    });
});
