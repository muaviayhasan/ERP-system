<?php

namespace App\Http\Controllers\Api\V1\Fees;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Fee\StoreFeeInstallmentRequest;
use App\Http\Requests\Fee\UpdateFeeInstallmentRequest;
use App\Http\Resources\FeeInstallmentResource;
use App\Models\FeeInstallment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeeInstallmentController extends ApiController
{
    protected array $filterable = ['status', 'student_fee_assignment_id', 'installment_number'];
    protected array $searchable = ['label'];
    protected array $sortable = ['id', 'installment_number', 'due_date', 'amount', 'created_at'];
    protected array $includable = ['studentFeeAssignment', 'feePayments'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(FeeInstallment::query(), $request);

        return $this->respondSuccess(
            FeeInstallmentResource::collection($query->paginate($this->perPage($request))),
            'Fee installments retrieved successfully.'
        );
    }

    public function store(StoreFeeInstallmentRequest $request): JsonResponse
    {
        $feeInstallment = FeeInstallment::create($request->validated());

        return $this->respondCreated(FeeInstallmentResource::make($feeInstallment), 'Fee installment created successfully.');
    }

    public function show(FeeInstallment $feeInstallment): JsonResponse
    {
        $feeInstallment->load(['studentFeeAssignment', 'feePayments']);

        return $this->respondSuccess(FeeInstallmentResource::make($feeInstallment), 'Fee installment retrieved successfully.');
    }

    public function update(UpdateFeeInstallmentRequest $request, FeeInstallment $feeInstallment): JsonResponse
    {
        $feeInstallment->update($request->validated());

        return $this->respondSuccess(FeeInstallmentResource::make($feeInstallment), 'Fee installment updated successfully.');
    }

    public function destroy(FeeInstallment $feeInstallment): JsonResponse
    {
        $feeInstallment->delete();

        return $this->respondNoContent('Fee installment deleted successfully.');
    }
}
