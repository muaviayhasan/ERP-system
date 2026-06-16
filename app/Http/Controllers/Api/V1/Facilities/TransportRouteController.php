<?php

namespace App\Http\Controllers\Api\V1\Facilities;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Facility\StoreTransportRouteRequest;
use App\Http\Requests\Facility\UpdateTransportRouteRequest;
use App\Http\Resources\TransportRouteResource;
use App\Models\TransportRoute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransportRouteController extends ApiController
{
    protected array $filterable = ['status', 'campus_id', 'vehicle_id'];
    protected array $searchable = ['name', 'code', 'start_point', 'end_point'];
    protected array $sortable = ['id', 'name', 'code', 'created_at'];
    protected array $includable = ['vehicle', 'campus', 'routeStops', 'transportAssignments'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(TransportRoute::query(), $request);

        return $this->respondSuccess(
            TransportRouteResource::collection($query->paginate($this->perPage($request))),
            'Transport routes retrieved successfully.'
        );
    }

    public function store(StoreTransportRouteRequest $request): JsonResponse
    {
        $transportRoute = TransportRoute::create($request->validated());

        return $this->respondCreated(TransportRouteResource::make($transportRoute), 'Transport route created successfully.');
    }

    public function show(TransportRoute $transportRoute): JsonResponse
    {
        $transportRoute->load(['vehicle', 'campus', 'routeStops']);

        return $this->respondSuccess(TransportRouteResource::make($transportRoute), 'Transport route retrieved successfully.');
    }

    public function update(UpdateTransportRouteRequest $request, TransportRoute $transportRoute): JsonResponse
    {
        $transportRoute->update($request->validated());

        return $this->respondSuccess(TransportRouteResource::make($transportRoute), 'Transport route updated successfully.');
    }

    public function destroy(TransportRoute $transportRoute): JsonResponse
    {
        $transportRoute->delete();

        return $this->respondNoContent('Transport route deleted successfully.');
    }
}
