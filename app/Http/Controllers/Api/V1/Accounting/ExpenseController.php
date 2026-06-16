<?php

namespace App\Http\Controllers\Api\V1\Accounting;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Accounting\StoreExpenseRequest;
use App\Http\Requests\Accounting\UpdateExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use App\Services\Finance\ExpenseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExpenseController extends ApiController
{
    protected array $filterable = ['status', 'campus_id', 'category_id', 'approver_id', 'currency'];
    protected array $searchable = ['reference_no', 'title', 'payee'];
    protected array $sortable = ['id', 'reference_no', 'title', 'amount', 'expense_date', 'created_at'];
    protected array $includable = ['category', 'campus', 'approver', 'createdBy'];

    public function __construct(private readonly ExpenseService $expenses)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Expense::query(), $request);

        return $this->respondSuccess(
            ExpenseResource::collection($query->paginate($this->perPage($request))),
            'Expenses retrieved successfully.'
        );
    }

    public function store(StoreExpenseRequest $request): JsonResponse
    {
        $expense = $this->expenses->create($request->validated(), $request->user()?->id);

        return $this->respondCreated(ExpenseResource::make($expense), 'Expense created and posted to the ledger.');
    }

    public function show(Expense $expense): JsonResponse
    {
        $expense->load(['category', 'campus', 'approver']);

        return $this->respondSuccess(ExpenseResource::make($expense), 'Expense retrieved successfully.');
    }

    public function update(UpdateExpenseRequest $request, Expense $expense): JsonResponse
    {
        $expense->update($request->validated());

        return $this->respondSuccess(ExpenseResource::make($expense), 'Expense updated successfully.');
    }

    public function destroy(Expense $expense): JsonResponse
    {
        $expense->delete();

        return $this->respondNoContent('Expense deleted successfully.');
    }
}
