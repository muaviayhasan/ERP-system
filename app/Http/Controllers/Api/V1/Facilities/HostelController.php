<?php

namespace App\Http\Controllers\Api\V1\Facilities;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Facility\StoreHostelRequest;
use App\Http\Requests\Facility\UpdateHostelRequest;
use App\Http\Resources\HostelResource;
use App\Models\Hostel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HostelController extends ApiController
{
    protected array $filterable = ['type', 'occupancy_status', 'campus_id', 'warden_id'];
    protected array $searchable = ['name', 'block'];
    protected array $sortable = ['id', 'name', 'created_at'];
    protected array $includable = ['warden', 'campus', 'rooms', 'allocations'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Hostel::query(), $request);

        return $this->respondSuccess(
            HostelResource::collection($query->paginate($this->perPage($request))),
            'Hostels retrieved successfully.'
        );
    }

    public function store(StoreHostelRequest $request): JsonResponse
    {
        $hostel = Hostel::create($request->validated());

        return $this->respondCreated(HostelResource::make($hostel), 'Hostel created successfully.');
    }

    public function show(Hostel $hostel): JsonResponse
    {
        $hostel->load(['warden', 'campus', 'rooms']);

        return $this->respondSuccess(HostelResource::make($hostel), 'Hostel retrieved successfully.');
    }

    public function update(UpdateHostelRequest $request, Hostel $hostel): JsonResponse
    {
        $hostel->update($request->validated());

        return $this->respondSuccess(HostelResource::make($hostel), 'Hostel updated successfully.');
    }

    public function destroy(Hostel $hostel): JsonResponse
    {
        $hostel->delete();

        return $this->respondNoContent('Hostel deleted successfully.');
    }
}
