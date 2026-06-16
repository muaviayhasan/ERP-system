<?php

use App\Http\Controllers\Api\V1\Students\GuardianController;
use App\Http\Controllers\Api\V1\Students\StudentController;
use Illuminate\Support\Facades\Route;

/*
| Students module — registered inside the v1 + auth:sanctum group.
| Route-model binding uses the singular parameter (e.g. {student}).
*/

Route::apiResource('students', StudentController::class);
Route::apiResource('guardians', GuardianController::class);
