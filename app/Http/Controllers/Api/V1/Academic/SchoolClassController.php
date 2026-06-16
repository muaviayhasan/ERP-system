<?php

namespace App\Http\Controllers\Api\V1\Academic;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Academic\StoreSchoolClassRequest;
use App\Http\Requests\Academic\UpdateSchoolClassRequest;
use App\Http\Resources\SchoolClassResource;
use App\Models\SchoolClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SchoolClassController extends ApiController
{
    protected array $filterable = ['status', 'institution_type', 'academic_level', 'campus_id', 'semester_id', 'is_active', 'code'];
    protected array $searchable = ['name', 'code', 'board'];
    protected array $sortable = ['id', 'name', 'code', 'created_at'];
    protected array $includable = ['campus', 'semester', 'coordinatorUser', 'sections', 'batches', 'subjects'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(SchoolClass::query(), $request);

        return $this->respondSuccess(
            SchoolClassResource::collection($query->paginate($this->perPage($request))),
            'Classes retrieved successfully.'
        );
    }

    public function store(StoreSchoolClassRequest $request): JsonResponse
    {
        $class = SchoolClass::create($request->validated());

        return $this->respondCreated(SchoolClassResource::make($class), 'Class created successfully.');
    }

    public function show(SchoolClass $class): JsonResponse
    {
        return $this->respondSuccess(SchoolClassResource::make($class), 'Class retrieved successfully.');
    }

    public function update(UpdateSchoolClassRequest $request, SchoolClass $class): JsonResponse
    {
        $class->update($request->validated());

        return $this->respondSuccess(SchoolClassResource::make($class), 'Class updated successfully.');
    }

    public function destroy(SchoolClass $class): JsonResponse
    {
        $class->delete();

        return $this->respondNoContent('Class deleted successfully.');
    }
}
