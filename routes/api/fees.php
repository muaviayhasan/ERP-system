<?php

use App\Http\Controllers\Api\V1\Fees\FeeCategoryController;
use App\Http\Controllers\Api\V1\Fees\FeeInstallmentController;
use App\Http\Controllers\Api\V1\Fees\FeePaymentController;
use App\Http\Controllers\Api\V1\Fees\FeePlanController;
use App\Http\Controllers\Api\V1\Fees\FeeReceiptController;
use App\Http\Controllers\Api\V1\Fees\FeeStructureController;
use App\Http\Controllers\Api\V1\Fees\PendingFeeController;
use App\Http\Controllers\Api\V1\Fees\StudentFeeAssignmentController;
use Illuminate\Support\Facades\Route;

/*
| Fees core module — registered inside the v1 + auth:sanctum group.
| Route-model binding uses the singular parameter (e.g. {fee_category}).
*/

Route::apiResource('fee-categories', FeeCategoryController::class);
Route::apiResource('fee-structures', FeeStructureController::class);
Route::apiResource('fee-plans', FeePlanController::class);
Route::apiResource('student-fee-assignments', StudentFeeAssignmentController::class);
Route::apiResource('fee-installments', FeeInstallmentController::class);
Route::apiResource('fee-payments', FeePaymentController::class);
Route::apiResource('fee-receipts', FeeReceiptController::class);
Route::apiResource('pending-fees', PendingFeeController::class);
