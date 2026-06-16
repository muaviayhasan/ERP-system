<?php

use App\Http\Controllers\Api\V1\System\ActivityLogController;
use App\Http\Controllers\Api\V1\System\CurrencyController;
use App\Http\Controllers\Api\V1\System\IntegrationController;
use App\Http\Controllers\Api\V1\System\LanguageController;
use App\Http\Controllers\Api\V1\System\NoticeController;
use App\Http\Controllers\Api\V1\System\NotificationTemplateController;
use App\Http\Controllers\Api\V1\System\ReportController;
use App\Http\Controllers\Api\V1\System\RoleController;
use App\Http\Controllers\Api\V1\System\SettingController;
use App\Http\Controllers\Api\V1\System\UserController;
use Illuminate\Support\Facades\Route;

/*
| Settings, Communication & System module — registered inside the v1 +
| auth:sanctum group. Route-model binding uses the singular parameter
| (e.g. {setting}, {notice}, {user}).
*/

Route::apiResource('settings', SettingController::class);
Route::apiResource('notices', NoticeController::class);
Route::apiResource('notification-templates', NotificationTemplateController::class);
Route::apiResource('reports', ReportController::class);
Route::apiResource('integrations', IntegrationController::class);
Route::apiResource('languages', LanguageController::class);
Route::apiResource('currencies', CurrencyController::class);
Route::apiResource('activity-logs', ActivityLogController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('roles', RoleController::class);
