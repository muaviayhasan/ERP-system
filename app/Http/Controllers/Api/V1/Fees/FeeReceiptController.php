<?php

namespace App\Http\Controllers\Api\V1\Fees;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Fee\StoreFeeReceiptRequest;
use App\Http\Requests\Fee\UpdateFeeReceiptRequest;
use App\Http\Resources\FeeReceiptResource;
use App\Models\FeeReceipt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeeReceiptController extends ApiController
{
    protected array $filterable = ['status', 'student_id', 'fee_payment_id', 'program_id', 'campus_id', 'payment_method', 'collected_by'];
    protected array $searchable = ['receipt_number', 'transaction_id', 'reference_number'];
    protected array $sortable = ['id', 'receipt_number', 'amount_paid', 'issued_at', 'created_at'];
    protected array $includable = ['student', 'feePayment', 'program', 'campus', 'collectedBy'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(FeeReceipt::query(), $request);

        return $this->respondSuccess(
            FeeReceiptResource::collection($query->paginate($this->perPage($request))),
            'Fee receipts retrieved successfully.'
        );
    }

    public function store(StoreFeeReceiptRequest $request): JsonResponse
    {
        $feeReceipt = FeeReceipt::create($request->validated());

        return $this->respondCreated(FeeReceiptResource::make($feeReceipt), 'Fee receipt created successfully.');
    }

    public function show(FeeReceipt $feeReceipt): JsonResponse
    {
        $feeReceipt->load(['student', 'feePayment', 'program', 'campus']);

        return $this->respondSuccess(FeeReceiptResource::make($feeReceipt), 'Fee receipt retrieved successfully.');
    }

    public function update(UpdateFeeReceiptRequest $request, FeeReceipt $feeReceipt): JsonResponse
    {
        $feeReceipt->update($request->validated());

        return $this->respondSuccess(FeeReceiptResource::make($feeReceipt), 'Fee receipt updated successfully.');
    }

    public function destroy(FeeReceipt $feeReceipt): JsonResponse
    {
        $feeReceipt->delete();

        return $this->respondNoContent('Fee receipt deleted successfully.');
    }
}
