<?php

namespace App\Http\Controllers\Api\V1\Attendance;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Attendance\StoreStudyMaterialRequest;
use App\Http\Requests\Attendance\UpdateStudyMaterialRequest;
use App\Http\Resources\StudyMaterialResource;
use App\Models\StudyMaterial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudyMaterialController extends ApiController
{
    protected array $filterable = ['type', 'subject_id', 'class_id', 'folder_id', 'uploaded_by', 'is_active'];
    protected array $searchable = ['title', 'description'];
    protected array $sortable = ['id', 'title', 'type', 'download_count', 'view_count', 'published_at', 'created_at'];
    protected array $includable = ['subject', 'schoolClass', 'folder', 'uploadedBy'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(StudyMaterial::query(), $request);

        return $this->respondSuccess(
            StudyMaterialResource::collection($query->paginate($this->perPage($request))),
            'Study materials retrieved successfully.'
        );
    }

    public function store(StoreStudyMaterialRequest $request): JsonResponse
    {
        $studyMaterial = StudyMaterial::create($request->validated());

        return $this->respondCreated(StudyMaterialResource::make($studyMaterial), 'Study material created successfully.');
    }

    public function show(StudyMaterial $studyMaterial): JsonResponse
    {
        $studyMaterial->load(['subject', 'schoolClass', 'folder', 'uploadedBy']);

        return $this->respondSuccess(StudyMaterialResource::make($studyMaterial), 'Study material retrieved successfully.');
    }

    public function update(UpdateStudyMaterialRequest $request, StudyMaterial $studyMaterial): JsonResponse
    {
        $studyMaterial->update($request->validated());

        return $this->respondSuccess(StudyMaterialResource::make($studyMaterial), 'Study material updated successfully.');
    }

    public function destroy(StudyMaterial $studyMaterial): JsonResponse
    {
        $studyMaterial->delete();

        return $this->respondNoContent('Study material deleted successfully.');
    }
}
