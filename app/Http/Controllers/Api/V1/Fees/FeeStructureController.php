<?php

namespace App\Http\Controllers\Api\V1\Fees;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Fee\StoreFeeStructureRequest;
use App\Http\Requests\Fee\UpdateFeeStructureRequest;
use App\Http\Resources\FeeStructureResource;
use App\Models\FeeStructure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeeStructureController extends ApiController
{
    protected array $filterable = ['status', 'campus_id', 'program_id', 'academic_year_id', 'institute_type', 'level', 'billing_cycle'];
    protected array $searchable = ['name', 'code'];
    protected array $sortable = ['id', 'name', 'code', 'total_fee', 'created_at'];
    protected array $includable = ['campus', 'program', 'academicYear', 'feePlans', 'feeStructureComponents', 'studentFeeAssignments'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(FeeStructure::query(), $request);

        return $this->respondSuccess(
            FeeStructureResource::collection($query->paginate($this->perPage($request))),
            'Fee structures retrieved successfully.'
        );
    }

    public function store(StoreFeeStructureRequest $request): JsonResponse
    {
        $feeStructure = FeeStructure::create($request->validated());

        return $this->respondCreated(FeeStructureResource::make($feeStructure), 'Fee structure created successfully.');
    }

    public function show(FeeStructure $feeStructure): JsonResponse
    {
        $feeStructure->load(['campus', 'program', 'academicYear']);

        return $this->respondSuccess(FeeStructureResource::make($feeStructure), 'Fee structure retrieved successfully.');
    }

    public function update(UpdateFeeStructureRequest $request, FeeStructure $feeStructure): JsonResponse
    {
        $feeStructure->update($request->validated());

        return $this->respondSuccess(FeeStructureResource::make($feeStructure), 'Fee structure updated successfully.');
    }

    public function destroy(FeeStructure $feeStructure): JsonResponse
    {
        $feeStructure->delete();

        return $this->respondNoContent('Fee structure deleted successfully.');
    }
}
