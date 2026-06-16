<?php

namespace App\Http\Controllers\Api\V1\Scholarships;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Scholarship\StoreRefundRequest;
use App\Http\Requests\Scholarship\UpdateRefundRequest;
use App\Http\Resources\RefundResource;
use App\Models\Refund;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RefundController extends ApiController
{
    protected array $filterable = ['status', 'student_id', 'program_id', 'semester_id', 'refund_type', 'approved_by'];
    protected array $searchable = ['reference_no', 'reason', 'payment_reference', 'payout_reference'];
    protected array $sortable = ['id', 'reference_no', 'requested_amount', 'approved_amount', 'request_date', 'created_at'];
    protected array $includable = ['student', 'program', 'semester', 'approvedBy'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Refund::query(), $request);

        return $this->respondSuccess(
            RefundResource::collection($query->paginate($this->perPage($request))),
            'Refunds retrieved successfully.'
        );
    }

    public function store(StoreRefundRequest $request): JsonResponse
    {
        $refund = Refund::create($request->validated());

        return $this->respondCreated(RefundResource::make($refund), 'Refund created successfully.');
    }

    public function show(Refund $refund): JsonResponse
    {
        $refund->load(['student', 'program', 'semester']);

        return $this->respondSuccess(RefundResource::make($refund), 'Refund retrieved successfully.');
    }

    public function update(UpdateRefundRequest $request, Refund $refund): JsonResponse
    {
        $refund->update($request->validated());

        return $this->respondSuccess(RefundResource::make($refund), 'Refund updated successfully.');
    }

    public function destroy(Refund $refund): JsonResponse
    {
        $refund->delete();

        return $this->respondNoContent('Refund deleted successfully.');
    }
}
