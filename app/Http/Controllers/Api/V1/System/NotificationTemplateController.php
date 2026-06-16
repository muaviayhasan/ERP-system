<?php

namespace App\Http\Controllers\Api\V1\System;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\System\StoreNotificationTemplateRequest;
use App\Http\Requests\System\UpdateNotificationTemplateRequest;
use App\Http\Resources\NotificationTemplateResource;
use App\Models\NotificationTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationTemplateController extends ApiController
{
    protected array $filterable = ['category', 'status'];
    protected array $searchable = ['name', 'subject', 'body', 'category'];
    protected array $sortable = ['id', 'name', 'category', 'created_at'];
    protected array $includable = ['logs'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(NotificationTemplate::query(), $request);

        return $this->respondSuccess(
            NotificationTemplateResource::collection($query->paginate($this->perPage($request))),
            'Notification templates retrieved successfully.'
        );
    }

    public function store(StoreNotificationTemplateRequest $request): JsonResponse
    {
        $template = NotificationTemplate::create($request->validated());

        return $this->respondCreated(NotificationTemplateResource::make($template), 'Notification template created successfully.');
    }

    public function show(NotificationTemplate $notificationTemplate): JsonResponse
    {
        return $this->respondSuccess(NotificationTemplateResource::make($notificationTemplate), 'Notification template retrieved successfully.');
    }

    public function update(UpdateNotificationTemplateRequest $request, NotificationTemplate $notificationTemplate): JsonResponse
    {
        $notificationTemplate->update($request->validated());

        return $this->respondSuccess(NotificationTemplateResource::make($notificationTemplate), 'Notification template updated successfully.');
    }

    public function destroy(NotificationTemplate $notificationTemplate): JsonResponse
    {
        $notificationTemplate->delete();

        return $this->respondNoContent('Notification template deleted successfully.');
    }
}
