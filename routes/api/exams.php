<?php

use App\Http\Controllers\Api\V1\Exams\ExamController;
use App\Http\Controllers\Api\V1\Exams\ExamResultController;
use App\Http\Controllers\Api\V1\Exams\ExamScheduleController;
use App\Http\Controllers\Api\V1\Exams\GradeScaleController;
use App\Http\Controllers\Api\V1\Exams\ResultCardController;
use App\Http\Controllers\Api\V1\Exams\StudentGpaController;
use Illuminate\Support\Facades\Route;

/*
| Exams & Results module — registered inside the v1 + auth:sanctum group.
| Route-model binding uses the singular parameter (e.g. {exam}, {exam_schedule}).
*/

Route::apiResource('exams', ExamController::class);
Route::apiResource('exam-schedules', ExamScheduleController::class);
Route::apiResource('exam-results', ExamResultController::class);
Route::apiResource('grade-scales', GradeScaleController::class);
Route::apiResource('student-gpas', StudentGpaController::class);
Route::apiResource('result-cards', ResultCardController::class);
