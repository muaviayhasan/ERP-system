<?php

namespace App\Http\Controllers\Api\V1\Facilities;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Facility\StoreHostelAllocationRequest;
use App\Http\Requests\Facility\UpdateHostelAllocationRequest;
use App\Http\Resources\HostelAllocationResource;
use App\Models\HostelAllocation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HostelAllocationController extends ApiController
{
    protected array $filterable = ['status', 'student_id', 'hostel_id', 'room_id'];
    protected array $searchable = [];
    protected array $sortable = ['id', 'check_in_date', 'check_out_date', 'created_at'];
    protected array $includable = ['student', 'hostel', 'room', 'bed'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(HostelAllocation::query(), $request);

        return $this->respondSuccess(
            HostelAllocationResource::collection($query->paginate($this->perPage($request))),
            'Hostel allocations retrieved successfully.'
        );
    }

    public function store(StoreHostelAllocationRequest $request): JsonResponse
    {
        $hostelAllocation = HostelAllocation::create($request->validated());

        return $this->respondCreated(HostelAllocationResource::make($hostelAllocation), 'Hostel allocation created successfully.');
    }

    public function show(HostelAllocation $hostelAllocation): JsonResponse
    {
        $hostelAllocation->load(['student', 'hostel', 'room', 'bed']);

        return $this->respondSuccess(HostelAllocationResource::make($hostelAllocation), 'Hostel allocation retrieved successfully.');
    }

    public function update(UpdateHostelAllocationRequest $request, HostelAllocation $hostelAllocation): JsonResponse
    {
        $hostelAllocation->update($request->validated());

        return $this->respondSuccess(HostelAllocationResource::make($hostelAllocation), 'Hostel allocation updated successfully.');
    }

    public function destroy(HostelAllocation $hostelAllocation): JsonResponse
    {
        $hostelAllocation->delete();

        return $this->respondNoContent('Hostel allocation deleted successfully.');
    }
}
