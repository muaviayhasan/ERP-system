<?php

use App\Http\Controllers\Api\V1\Hr\PayrollRuleController;
use App\Http\Controllers\Api\V1\Hr\SalaryPaymentController;
use App\Http\Controllers\Api\V1\Hr\SalaryStructureController;
use App\Http\Controllers\Api\V1\Hr\StaffAttendanceController;
use App\Http\Controllers\Api\V1\Hr\StaffController;
use App\Http\Controllers\Api\V1\Hr\TeacherAssignmentController;
use App\Http\Controllers\Api\V1\Hr\TeacherController;
use Illuminate\Support\Facades\Route;

/*
| Teachers / Staff / HR module — registered inside the v1 + auth:sanctum group.
| Route-model binding uses the singular parameter (e.g. {teacher}).
*/

Route::apiResource('teachers', TeacherController::class);
Route::apiResource('staff', StaffController::class)->parameter('staff', 'staff');
Route::apiResource('teacher-assignments', TeacherAssignmentController::class);
Route::apiResource('staff-attendances', StaffAttendanceController::class);
Route::apiResource('salary-structures', SalaryStructureController::class);
Route::apiResource('salary-payments', SalaryPaymentController::class);
Route::apiResource('payroll-rules', PayrollRuleController::class);
