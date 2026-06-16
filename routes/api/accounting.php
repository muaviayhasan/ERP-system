<?php

use App\Http\Controllers\Api\V1\Accounting\ExpenseCategoryController;
use App\Http\Controllers\Api\V1\Accounting\ExpenseController;
use App\Http\Controllers\Api\V1\Accounting\IncomeCategoryController;
use App\Http\Controllers\Api\V1\Accounting\IncomeController;
use App\Http\Controllers\Api\V1\Accounting\LedgerAccountController;
use App\Http\Controllers\Api\V1\Accounting\LedgerEntryController;
use Illuminate\Support\Facades\Route;

/*
| Accounting / Finance module — registered inside the v1 + auth:sanctum group.
| Route-model binding uses camelCase parameters to match request classes.
*/

Route::apiResource('expense-categories', ExpenseCategoryController::class)
    ->parameters(['expense-categories' => 'expenseCategory']);
Route::apiResource('expenses', ExpenseController::class);
Route::apiResource('income-categories', IncomeCategoryController::class)
    ->parameters(['income-categories' => 'incomeCategory']);
Route::apiResource('incomes', IncomeController::class);
Route::apiResource('ledger-accounts', LedgerAccountController::class)
    ->parameters(['ledger-accounts' => 'ledgerAccount']);
Route::apiResource('ledger-entries', LedgerEntryController::class)
    ->parameters(['ledger-entries' => 'ledgerEntry']);
