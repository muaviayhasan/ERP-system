<?php

namespace App\Http\Controllers\Api\V1\Facilities;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Facility\StoreVehicleRequest;
use App\Http\Requests\Facility\UpdateVehicleRequest;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleController extends ApiController
{
    protected array $filterable = ['status', 'type', 'campus_id', 'route_id', 'driver_id'];
    protected array $searchable = ['vehicle_number', 'type'];
    protected array $sortable = ['id', 'vehicle_number', 'capacity', 'created_at'];
    protected array $includable = ['route', 'driver', 'campus', 'maintenanceLogs'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Vehicle::query(), $request);

        return $this->respondSuccess(
            VehicleResource::collection($query->paginate($this->perPage($request))),
            'Vehicles retrieved successfully.'
        );
    }

    public function store(StoreVehicleRequest $request): JsonResponse
    {
        $vehicle = Vehicle::create($request->validated());

        return $this->respondCreated(VehicleResource::make($vehicle), 'Vehicle created successfully.');
    }

    public function show(Vehicle $vehicle): JsonResponse
    {
        $vehicle->load(['route', 'driver', 'campus']);

        return $this->respondSuccess(VehicleResource::make($vehicle), 'Vehicle retrieved successfully.');
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle): JsonResponse
    {
        $vehicle->update($request->validated());

        return $this->respondSuccess(VehicleResource::make($vehicle), 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle): JsonResponse
    {
        $vehicle->delete();

        return $this->respondNoContent('Vehicle deleted successfully.');
    }
}
