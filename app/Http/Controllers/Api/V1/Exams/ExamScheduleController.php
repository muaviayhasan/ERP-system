<?php

namespace App\Http\Controllers\Api\V1\Exams;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Exam\StoreExamScheduleRequest;
use App\Http\Requests\Exam\UpdateExamScheduleRequest;
use App\Http\Resources\ExamScheduleResource;
use App\Models\ExamSchedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExamScheduleController extends ApiController
{
    protected array $filterable = ['exam_id', 'subject_id', 'program_id', 'invigilator_id', 'exam_type', 'status', 'has_conflict', 'conflict_severity'];
    protected array $searchable = ['class_label', 'venue', 'conflict_note'];
    protected array $sortable = ['id', 'exam_date', 'start_time', 'end_time', 'created_at'];
    protected array $includable = ['exam', 'subject', 'program', 'invigilator', 'examScheduleConflicts'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(ExamSchedule::query(), $request);

        return $this->respondSuccess(
            ExamScheduleResource::collection($query->paginate($this->perPage($request))),
            'Exam schedules retrieved successfully.'
        );
    }

    public function store(StoreExamScheduleRequest $request): JsonResponse
    {
        $examSchedule = ExamSchedule::create($request->validated());

        return $this->respondCreated(ExamScheduleResource::make($examSchedule), 'Exam schedule created successfully.');
    }

    public function show(ExamSchedule $examSchedule): JsonResponse
    {
        $examSchedule->load(['exam', 'subject', 'program', 'invigilator']);

        return $this->respondSuccess(ExamScheduleResource::make($examSchedule), 'Exam schedule retrieved successfully.');
    }

    public function update(UpdateExamScheduleRequest $request, ExamSchedule $examSchedule): JsonResponse
    {
        $examSchedule->update($request->validated());

        return $this->respondSuccess(ExamScheduleResource::make($examSchedule), 'Exam schedule updated successfully.');
    }

    public function destroy(ExamSchedule $examSchedule): JsonResponse
    {
        $examSchedule->delete();

        return $this->respondNoContent('Exam schedule deleted successfully.');
    }
}
