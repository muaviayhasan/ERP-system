<?php

namespace App\Http\Controllers\Api\V1\Exams;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Exam\StoreExamResultRequest;
use App\Http\Requests\Exam\UpdateExamResultRequest;
use App\Http\Resources\ExamResultResource;
use App\Models\ExamResult;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExamResultController extends ApiController
{
    protected array $filterable = ['exam_id', 'student_id', 'subject_id', 'evaluator_id', 'attendance_status', 'grade', 'entry_status', 'is_flagged'];
    protected array $searchable = ['grade', 'remarks', 'validation_error'];
    protected array $sortable = ['id', 'marks_obtained', 'percentage', 'created_at'];
    protected array $includable = ['exam', 'student', 'subject', 'evaluator'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(ExamResult::query(), $request);

        return $this->respondSuccess(
            ExamResultResource::collection($query->paginate($this->perPage($request))),
            'Exam results retrieved successfully.'
        );
    }

    public function store(StoreExamResultRequest $request): JsonResponse
    {
        $examResult = ExamResult::create($request->validated());

        return $this->respondCreated(ExamResultResource::make($examResult), 'Exam result created successfully.');
    }

    public function show(ExamResult $examResult): JsonResponse
    {
        $examResult->load(['exam', 'student', 'subject', 'evaluator']);

        return $this->respondSuccess(ExamResultResource::make($examResult), 'Exam result retrieved successfully.');
    }

    public function update(UpdateExamResultRequest $request, ExamResult $examResult): JsonResponse
    {
        $examResult->update($request->validated());

        return $this->respondSuccess(ExamResultResource::make($examResult), 'Exam result updated successfully.');
    }

    public function destroy(ExamResult $examResult): JsonResponse
    {
        $examResult->delete();

        return $this->respondNoContent('Exam result deleted successfully.');
    }
}
