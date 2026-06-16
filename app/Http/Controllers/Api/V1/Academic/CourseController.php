<?php

namespace App\Http\Controllers\Api\V1\Academic;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Academic\StoreCourseRequest;
use App\Http\Requests\Academic\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends ApiController
{
    protected array $filterable = ['status', 'type', 'campus_id', 'program_id', 'department_id', 'semester_id', 'is_active', 'code'];
    protected array $searchable = ['name', 'code'];
    protected array $sortable = ['id', 'name', 'code', 'created_at'];
    protected array $includable = ['campus', 'program', 'department', 'semester', 'primaryInstructor', 'semesters'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Course::query(), $request);

        return $this->respondSuccess(
            CourseResource::collection($query->paginate($this->perPage($request))),
            'Courses retrieved successfully.'
        );
    }

    public function store(StoreCourseRequest $request): JsonResponse
    {
        $course = Course::create($request->validated());

        return $this->respondCreated(CourseResource::make($course), 'Course created successfully.');
    }

    public function show(Course $course): JsonResponse
    {
        return $this->respondSuccess(CourseResource::make($course), 'Course retrieved successfully.');
    }

    public function update(UpdateCourseRequest $request, Course $course): JsonResponse
    {
        $course->update($request->validated());

        return $this->respondSuccess(CourseResource::make($course), 'Course updated successfully.');
    }

    public function destroy(Course $course): JsonResponse
    {
        $course->delete();

        return $this->respondNoContent('Course deleted successfully.');
    }
}
