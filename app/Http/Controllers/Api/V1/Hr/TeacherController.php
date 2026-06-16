<?php

namespace App\Http\Controllers\Api\V1\Hr;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Hr\StoreTeacherRequest;
use App\Http\Requests\Hr\UpdateTeacherRequest;
use App\Http\Resources\TeacherResource;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherController extends ApiController
{
    protected array $filterable = ['status', 'campus_id', 'department_id', 'designation', 'institute_type'];
    protected array $searchable = ['first_name', 'last_name', 'full_name', 'teacher_code', 'email', 'phone'];
    protected array $sortable = ['id', 'first_name', 'teacher_code', 'designation', 'joining_date', 'created_at'];
    protected array $includable = ['user', 'campus', 'department', 'programs', 'assignments'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Teacher::query(), $request);

        return $this->respondSuccess(
            TeacherResource::collection($query->paginate($this->perPage($request))),
            'Teachers retrieved successfully.'
        );
    }

    public function store(StoreTeacherRequest $request): JsonResponse
    {
        $teacher = Teacher::create($request->validated());

        return $this->respondCreated(TeacherResource::make($teacher), 'Teacher created successfully.');
    }

    public function show(Teacher $teacher): JsonResponse
    {
        $teacher->load(['campus', 'department', 'programs']);

        return $this->respondSuccess(TeacherResource::make($teacher), 'Teacher retrieved successfully.');
    }

    public function update(UpdateTeacherRequest $request, Teacher $teacher): JsonResponse
    {
        $teacher->update($request->validated());

        return $this->respondSuccess(TeacherResource::make($teacher), 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher): JsonResponse
    {
        $teacher->delete();

        return $this->respondNoContent('Teacher deleted successfully.');
    }
}
