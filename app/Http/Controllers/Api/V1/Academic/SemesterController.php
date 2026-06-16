<?php

namespace App\Http\Controllers\Api\V1\Academic;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Academic\StoreSemesterRequest;
use App\Http\Requests\Academic\UpdateSemesterRequest;
use App\Http\Resources\SemesterResource;
use App\Models\Semester;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SemesterController extends ApiController
{
    protected array $filterable = ['status', 'program_id', 'department_id', 'campus_id', 'academic_year_id', 'is_locked', 'code'];
    protected array $searchable = ['name', 'code'];
    protected array $sortable = ['id', 'name', 'code', 'start_date', 'created_at'];
    protected array $includable = ['program', 'department', 'campus', 'academicYear', 'courses', 'subjects'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Semester::query(), $request);

        return $this->respondSuccess(
            SemesterResource::collection($query->paginate($this->perPage($request))),
            'Semesters retrieved successfully.'
        );
    }

    public function store(StoreSemesterRequest $request): JsonResponse
    {
        $semester = Semester::create($request->validated());

        return $this->respondCreated(SemesterResource::make($semester), 'Semester created successfully.');
    }

    public function show(Semester $semester): JsonResponse
    {
        return $this->respondSuccess(SemesterResource::make($semester), 'Semester retrieved successfully.');
    }

    public function update(UpdateSemesterRequest $request, Semester $semester): JsonResponse
    {
        $semester->update($request->validated());

        return $this->respondSuccess(SemesterResource::make($semester), 'Semester updated successfully.');
    }

    public function destroy(Semester $semester): JsonResponse
    {
        $semester->delete();

        return $this->respondNoContent('Semester deleted successfully.');
    }
}
