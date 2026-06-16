<?php

namespace App\Http\Controllers\Api\V1\Students;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends ApiController
{
    protected array $filterable = ['status', 'admission_status', 'campus_id', 'program_id', 'section_id', 'batch_id', 'gender', 'institute_type'];
    protected array $searchable = ['first_name', 'last_name', 'full_name', 'student_code', 'email', 'roll_number'];
    protected array $sortable = ['id', 'first_name', 'student_code', 'roll_number', 'created_at'];
    protected array $includable = ['campus', 'program', 'guardians', 'section', 'batch'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Student::query(), $request);

        return $this->respondSuccess(
            StudentResource::collection($query->paginate($this->perPage($request))),
            'Students retrieved successfully.'
        );
    }

    public function store(StoreStudentRequest $request): JsonResponse
    {
        $student = Student::create($request->validated());

        return $this->respondCreated(StudentResource::make($student), 'Student created successfully.');
    }

    public function show(Student $student): JsonResponse
    {
        $student->load(['campus', 'program', 'guardians']);

        return $this->respondSuccess(StudentResource::make($student), 'Student retrieved successfully.');
    }

    public function update(UpdateStudentRequest $request, Student $student): JsonResponse
    {
        $student->update($request->validated());

        return $this->respondSuccess(StudentResource::make($student), 'Student updated successfully.');
    }

    public function destroy(Student $student): JsonResponse
    {
        $student->delete();

        return $this->respondNoContent('Student deleted successfully.');
    }
}
