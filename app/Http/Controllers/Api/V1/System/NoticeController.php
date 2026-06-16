<?php

namespace App\Http\Controllers\Api\V1\System;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\System\StoreNoticeRequest;
use App\Http\Requests\System\UpdateNoticeRequest;
use App\Http\Resources\NoticeResource;
use App\Models\Notice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NoticeController extends ApiController
{
    protected array $filterable = ['category', 'type', 'priority', 'status', 'created_by', 'require_acknowledgment'];
    protected array $searchable = ['title', 'description', 'category'];
    protected array $sortable = ['id', 'title', 'priority', 'publish_date', 'created_at'];
    protected array $includable = ['createdBy', 'acknowledgments'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Notice::query(), $request);

        return $this->respondSuccess(
            NoticeResource::collection($query->paginate($this->perPage($request))),
            'Notices retrieved successfully.'
        );
    }

    public function store(StoreNoticeRequest $request): JsonResponse
    {
        $notice = Notice::create($request->validated());

        return $this->respondCreated(NoticeResource::make($notice), 'Notice created successfully.');
    }

    public function show(Notice $notice): JsonResponse
    {
        $notice->load(['createdBy', 'acknowledgments']);

        return $this->respondSuccess(NoticeResource::make($notice), 'Notice retrieved successfully.');
    }

    public function update(UpdateNoticeRequest $request, Notice $notice): JsonResponse
    {
        $notice->update($request->validated());

        return $this->respondSuccess(NoticeResource::make($notice), 'Notice updated successfully.');
    }

    public function destroy(Notice $notice): JsonResponse
    {
        $notice->delete();

        return $this->respondNoContent('Notice deleted successfully.');
    }
}
