<?php

namespace App\Http\Controllers\Api\V1\Fees;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Fee\StoreFeePlanRequest;
use App\Http\Requests\Fee\UpdateFeePlanRequest;
use App\Http\Resources\FeePlanResource;
use App\Models\FeePlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeePlanController extends ApiController
{
    protected array $filterable = ['status', 'fee_structure_id', 'schedule_type'];
    protected array $searchable = ['name'];
    protected array $sortable = ['id', 'name', 'start_date', 'created_at'];
    protected array $includable = ['feeStructure', 'studentFeeAssignments'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(FeePlan::query(), $request);

        return $this->respondSuccess(
            FeePlanResource::collection($query->paginate($this->perPage($request))),
            'Fee plans retrieved successfully.'
        );
    }

    public function store(StoreFeePlanRequest $request): JsonResponse
    {
        $feePlan = FeePlan::create($request->validated());

        return $this->respondCreated(FeePlanResource::make($feePlan), 'Fee plan created successfully.');
    }

    public function show(FeePlan $feePlan): JsonResponse
    {
        $feePlan->load(['feeStructure']);

        return $this->respondSuccess(FeePlanResource::make($feePlan), 'Fee plan retrieved successfully.');
    }

    public function update(UpdateFeePlanRequest $request, FeePlan $feePlan): JsonResponse
    {
        $feePlan->update($request->validated());

        return $this->respondSuccess(FeePlanResource::make($feePlan), 'Fee plan updated successfully.');
    }

    public function destroy(FeePlan $feePlan): JsonResponse
    {
        $feePlan->delete();

        return $this->respondNoContent('Fee plan deleted successfully.');
    }
}
