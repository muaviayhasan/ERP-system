<?php

use App\Http\Controllers\Api\V1\Attendance\AssignmentController;
use App\Http\Controllers\Api\V1\Attendance\AttendanceController;
use App\Http\Controllers\Api\V1\Attendance\HomeworkController;
use App\Http\Controllers\Api\V1\Attendance\LowAttendanceAlertController;
use App\Http\Controllers\Api\V1\Attendance\StudyMaterialController;
use App\Http\Controllers\Api\V1\Attendance\TimetableController;
use Illuminate\Support\Facades\Route;

/*
| Attendance & Academic Delivery module — registered inside the v1 + auth:sanctum group.
| Route-model binding uses the singular parameter (e.g. {attendance}).
*/

Route::apiResource('attendances', AttendanceController::class);
Route::apiResource('low-attendance-alerts', LowAttendanceAlertController::class)
    ->parameters(['low-attendance-alerts' => 'lowAttendanceAlert']);
Route::apiResource('assignments', AssignmentController::class);
Route::apiResource('homeworks', HomeworkController::class);
Route::apiResource('study-materials', StudyMaterialController::class)
    ->parameters(['study-materials' => 'studyMaterial']);
Route::apiResource('timetables', TimetableController::class);
