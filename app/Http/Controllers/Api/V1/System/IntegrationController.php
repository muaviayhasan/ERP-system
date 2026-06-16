<?php

namespace App\Http\Controllers\Api\V1\System;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\System\StoreIntegrationRequest;
use App\Http\Requests\System\UpdateIntegrationRequest;
use App\Http\Resources\IntegrationResource;
use App\Models\Integration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IntegrationController extends ApiController
{
    protected array $filterable = ['provider', 'type', 'is_enabled', 'status'];
    protected array $searchable = ['provider', 'type', 'status'];
    protected array $sortable = ['id', 'provider', 'type', 'created_at'];
    protected array $includable = [];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Integration::query(), $request);

        return $this->respondSuccess(
            IntegrationResource::collection($query->paginate($this->perPage($request))),
            'Integrations retrieved successfully.'
        );
    }

    public function store(StoreIntegrationRequest $request): JsonResponse
    {
        $integration = Integration::create($request->validated());

        return $this->respondCreated(IntegrationResource::make($integration), 'Integration created successfully.');
    }

    public function show(Integration $integration): JsonResponse
    {
        return $this->respondSuccess(IntegrationResource::make($integration), 'Integration retrieved successfully.');
    }

    public function update(UpdateIntegrationRequest $request, Integration $integration): JsonResponse
    {
        $integration->update($request->validated());

        return $this->respondSuccess(IntegrationResource::make($integration), 'Integration updated successfully.');
    }

    public function destroy(Integration $integration): JsonResponse
    {
        $integration->delete();

        return $this->respondNoContent('Integration deleted successfully.');
    }
}
