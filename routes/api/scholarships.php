<?php

use App\Http\Controllers\Api\V1\Scholarships\FineController;
use App\Http\Controllers\Api\V1\Scholarships\FineRuleController;
use App\Http\Controllers\Api\V1\Scholarships\RefundController;
use App\Http\Controllers\Api\V1\Scholarships\ScholarshipApplicationController;
use App\Http\Controllers\Api\V1\Scholarships\ScholarshipAssignmentController;
use App\Http\Controllers\Api\V1\Scholarships\ScholarshipController;
use Illuminate\Support\Facades\Route;

/*
| Fines, Refunds & Scholarships module — registered inside the v1 + auth:sanctum group.
| Route-model binding uses the singular parameter (e.g. {fine_rule}, {scholarship_application}).
*/

Route::apiResource('fine-rules', FineRuleController::class);
Route::apiResource('fines', FineController::class);
Route::apiResource('refunds', RefundController::class);
Route::apiResource('scholarships', ScholarshipController::class);
Route::apiResource('scholarship-applications', ScholarshipApplicationController::class);
Route::apiResource('scholarship-assignments', ScholarshipAssignmentController::class);
