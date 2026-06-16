<?php

namespace App\Http\Controllers\Api\V1\Exams;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Exam\StoreGradeScaleRequest;
use App\Http\Requests\Exam\UpdateGradeScaleRequest;
use App\Http\Resources\GradeScaleResource;
use App\Models\GradeScale;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GradeScaleController extends ApiController
{
    protected array $filterable = ['program_id', 'grade', 'is_passing'];
    protected array $searchable = ['grade'];
    protected array $sortable = ['id', 'grade', 'min_percent', 'max_percent', 'gpa_point', 'created_at'];
    protected array $includable = ['program'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(GradeScale::query(), $request);

        return $this->respondSuccess(
            GradeScaleResource::collection($query->paginate($this->perPage($request))),
            'Grade scales retrieved successfully.'
        );
    }

    public function store(StoreGradeScaleRequest $request): JsonResponse
    {
        $gradeScale = GradeScale::create($request->validated());

        return $this->respondCreated(GradeScaleResource::make($gradeScale), 'Grade scale created successfully.');
    }

    public function show(GradeScale $gradeScale): JsonResponse
    {
        $gradeScale->load(['program']);

        return $this->respondSuccess(GradeScaleResource::make($gradeScale), 'Grade scale retrieved successfully.');
    }

    public function update(UpdateGradeScaleRequest $request, GradeScale $gradeScale): JsonResponse
    {
        $gradeScale->update($request->validated());

        return $this->respondSuccess(GradeScaleResource::make($gradeScale), 'Grade scale updated successfully.');
    }

    public function destroy(GradeScale $gradeScale): JsonResponse
    {
        $gradeScale->delete();

        return $this->respondNoContent('Grade scale deleted successfully.');
    }
}
