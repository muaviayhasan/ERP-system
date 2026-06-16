<?php

namespace App\Http\Controllers\Api\V1\Scholarships;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Scholarship\StoreScholarshipAssignmentRequest;
use App\Http\Requests\Scholarship\UpdateScholarshipAssignmentRequest;
use App\Http\Resources\ScholarshipAssignmentResource;
use App\Models\ScholarshipAssignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScholarshipAssignmentController extends ApiController
{
    protected array $filterable = ['status', 'student_id', 'scholarship_id', 'assigned_by'];
    protected array $searchable = [];
    protected array $sortable = ['id', 'discount_amount', 'status', 'expires_at', 'created_at'];
    protected array $includable = ['student', 'scholarship', 'assignedBy'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(ScholarshipAssignment::query(), $request);

        return $this->respondSuccess(
            ScholarshipAssignmentResource::collection($query->paginate($this->perPage($request))),
            'Scholarship assignments retrieved successfully.'
        );
    }

    public function store(StoreScholarshipAssignmentRequest $request): JsonResponse
    {
        $assignment = ScholarshipAssignment::create($request->validated());

        return $this->respondCreated(ScholarshipAssignmentResource::make($assignment), 'Scholarship assignment created successfully.');
    }

    public function show(ScholarshipAssignment $scholarshipAssignment): JsonResponse
    {
        $scholarshipAssignment->load(['student', 'scholarship']);

        return $this->respondSuccess(ScholarshipAssignmentResource::make($scholarshipAssignment), 'Scholarship assignment retrieved successfully.');
    }

    public function update(UpdateScholarshipAssignmentRequest $request, ScholarshipAssignment $scholarshipAssignment): JsonResponse
    {
        $scholarshipAssignment->update($request->validated());

        return $this->respondSuccess(ScholarshipAssignmentResource::make($scholarshipAssignment), 'Scholarship assignment updated successfully.');
    }

    public function destroy(ScholarshipAssignment $scholarshipAssignment): JsonResponse
    {
        $scholarshipAssignment->delete();

        return $this->respondNoContent('Scholarship assignment deleted successfully.');
    }
}
