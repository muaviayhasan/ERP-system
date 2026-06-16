<?php

namespace App\Http\Controllers\Api\V1\Hr;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Hr\StoreTeacherAssignmentRequest;
use App\Http\Requests\Hr\UpdateTeacherAssignmentRequest;
use App\Http\Resources\TeacherAssignmentResource;
use App\Models\TeacherAssignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherAssignmentController extends ApiController
{
    protected array $filterable = ['teacher_id', 'department_id', 'program_id', 'class_id', 'subject_id', 'course_id', 'section_id', 'semester_id', 'institute_type', 'timetable_status', 'has_conflict', 'status'];
    protected array $searchable = ['credits', 'conflict_note'];
    protected array $sortable = ['id', 'teacher_id', 'weekly_hours', 'timetable_status', 'created_at'];
    protected array $includable = ['teacher', 'department', 'program', 'class', 'subject', 'course', 'section', 'semester'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(TeacherAssignment::query(), $request);

        return $this->respondSuccess(
            TeacherAssignmentResource::collection($query->paginate($this->perPage($request))),
            'Teacher assignments retrieved successfully.'
        );
    }

    public function store(StoreTeacherAssignmentRequest $request): JsonResponse
    {
        $assignment = TeacherAssignment::create($request->validated());

        return $this->respondCreated(TeacherAssignmentResource::make($assignment), 'Teacher assignment created successfully.');
    }

    public function show(TeacherAssignment $teacherAssignment): JsonResponse
    {
        $teacherAssignment->load(['teacher', 'program', 'subject', 'course']);

        return $this->respondSuccess(TeacherAssignmentResource::make($teacherAssignment), 'Teacher assignment retrieved successfully.');
    }

    public function update(UpdateTeacherAssignmentRequest $request, TeacherAssignment $teacherAssignment): JsonResponse
    {
        $teacherAssignment->update($request->validated());

        return $this->respondSuccess(TeacherAssignmentResource::make($teacherAssignment), 'Teacher assignment updated successfully.');
    }

    public function destroy(TeacherAssignment $teacherAssignment): JsonResponse
    {
        $teacherAssignment->delete();

        return $this->respondNoContent('Teacher assignment deleted successfully.');
    }
}
