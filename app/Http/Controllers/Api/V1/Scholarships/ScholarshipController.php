<?php

namespace App\Http\Controllers\Api\V1\Scholarships;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Scholarship\StoreScholarshipRequest;
use App\Http\Requests\Scholarship\UpdateScholarshipRequest;
use App\Http\Resources\ScholarshipResource;
use App\Models\Scholarship;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScholarshipController extends ApiController
{
    protected array $filterable = ['status', 'type', 'value_type', 'level'];
    protected array $searchable = ['name', 'code'];
    protected array $sortable = ['id', 'name', 'code', 'type', 'value', 'created_at'];
    protected array $includable = ['assignments', 'applications'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Scholarship::query(), $request);

        return $this->respondSuccess(
            ScholarshipResource::collection($query->paginate($this->perPage($request))),
            'Scholarships retrieved successfully.'
        );
    }

    public function store(StoreScholarshipRequest $request): JsonResponse
    {
        $scholarship = Scholarship::create($request->validated());

        return $this->respondCreated(ScholarshipResource::make($scholarship), 'Scholarship created successfully.');
    }

    public function show(Scholarship $scholarship): JsonResponse
    {
        $scholarship->load(['assignments', 'applications']);

        return $this->respondSuccess(ScholarshipResource::make($scholarship), 'Scholarship retrieved successfully.');
    }

    public function update(UpdateScholarshipRequest $request, Scholarship $scholarship): JsonResponse
    {
        $scholarship->update($request->validated());

        return $this->respondSuccess(ScholarshipResource::make($scholarship), 'Scholarship updated successfully.');
    }

    public function destroy(Scholarship $scholarship): JsonResponse
    {
        $scholarship->delete();

        return $this->respondNoContent('Scholarship deleted successfully.');
    }
}
