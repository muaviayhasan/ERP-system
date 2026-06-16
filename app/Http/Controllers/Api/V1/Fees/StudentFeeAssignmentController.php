<?php

namespace App\Http\Controllers\Api\V1\Fees;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Fee\StoreStudentFeeAssignmentRequest;
use App\Http\Requests\Fee\UpdateStudentFeeAssignmentRequest;
use App\Http\Resources\StudentFeeAssignmentResource;
use App\Models\StudentFeeAssignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentFeeAssignmentController extends ApiController
{
    protected array $filterable = ['status', 'student_id', 'fee_structure_id', 'fee_plan_id', 'program_id', 'semester_id', 'campus_id', 'academic_year_id', 'scholarship_id'];
    protected array $searchable = [];
    protected array $sortable = ['id', 'total_fee', 'final_payable', 'next_due_date', 'created_at'];
    protected array $includable = ['student', 'feeStructure', 'feePlan', 'program', 'semester', 'campus', 'academicYear', 'scholarship', 'feeInstallments', 'feePayments'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(StudentFeeAssignment::query(), $request);

        return $this->respondSuccess(
            StudentFeeAssignmentResource::collection($query->paginate($this->perPage($request))),
            'Student fee assignments retrieved successfully.'
        );
    }

    public function store(StoreStudentFeeAssignmentRequest $request): JsonResponse
    {
        $studentFeeAssignment = StudentFeeAssignment::create($request->validated());

        return $this->respondCreated(StudentFeeAssignmentResource::make($studentFeeAssignment), 'Student fee assignment created successfully.');
    }

    public function show(StudentFeeAssignment $studentFeeAssignment): JsonResponse
    {
        $studentFeeAssignment->load(['student', 'feeStructure', 'feePlan', 'feeInstallments']);

        return $this->respondSuccess(StudentFeeAssignmentResource::make($studentFeeAssignment), 'Student fee assignment retrieved successfully.');
    }

    public function update(UpdateStudentFeeAssignmentRequest $request, StudentFeeAssignment $studentFeeAssignment): JsonResponse
    {
        $studentFeeAssignment->update($request->validated());

        return $this->respondSuccess(StudentFeeAssignmentResource::make($studentFeeAssignment), 'Student fee assignment updated successfully.');
    }

    public function destroy(StudentFeeAssignment $studentFeeAssignment): JsonResponse
    {
        $studentFeeAssignment->delete();

        return $this->respondNoContent('Student fee assignment deleted successfully.');
    }
}
