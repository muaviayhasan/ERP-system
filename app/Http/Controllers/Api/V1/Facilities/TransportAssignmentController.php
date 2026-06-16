<?php

namespace App\Http\Controllers\Api\V1\Facilities;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Facility\StoreTransportAssignmentRequest;
use App\Http\Requests\Facility\UpdateTransportAssignmentRequest;
use App\Http\Resources\TransportAssignmentResource;
use App\Models\TransportAssignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransportAssignmentController extends ApiController
{
    protected array $filterable = ['status', 'student_id', 'route_id'];
    protected array $searchable = [];
    protected array $sortable = ['id', 'created_at'];
    protected array $includable = ['student', 'route', 'pickupStop', 'dropoffStop'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(TransportAssignment::query(), $request);

        return $this->respondSuccess(
            TransportAssignmentResource::collection($query->paginate($this->perPage($request))),
            'Transport assignments retrieved successfully.'
        );
    }

    public function store(StoreTransportAssignmentRequest $request): JsonResponse
    {
        $transportAssignment = TransportAssignment::create($request->validated());

        return $this->respondCreated(TransportAssignmentResource::make($transportAssignment), 'Transport assignment created successfully.');
    }

    public function show(TransportAssignment $transportAssignment): JsonResponse
    {
        $transportAssignment->load(['student', 'route', 'pickupStop', 'dropoffStop']);

        return $this->respondSuccess(TransportAssignmentResource::make($transportAssignment), 'Transport assignment retrieved successfully.');
    }

    public function update(UpdateTransportAssignmentRequest $request, TransportAssignment $transportAssignment): JsonResponse
    {
        $transportAssignment->update($request->validated());

        return $this->respondSuccess(TransportAssignmentResource::make($transportAssignment), 'Transport assignment updated successfully.');
    }

    public function destroy(TransportAssignment $transportAssignment): JsonResponse
    {
        $transportAssignment->delete();

        return $this->respondNoContent('Transport assignment deleted successfully.');
    }
}
