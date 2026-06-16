<?php

namespace App\Http\Controllers\Api\V1\Exams;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Exam\StoreExamRequest;
use App\Http\Requests\Exam\UpdateExamRequest;
use App\Http\Resources\ExamResource;
use App\Models\Exam;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExamController extends ApiController
{
    protected array $filterable = ['exam_type', 'status', 'result_status', 'academic_year_id', 'program_id', 'department_id', 'semester_id', 'campus_id'];
    protected array $searchable = ['name', 'code', 'scope_label'];
    protected array $sortable = ['id', 'name', 'code', 'start_date', 'end_date', 'created_at'];
    protected array $includable = ['academicYear', 'program', 'department', 'semester', 'campus', 'createdBy'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Exam::query(), $request);

        return $this->respondSuccess(
            ExamResource::collection($query->paginate($this->perPage($request))),
            'Exams retrieved successfully.'
        );
    }

    public function store(StoreExamRequest $request): JsonResponse
    {
        $exam = Exam::create($request->validated());

        return $this->respondCreated(ExamResource::make($exam), 'Exam created successfully.');
    }

    public function show(Exam $exam): JsonResponse
    {
        $exam->load(['academicYear', 'program', 'department', 'semester', 'campus']);

        return $this->respondSuccess(ExamResource::make($exam), 'Exam retrieved successfully.');
    }

    public function update(UpdateExamRequest $request, Exam $exam): JsonResponse
    {
        $exam->update($request->validated());

        return $this->respondSuccess(ExamResource::make($exam), 'Exam updated successfully.');
    }

    public function destroy(Exam $exam): JsonResponse
    {
        $exam->delete();

        return $this->respondNoContent('Exam deleted successfully.');
    }
}
