<?php

namespace App\Http\Controllers\Api\V1\Facilities;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Facility\StoreHostelRoomRequest;
use App\Http\Requests\Facility\UpdateHostelRoomRequest;
use App\Http\Resources\HostelRoomResource;
use App\Models\HostelRoom;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HostelRoomController extends ApiController
{
    protected array $filterable = ['status', 'type', 'hostel_id'];
    protected array $searchable = ['room_number', 'floor'];
    protected array $sortable = ['id', 'room_number', 'capacity', 'created_at'];
    protected array $includable = ['hostel', 'beds', 'allocations'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(HostelRoom::query(), $request);

        return $this->respondSuccess(
            HostelRoomResource::collection($query->paginate($this->perPage($request))),
            'Hostel rooms retrieved successfully.'
        );
    }

    public function store(StoreHostelRoomRequest $request): JsonResponse
    {
        $hostelRoom = HostelRoom::create($request->validated());

        return $this->respondCreated(HostelRoomResource::make($hostelRoom), 'Hostel room created successfully.');
    }

    public function show(HostelRoom $hostelRoom): JsonResponse
    {
        $hostelRoom->load(['hostel', 'beds']);

        return $this->respondSuccess(HostelRoomResource::make($hostelRoom), 'Hostel room retrieved successfully.');
    }

    public function update(UpdateHostelRoomRequest $request, HostelRoom $hostelRoom): JsonResponse
    {
        $hostelRoom->update($request->validated());

        return $this->respondSuccess(HostelRoomResource::make($hostelRoom), 'Hostel room updated successfully.');
    }

    public function destroy(HostelRoom $hostelRoom): JsonResponse
    {
        $hostelRoom->delete();

        return $this->respondNoContent('Hostel room deleted successfully.');
    }
}
