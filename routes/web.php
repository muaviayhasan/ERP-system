<?php

use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\CampusController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Middleware\AuditWebActions;
use App\Http\Middleware\EnsureNotInMaintenance;
use App\Http\Middleware\RestrictIpAccess;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

// Returns a fresh CSRF token for the current session. Used right before logout so
// a long-open confirmation modal never submits a stale token (avoids 419 errors).
Route::get('/csrf-token', fn () => response()->json(['token' => csrf_token()]))->name('csrf.token');

/*
|--------------------------------------------------------------------------
| Guest (unauthenticated) routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/forgot-password', [PasswordResetController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Authenticated admin panel
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', RestrictIpAccess::class, EnsureNotInMaintenance::class, AuditWebActions::class])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Academic structure (each action is permission-guarded in the controller).
    Route::resource('campuses', CampusController::class)->except('show');
    Route::post('academic-years/{academicYear}/activate', [AcademicYearController::class, 'activate'])->name('academic-years.activate');
    Route::resource('academic-years', AcademicYearController::class)
        ->except('show')
        ->parameters(['academic-years' => 'academicYear']);

    // User & access management (each action is permission-guarded in the controller).
    Route::resource('users', UserController::class)->except('show');
    Route::resource('roles', RoleController::class)->except('show');

    /*
    | System Settings — one page per module. View/update guarded in the controller.
    */
    Route::prefix('settings')->name('settings.')->controller(SettingsController::class)->group(function () {
        Route::get('/', 'index')->name('home');

        Route::get('institute', 'institute')->name('institute');
        Route::put('institute', 'updateInstitute')->name('institute.update');

        Route::get('general', 'general')->name('general');
        Route::put('general', 'updateGeneral')->name('general.update');

        Route::get('localization', 'localization')->name('localization');
        Route::put('localization', 'updateLocalization')->name('localization.update');

        Route::get('seo', 'seo')->name('seo');
        Route::put('seo', 'updateSeo')->name('seo.update');

        Route::get('academic', 'academic')->name('academic');
        Route::put('academic', 'updateAcademic')->name('academic.update');

        Route::get('financial', 'financial')->name('financial');
        Route::put('financial', 'updateFinancial')->name('financial.update');

        Route::get('notifications', 'notifications')->name('notifications');
        Route::put('notifications', 'updateNotifications')->name('notifications.update');

        Route::get('integrations', 'integrations')->name('integrations');
        Route::put('integrations', 'updateIntegrations')->name('integrations.update');

        Route::get('security', 'security')->name('security');
        Route::put('security', 'updateSecurity')->name('security.update');

        Route::get('user-defaults', 'userDefaults')->name('user-defaults');
        Route::put('user-defaults', 'updateUserDefaults')->name('user-defaults.update');

        // Backup & restore
        Route::get('backup', 'backup')->name('backup');
        Route::post('backup', 'createBackup')->name('backup.create');
        Route::get('backup/{file}/download', 'downloadBackup')->name('backup.download')->where('file', '[A-Za-z0-9._-]+');
        Route::delete('backup/{file}', 'destroyBackup')->name('backup.destroy')->where('file', '[A-Za-z0-9._-]+');
    });
});
