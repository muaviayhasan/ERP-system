<?php

namespace App\Http\Controllers\Api\V1\Hr;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Hr\StoreStaffAttendanceRequest;
use App\Http\Requests\Hr\UpdateStaffAttendanceRequest;
use App\Http\Resources\StaffAttendanceResource;
use App\Models\StaffAttendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StaffAttendanceController extends ApiController
{
    protected array $filterable = ['staff_id', 'department_id', 'campus_id', 'attendance_date', 'shift', 'status', 'is_overtime', 'needs_correction', 'marked_by'];
    protected array $searchable = ['status', 'shift'];
    protected array $sortable = ['id', 'staff_id', 'attendance_date', 'check_in', 'check_out', 'created_at'];
    protected array $includable = ['staff', 'department', 'campus', 'markedBy'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(StaffAttendance::query(), $request);

        return $this->respondSuccess(
            StaffAttendanceResource::collection($query->paginate($this->perPage($request))),
            'Staff attendances retrieved successfully.'
        );
    }

    public function store(StoreStaffAttendanceRequest $request): JsonResponse
    {
        $attendance = StaffAttendance::create($request->validated());

        return $this->respondCreated(StaffAttendanceResource::make($attendance), 'Staff attendance created successfully.');
    }

    public function show(StaffAttendance $staffAttendance): JsonResponse
    {
        $staffAttendance->load(['staff', 'department', 'campus']);

        return $this->respondSuccess(StaffAttendanceResource::make($staffAttendance), 'Staff attendance retrieved successfully.');
    }

    public function update(UpdateStaffAttendanceRequest $request, StaffAttendance $staffAttendance): JsonResponse
    {
        $staffAttendance->update($request->validated());

        return $this->respondSuccess(StaffAttendanceResource::make($staffAttendance), 'Staff attendance updated successfully.');
    }

    public function destroy(StaffAttendance $staffAttendance): JsonResponse
    {
        $staffAttendance->delete();

        return $this->respondNoContent('Staff attendance deleted successfully.');
    }
}
