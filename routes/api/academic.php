<?php

use App\Http\Controllers\Api\V1\Academic\AcademicSettingController;
use App\Http\Controllers\Api\V1\Academic\AcademicYearController;
use App\Http\Controllers\Api\V1\Academic\BatchController;
use App\Http\Controllers\Api\V1\Academic\CampusController;
use App\Http\Controllers\Api\V1\Academic\CourseController;
use App\Http\Controllers\Api\V1\Academic\DepartmentController;
use App\Http\Controllers\Api\V1\Academic\ProgramController;
use App\Http\Controllers\Api\V1\Academic\SchoolClassController;
use App\Http\Controllers\Api\V1\Academic\SectionController;
use App\Http\Controllers\Api\V1\Academic\SemesterController;
use App\Http\Controllers\Api\V1\Academic\SubjectController;
use Illuminate\Support\Facades\Route;

/*
| Academic Structure module — registered inside the v1 + auth:sanctum group.
| Route-model binding uses the singular parameter (e.g. {campus}).
*/

Route::apiResource('campuses', CampusController::class);
Route::apiResource('departments', DepartmentController::class);
Route::apiResource('programs', ProgramController::class);
Route::apiResource('courses', CourseController::class);
Route::apiResource('subjects', SubjectController::class);
Route::apiResource('classes', SchoolClassController::class)->parameter('classes', 'class');
Route::apiResource('sections', SectionController::class);
Route::apiResource('batches', BatchController::class);
Route::apiResource('semesters', SemesterController::class);
Route::apiResource('academic-years', AcademicYearController::class)->parameter('academic-years', 'academicYear');
Route::apiResource('academic-settings', AcademicSettingController::class)->parameter('academic-settings', 'academicSetting');
