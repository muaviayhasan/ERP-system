<?php

namespace App\Http\Controllers\Api\V1\System;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\System\StoreActivityLogRequest;
use App\Http\Requests\System\UpdateActivityLogRequest;
use App\Http\Resources\ActivityLogResource;
use App\Models\ActivityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivityLogController extends ApiController
{
    protected array $filterable = ['user_id', 'role', 'module', 'action', 'status', 'protocol', 'mfa_status'];
    protected array $searchable = ['audit_ref', 'user_name', 'module', 'action', 'description', 'ip_address'];
    protected array $sortable = ['id', 'module', 'action', 'status', 'created_at'];
    protected array $includable = ['user'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(ActivityLog::query(), $request);

        return $this->respondSuccess(
            ActivityLogResource::collection($query->paginate($this->perPage($request))),
            'Activity logs retrieved successfully.'
        );
    }

    public function store(StoreActivityLogRequest $request): JsonResponse
    {
        $activityLog = ActivityLog::create($request->validated());

        return $this->respondCreated(ActivityLogResource::make($activityLog), 'Activity log created successfully.');
    }

    public function show(ActivityLog $activityLog): JsonResponse
    {
        $activityLog->load(['user']);

        return $this->respondSuccess(ActivityLogResource::make($activityLog), 'Activity log retrieved successfully.');
    }

    public function update(UpdateActivityLogRequest $request, ActivityLog $activityLog): JsonResponse
    {
        $activityLog->update($request->validated());

        return $this->respondSuccess(ActivityLogResource::make($activityLog), 'Activity log updated successfully.');
    }

    public function destroy(ActivityLog $activityLog): JsonResponse
    {
        $activityLog->delete();

        return $this->respondNoContent('Activity log deleted successfully.');
    }
}
