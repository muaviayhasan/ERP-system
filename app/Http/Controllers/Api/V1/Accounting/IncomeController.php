<?php

namespace App\Http\Controllers\Api\V1\Accounting;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Accounting\StoreIncomeRequest;
use App\Http\Requests\Accounting\UpdateIncomeRequest;
use App\Http\Resources\IncomeResource;
use App\Models\Income;
use App\Services\Finance\IncomeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IncomeController extends ApiController
{
    protected array $filterable = ['status', 'campus_id', 'category_id', 'payment_method'];
    protected array $searchable = ['reference_no', 'title', 'subtitle'];
    protected array $sortable = ['id', 'reference_no', 'title', 'amount', 'income_date', 'created_at'];
    protected array $includable = ['category', 'campus', 'createdBy'];

    public function __construct(private readonly IncomeService $incomes)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Income::query(), $request);

        return $this->respondSuccess(
            IncomeResource::collection($query->paginate($this->perPage($request))),
            'Incomes retrieved successfully.'
        );
    }

    public function store(StoreIncomeRequest $request): JsonResponse
    {
        $income = $this->incomes->create($request->validated(), $request->user()?->id);

        return $this->respondCreated(IncomeResource::make($income), 'Income created and posted to the ledger.');
    }

    public function show(Income $income): JsonResponse
    {
        $income->load(['category', 'campus']);

        return $this->respondSuccess(IncomeResource::make($income), 'Income retrieved successfully.');
    }

    public function update(UpdateIncomeRequest $request, Income $income): JsonResponse
    {
        $income->update($request->validated());

        return $this->respondSuccess(IncomeResource::make($income), 'Income updated successfully.');
    }

    public function destroy(Income $income): JsonResponse
    {
        $income->delete();

        return $this->respondNoContent('Income deleted successfully.');
    }
}
