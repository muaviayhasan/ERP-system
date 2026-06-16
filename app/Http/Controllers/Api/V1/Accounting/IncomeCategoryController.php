<?php

namespace App\Http\Controllers\Api\V1\Accounting;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Accounting\StoreIncomeCategoryRequest;
use App\Http\Requests\Accounting\UpdateIncomeCategoryRequest;
use App\Http\Resources\IncomeCategoryResource;
use App\Models\IncomeCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IncomeCategoryController extends ApiController
{
    protected array $filterable = ['is_active'];
    protected array $searchable = ['name', 'slug'];
    protected array $sortable = ['id', 'name', 'created_at'];
    protected array $includable = ['incomes'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(IncomeCategory::query(), $request);

        return $this->respondSuccess(
            IncomeCategoryResource::collection($query->paginate($this->perPage($request))),
            'Income categories retrieved successfully.'
        );
    }

    public function store(StoreIncomeCategoryRequest $request): JsonResponse
    {
        $incomeCategory = IncomeCategory::create($request->validated());

        return $this->respondCreated(IncomeCategoryResource::make($incomeCategory), 'Income category created successfully.');
    }

    public function show(IncomeCategory $incomeCategory): JsonResponse
    {
        $incomeCategory->load(['incomes']);

        return $this->respondSuccess(IncomeCategoryResource::make($incomeCategory), 'Income category retrieved successfully.');
    }

    public function update(UpdateIncomeCategoryRequest $request, IncomeCategory $incomeCategory): JsonResponse
    {
        $incomeCategory->update($request->validated());

        return $this->respondSuccess(IncomeCategoryResource::make($incomeCategory), 'Income category updated successfully.');
    }

    public function destroy(IncomeCategory $incomeCategory): JsonResponse
    {
        $incomeCategory->delete();

        return $this->respondNoContent('Income category deleted successfully.');
    }
}
