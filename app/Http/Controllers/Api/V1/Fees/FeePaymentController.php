<?php

namespace App\Http\Controllers\Api\V1\Fees;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Fee\StoreFeePaymentRequest;
use App\Http\Requests\Fee\UpdateFeePaymentRequest;
use App\Http\Resources\FeePaymentResource;
use App\Models\FeePayment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeePaymentController extends ApiController
{
    protected array $filterable = ['status', 'student_id', 'student_fee_assignment_id', 'fee_installment_id', 'receipt_id', 'payment_method', 'collected_by'];
    protected array $searchable = ['transaction_id', 'reference_number'];
    protected array $sortable = ['id', 'amount_paid', 'paid_at', 'created_at'];
    protected array $includable = ['student', 'studentFeeAssignment', 'feeInstallment', 'receipt', 'collectedBy'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(FeePayment::query(), $request);

        return $this->respondSuccess(
            FeePaymentResource::collection($query->paginate($this->perPage($request))),
            'Fee payments retrieved successfully.'
        );
    }

    public function store(StoreFeePaymentRequest $request): JsonResponse
    {
        $feePayment = FeePayment::create($request->validated());

        return $this->respondCreated(FeePaymentResource::make($feePayment), 'Fee payment created successfully.');
    }

    public function show(FeePayment $feePayment): JsonResponse
    {
        $feePayment->load(['student', 'studentFeeAssignment', 'feeInstallment', 'receipt']);

        return $this->respondSuccess(FeePaymentResource::make($feePayment), 'Fee payment retrieved successfully.');
    }

    public function update(UpdateFeePaymentRequest $request, FeePayment $feePayment): JsonResponse
    {
        $feePayment->update($request->validated());

        return $this->respondSuccess(FeePaymentResource::make($feePayment), 'Fee payment updated successfully.');
    }

    public function destroy(FeePayment $feePayment): JsonResponse
    {
        $feePayment->delete();

        return $this->respondNoContent('Fee payment deleted successfully.');
    }
}
