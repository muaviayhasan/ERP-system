<?php

namespace App\Http\Controllers\Api\V1\System;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\System\StoreSettingRequest;
use App\Http\Requests\System\UpdateSettingRequest;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends ApiController
{
    protected array $filterable = ['group', 'key', 'type'];
    protected array $searchable = ['group', 'key', 'value'];
    protected array $sortable = ['id', 'group', 'key', 'created_at'];
    protected array $includable = [];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Setting::query(), $request);

        return $this->respondSuccess(
            SettingResource::collection($query->paginate($this->perPage($request))),
            'Settings retrieved successfully.'
        );
    }

    public function store(StoreSettingRequest $request): JsonResponse
    {
        $setting = Setting::create($request->validated());

        return $this->respondCreated(SettingResource::make($setting), 'Setting created successfully.');
    }

    public function show(Setting $setting): JsonResponse
    {
        return $this->respondSuccess(SettingResource::make($setting), 'Setting retrieved successfully.');
    }

    public function update(UpdateSettingRequest $request, Setting $setting): JsonResponse
    {
        $setting->update($request->validated());

        return $this->respondSuccess(SettingResource::make($setting), 'Setting updated successfully.');
    }

    public function destroy(Setting $setting): JsonResponse
    {
        $setting->delete();

        return $this->respondNoContent('Setting deleted successfully.');
    }
}
