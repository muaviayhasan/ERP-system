<?php

namespace App\Http\Controllers\Api\V1\Accounting;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Accounting\StoreExpenseCategoryRequest;
use App\Http\Requests\Accounting\UpdateExpenseCategoryRequest;
use App\Http\Resources\ExpenseCategoryResource;
use App\Models\ExpenseCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExpenseCategoryController extends ApiController
{
    protected array $filterable = ['is_active'];
    protected array $searchable = ['name', 'slug'];
    protected array $sortable = ['id', 'name', 'budget_amount', 'created_at'];
    protected array $includable = ['expenses'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(ExpenseCategory::query(), $request);

        return $this->respondSuccess(
            ExpenseCategoryResource::collection($query->paginate($this->perPage($request))),
            'Expense categories retrieved successfully.'
        );
    }

    public function store(StoreExpenseCategoryRequest $request): JsonResponse
    {
        $expenseCategory = ExpenseCategory::create($request->validated());

        return $this->respondCreated(ExpenseCategoryResource::make($expenseCategory), 'Expense category created successfully.');
    }

    public function show(ExpenseCategory $expenseCategory): JsonResponse
    {
        $expenseCategory->load(['expenses']);

        return $this->respondSuccess(ExpenseCategoryResource::make($expenseCategory), 'Expense category retrieved successfully.');
    }

    public function update(UpdateExpenseCategoryRequest $request, ExpenseCategory $expenseCategory): JsonResponse
    {
        $expenseCategory->update($request->validated());

        return $this->respondSuccess(ExpenseCategoryResource::make($expenseCategory), 'Expense category updated successfully.');
    }

    public function destroy(ExpenseCategory $expenseCategory): JsonResponse
    {
        $expenseCategory->delete();

        return $this->respondNoContent('Expense category deleted successfully.');
    }
}
