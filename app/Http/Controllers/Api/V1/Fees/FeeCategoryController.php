<?php

namespace App\Http\Controllers\Api\V1\Fees;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Fee\StoreFeeCategoryRequest;
use App\Http\Requests\Fee\UpdateFeeCategoryRequest;
use App\Http\Resources\FeeCategoryResource;
use App\Models\FeeCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeeCategoryController extends ApiController
{
    protected array $filterable = ['status', 'fee_type', 'currency'];
    protected array $searchable = ['name', 'code'];
    protected array $sortable = ['id', 'name', 'code', 'default_amount', 'created_at'];
    protected array $includable = ['feeStructureComponents'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(FeeCategory::query(), $request);

        return $this->respondSuccess(
            FeeCategoryResource::collection($query->paginate($this->perPage($request))),
            'Fee categories retrieved successfully.'
        );
    }

    public function store(StoreFeeCategoryRequest $request): JsonResponse
    {
        $feeCategory = FeeCategory::create($request->validated());

        return $this->respondCreated(FeeCategoryResource::make($feeCategory), 'Fee category created successfully.');
    }

    public function show(FeeCategory $feeCategory): JsonResponse
    {
        return $this->respondSuccess(FeeCategoryResource::make($feeCategory), 'Fee category retrieved successfully.');
    }

    public function update(UpdateFeeCategoryRequest $request, FeeCategory $feeCategory): JsonResponse
    {
        $feeCategory->update($request->validated());

        return $this->respondSuccess(FeeCategoryResource::make($feeCategory), 'Fee category updated successfully.');
    }

    public function destroy(FeeCategory $feeCategory): JsonResponse
    {
        $feeCategory->delete();

        return $this->respondNoContent('Fee category deleted successfully.');
    }
}
