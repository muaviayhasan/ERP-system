<?php

namespace App\Http\Controllers\Api\V1\Hr;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Hr\StoreStaffRequest;
use App\Http\Requests\Hr\UpdateStaffRequest;
use App\Http\Resources\StaffResource;
use App\Models\Staff;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StaffController extends ApiController
{
    protected array $filterable = ['status', 'campus_id', 'department_id', 'role', 'shift', 'reporting_to_id'];
    protected array $searchable = ['first_name', 'last_name', 'full_name', 'staff_code', 'email', 'phone'];
    protected array $sortable = ['id', 'first_name', 'staff_code', 'role', 'joining_date', 'created_at'];
    protected array $includable = ['user', 'department', 'campus', 'reportingTo', 'attendances'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Staff::query(), $request);

        return $this->respondSuccess(
            StaffResource::collection($query->paginate($this->perPage($request))),
            'Staff retrieved successfully.'
        );
    }

    public function store(StoreStaffRequest $request): JsonResponse
    {
        $staff = Staff::create($request->validated());

        return $this->respondCreated(StaffResource::make($staff), 'Staff created successfully.');
    }

    public function show(Staff $staff): JsonResponse
    {
        $staff->load(['department', 'campus', 'reportingTo']);

        return $this->respondSuccess(StaffResource::make($staff), 'Staff retrieved successfully.');
    }

    public function update(UpdateStaffRequest $request, Staff $staff): JsonResponse
    {
        $staff->update($request->validated());

        return $this->respondSuccess(StaffResource::make($staff), 'Staff updated successfully.');
    }

    public function destroy(Staff $staff): JsonResponse
    {
        $staff->delete();

        return $this->respondNoContent('Staff deleted successfully.');
    }
}
