<?php

namespace App\Http\Controllers\Api\V1\Academic;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Academic\StoreAcademicYearRequest;
use App\Http\Requests\Academic\UpdateAcademicYearRequest;
use App\Http\Resources\AcademicYearResource;
use App\Models\AcademicYear;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AcademicYearController extends ApiController
{
    protected array $filterable = ['status', 'scope'];
    protected array $searchable = ['name'];
    protected array $sortable = ['id', 'name', 'start_date', 'created_at'];
    protected array $includable = ['semesters', 'academicSettings', 'campuses'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(AcademicYear::query(), $request);

        return $this->respondSuccess(
            AcademicYearResource::collection($query->paginate($this->perPage($request))),
            'Academic years retrieved successfully.'
        );
    }

    public function store(StoreAcademicYearRequest $request): JsonResponse
    {
        $academicYear = AcademicYear::create($request->validated());

        return $this->respondCreated(AcademicYearResource::make($academicYear), 'Academic year created successfully.');
    }

    public function show(AcademicYear $academicYear): JsonResponse
    {
        return $this->respondSuccess(AcademicYearResource::make($academicYear), 'Academic year retrieved successfully.');
    }

    public function update(UpdateAcademicYearRequest $request, AcademicYear $academicYear): JsonResponse
    {
        $academicYear->update($request->validated());

        return $this->respondSuccess(AcademicYearResource::make($academicYear), 'Academic year updated successfully.');
    }

    public function destroy(AcademicYear $academicYear): JsonResponse
    {
        $academicYear->delete();

        return $this->respondNoContent('Academic year deleted successfully.');
    }
}
