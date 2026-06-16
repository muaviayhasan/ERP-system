<?php

namespace App\Http\Controllers\Api\V1\Exams;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Exam\StoreStudentGpaRequest;
use App\Http\Requests\Exam\UpdateStudentGpaRequest;
use App\Http\Resources\StudentGpaResource;
use App\Models\StudentGpa;
use App\Services\Academics\GpaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentGpaController extends ApiController
{
    protected array $filterable = ['student_id', 'program_id', 'department_id', 'semester_id', 'academic_year_id', 'performance_status', 'academic_standing'];
    protected array $searchable = ['performance_status', 'academic_standing'];
    protected array $sortable = ['id', 'gpa', 'cgpa', 'credits', 'last_calculated_at', 'created_at'];
    protected array $includable = ['student', 'program', 'department', 'semester', 'academicYear'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(StudentGpa::query(), $request);

        return $this->respondSuccess(
            StudentGpaResource::collection($query->paginate($this->perPage($request))),
            'Student GPAs retrieved successfully.'
        );
    }

    public function store(StoreStudentGpaRequest $request): JsonResponse
    {
        $studentGpa = StudentGpa::create($request->validated());

        return $this->respondCreated(StudentGpaResource::make($studentGpa), 'Student GPA created successfully.');
    }

    /**
     * Compute (or recompute) a student's GPA/CGPA for a semester from entered
     * exam marks. Requires the `student-gpas.edit` ability.
     */
    public function calculate(Request $request, GpaService $gpa): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'semester_id' => ['required', 'integer', 'exists:semesters,id'],
        ]);

        $result = $gpa->calculate($validated['student_id'], $validated['semester_id']);

        return $this->respondSuccess(StudentGpaResource::make($result), 'GPA/CGPA calculated successfully.');
    }

    public function show(StudentGpa $studentGpa): JsonResponse
    {
        $studentGpa->load(['student', 'program', 'department', 'semester', 'academicYear']);

        return $this->respondSuccess(StudentGpaResource::make($studentGpa), 'Student GPA retrieved successfully.');
    }

    public function update(UpdateStudentGpaRequest $request, StudentGpa $studentGpa): JsonResponse
    {
        $studentGpa->update($request->validated());

        return $this->respondSuccess(StudentGpaResource::make($studentGpa), 'Student GPA updated successfully.');
    }

    public function destroy(StudentGpa $studentGpa): JsonResponse
    {
        $studentGpa->delete();

        return $this->respondNoContent('Student GPA deleted successfully.');
    }
}
