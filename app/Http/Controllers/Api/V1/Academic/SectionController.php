<?php

namespace App\Http\Controllers\Api\V1\Academic;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Academic\StoreSectionRequest;
use App\Http\Requests\Academic\UpdateSectionRequest;
use App\Http\Resources\SectionResource;
use App\Models\Section;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SectionController extends ApiController
{
    protected array $filterable = ['status', 'section_type', 'class_id', 'campus_id', 'institution_type', 'is_active', 'code'];
    protected array $searchable = ['name', 'code'];
    protected array $sortable = ['id', 'name', 'code', 'created_at'];
    protected array $includable = ['schoolClass', 'campus', 'classTeacher'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Section::query(), $request);

        return $this->respondSuccess(
            SectionResource::collection($query->paginate($this->perPage($request))),
            'Sections retrieved successfully.'
        );
    }

    public function store(StoreSectionRequest $request): JsonResponse
    {
        $section = Section::create($request->validated());

        return $this->respondCreated(SectionResource::make($section), 'Section created successfully.');
    }

    public function show(Section $section): JsonResponse
    {
        return $this->respondSuccess(SectionResource::make($section), 'Section retrieved successfully.');
    }

    public function update(UpdateSectionRequest $request, Section $section): JsonResponse
    {
        $section->update($request->validated());

        return $this->respondSuccess(SectionResource::make($section), 'Section updated successfully.');
    }

    public function destroy(Section $section): JsonResponse
    {
        $section->delete();

        return $this->respondNoContent('Section deleted successfully.');
    }
}
