<?php

namespace App\Http\Controllers\Api\V1\Attendance;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Attendance\StoreAssignmentRequest;
use App\Http\Requests\Attendance\UpdateAssignmentRequest;
use App\Http\Resources\AssignmentResource;
use App\Models\Assignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssignmentController extends ApiController
{
    protected array $filterable = ['status', 'subject_id', 'class_id', 'section_id', 'teacher_id', 'due_date'];
    protected array $searchable = ['title', 'code', 'description'];
    protected array $sortable = ['id', 'title', 'due_date', 'status', 'created_at'];
    protected array $includable = ['subject', 'schoolClass', 'section', 'teacher', 'submissions'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Assignment::query(), $request);

        return $this->respondSuccess(
            AssignmentResource::collection($query->paginate($this->perPage($request))),
            'Assignments retrieved successfully.'
        );
    }

    public function store(StoreAssignmentRequest $request): JsonResponse
    {
        $assignment = Assignment::create($request->validated());

        return $this->respondCreated(AssignmentResource::make($assignment), 'Assignment created successfully.');
    }

    public function show(Assignment $assignment): JsonResponse
    {
        $assignment->load(['subject', 'schoolClass', 'section', 'teacher']);

        return $this->respondSuccess(AssignmentResource::make($assignment), 'Assignment retrieved successfully.');
    }

    public function update(UpdateAssignmentRequest $request, Assignment $assignment): JsonResponse
    {
        $assignment->update($request->validated());

        return $this->respondSuccess(AssignmentResource::make($assignment), 'Assignment updated successfully.');
    }

    public function destroy(Assignment $assignment): JsonResponse
    {
        $assignment->delete();

        return $this->respondNoContent('Assignment deleted successfully.');
    }
}
