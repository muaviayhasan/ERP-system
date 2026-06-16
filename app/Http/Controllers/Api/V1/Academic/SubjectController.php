<?php

namespace App\Http\Controllers\Api\V1\Academic;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Academic\StoreSubjectRequest;
use App\Http\Requests\Academic\UpdateSubjectRequest;
use App\Http\Resources\SubjectResource;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubjectController extends ApiController
{
    protected array $filterable = ['status', 'classification', 'institution_type', 'department_id', 'class_id', 'semester_id', 'code'];
    protected array $searchable = ['name', 'code'];
    protected array $sortable = ['id', 'name', 'code', 'created_at'];
    protected array $includable = ['department', 'schoolClass', 'semester', 'primaryTeacher', 'classes'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Subject::query(), $request);

        return $this->respondSuccess(
            SubjectResource::collection($query->paginate($this->perPage($request))),
            'Subjects retrieved successfully.'
        );
    }

    public function store(StoreSubjectRequest $request): JsonResponse
    {
        $subject = Subject::create($request->validated());

        return $this->respondCreated(SubjectResource::make($subject), 'Subject created successfully.');
    }

    public function show(Subject $subject): JsonResponse
    {
        return $this->respondSuccess(SubjectResource::make($subject), 'Subject retrieved successfully.');
    }

    public function update(UpdateSubjectRequest $request, Subject $subject): JsonResponse
    {
        $subject->update($request->validated());

        return $this->respondSuccess(SubjectResource::make($subject), 'Subject updated successfully.');
    }

    public function destroy(Subject $subject): JsonResponse
    {
        $subject->delete();

        return $this->respondNoContent('Subject deleted successfully.');
    }
}
