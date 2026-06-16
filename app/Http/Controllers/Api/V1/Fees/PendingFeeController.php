<?php

namespace App\Http\Controllers\Api\V1\Fees;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Fee\StorePendingFeeRequest;
use App\Http\Requests\Fee\UpdatePendingFeeRequest;
use App\Http\Resources\PendingFeeResource;
use App\Models\PendingFee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PendingFeeController extends ApiController
{
    protected array $filterable = ['status', 'student_id', 'student_fee_assignment_id', 'program_id'];
    protected array $searchable = [];
    protected array $sortable = ['id', 'amount_pending', 'due_date', 'days_overdue', 'created_at'];
    protected array $includable = ['student', 'studentFeeAssignment', 'program', 'feeReminders'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(PendingFee::query(), $request);

        return $this->respondSuccess(
            PendingFeeResource::collection($query->paginate($this->perPage($request))),
            'Pending fees retrieved successfully.'
        );
    }

    public function store(StorePendingFeeRequest $request): JsonResponse
    {
        $pendingFee = PendingFee::create($request->validated());

        return $this->respondCreated(PendingFeeResource::make($pendingFee), 'Pending fee created successfully.');
    }

    public function show(PendingFee $pendingFee): JsonResponse
    {
        $pendingFee->load(['student', 'studentFeeAssignment', 'program']);

        return $this->respondSuccess(PendingFeeResource::make($pendingFee), 'Pending fee retrieved successfully.');
    }

    public function update(UpdatePendingFeeRequest $request, PendingFee $pendingFee): JsonResponse
    {
        $pendingFee->update($request->validated());

        return $this->respondSuccess(PendingFeeResource::make($pendingFee), 'Pending fee updated successfully.');
    }

    public function destroy(PendingFee $pendingFee): JsonResponse
    {
        $pendingFee->delete();

        return $this->respondNoContent('Pending fee deleted successfully.');
    }
}
