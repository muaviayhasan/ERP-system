<?php

namespace App\Http\Controllers\Api\V1\Exams;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Exam\StoreResultCardRequest;
use App\Http\Requests\Exam\UpdateResultCardRequest;
use App\Http\Resources\ResultCardResource;
use App\Models\ResultCard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResultCardController extends ApiController
{
    protected array $filterable = ['student_id', 'exam_id', 'academic_year_id', 'class_id', 'section_id', 'campus_id', 'result_status', 'is_published', 'is_locked'];
    protected array $searchable = ['verification_code', 'overall_grade', 'fee_status'];
    protected array $sortable = ['id', 'cumulative_gpa', 'rank_in_class', 'generated_at', 'created_at'];
    protected array $includable = ['student', 'exam', 'academicYear', 'schoolClass', 'section', 'campus', 'classTeacher', 'registrar', 'resultCardLines'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(ResultCard::query(), $request);

        return $this->respondSuccess(
            ResultCardResource::collection($query->paginate($this->perPage($request))),
            'Result cards retrieved successfully.'
        );
    }

    public function store(StoreResultCardRequest $request): JsonResponse
    {
        $resultCard = ResultCard::create($request->validated());

        return $this->respondCreated(ResultCardResource::make($resultCard), 'Result card created successfully.');
    }

    public function show(ResultCard $resultCard): JsonResponse
    {
        $resultCard->load(['student', 'exam', 'academicYear', 'schoolClass', 'section', 'campus']);

        return $this->respondSuccess(ResultCardResource::make($resultCard), 'Result card retrieved successfully.');
    }

    public function update(UpdateResultCardRequest $request, ResultCard $resultCard): JsonResponse
    {
        $resultCard->update($request->validated());

        return $this->respondSuccess(ResultCardResource::make($resultCard), 'Result card updated successfully.');
    }

    public function destroy(ResultCard $resultCard): JsonResponse
    {
        $resultCard->delete();

        return $this->respondNoContent('Result card deleted successfully.');
    }
}
