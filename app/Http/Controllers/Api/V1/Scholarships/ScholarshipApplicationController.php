<?php

namespace App\Http\Controllers\Api\V1\Scholarships;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Scholarship\StoreScholarshipApplicationRequest;
use App\Http\Requests\Scholarship\UpdateScholarshipApplicationRequest;
use App\Http\Resources\ScholarshipApplicationResource;
use App\Models\ScholarshipApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScholarshipApplicationController extends ApiController
{
    protected array $filterable = ['status', 'student_id', 'scholarship_id', 'program_id', 'semester_id', 'type', 'priority', 'reviewed_by'];
    protected array $searchable = ['reason', 'institute'];
    protected array $sortable = ['id', 'type', 'status', 'priority', 'cgpa', 'application_date', 'created_at'];
    protected array $includable = ['student', 'scholarship', 'program', 'semester', 'reviewedBy', 'documents', 'logs'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(ScholarshipApplication::query(), $request);

        return $this->respondSuccess(
            ScholarshipApplicationResource::collection($query->paginate($this->perPage($request))),
            'Scholarship applications retrieved successfully.'
        );
    }

    public function store(StoreScholarshipApplicationRequest $request): JsonResponse
    {
        $application = ScholarshipApplication::create($request->validated());

        return $this->respondCreated(ScholarshipApplicationResource::make($application), 'Scholarship application created successfully.');
    }

    public function show(ScholarshipApplication $scholarshipApplication): JsonResponse
    {
        $scholarshipApplication->load(['student', 'scholarship', 'program', 'semester', 'documents']);

        return $this->respondSuccess(ScholarshipApplicationResource::make($scholarshipApplication), 'Scholarship application retrieved successfully.');
    }

    public function update(UpdateScholarshipApplicationRequest $request, ScholarshipApplication $scholarshipApplication): JsonResponse
    {
        $scholarshipApplication->update($request->validated());

        return $this->respondSuccess(ScholarshipApplicationResource::make($scholarshipApplication), 'Scholarship application updated successfully.');
    }

    public function destroy(ScholarshipApplication $scholarshipApplication): JsonResponse
    {
        $scholarshipApplication->delete();

        return $this->respondNoContent('Scholarship application deleted successfully.');
    }
}
