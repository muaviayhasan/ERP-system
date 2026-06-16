<?php

namespace App\Http\Controllers\Api\V1\Attendance;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Attendance\StoreLowAttendanceAlertRequest;
use App\Http\Requests\Attendance\UpdateLowAttendanceAlertRequest;
use App\Http\Resources\LowAttendanceAlertResource;
use App\Models\LowAttendanceAlert;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LowAttendanceAlertController extends ApiController
{
    protected array $filterable = ['student_id', 'class_id', 'risk_level', 'scholarship_status', 'exam_eligibility_restricted', 'sms_warning_sent', 'guardian_notified'];
    protected array $searchable = ['risk_level', 'scholarship_status'];
    protected array $sortable = ['id', 'attendance_percentage', 'risk_level', 'student_id', 'created_at'];
    protected array $includable = ['student', 'schoolClass'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(LowAttendanceAlert::query(), $request);

        return $this->respondSuccess(
            LowAttendanceAlertResource::collection($query->paginate($this->perPage($request))),
            'Low attendance alerts retrieved successfully.'
        );
    }

    public function store(StoreLowAttendanceAlertRequest $request): JsonResponse
    {
        $lowAttendanceAlert = LowAttendanceAlert::create($request->validated());

        return $this->respondCreated(LowAttendanceAlertResource::make($lowAttendanceAlert), 'Low attendance alert created successfully.');
    }

    public function show(LowAttendanceAlert $lowAttendanceAlert): JsonResponse
    {
        $lowAttendanceAlert->load(['student', 'schoolClass']);

        return $this->respondSuccess(LowAttendanceAlertResource::make($lowAttendanceAlert), 'Low attendance alert retrieved successfully.');
    }

    public function update(UpdateLowAttendanceAlertRequest $request, LowAttendanceAlert $lowAttendanceAlert): JsonResponse
    {
        $lowAttendanceAlert->update($request->validated());

        return $this->respondSuccess(LowAttendanceAlertResource::make($lowAttendanceAlert), 'Low attendance alert updated successfully.');
    }

    public function destroy(LowAttendanceAlert $lowAttendanceAlert): JsonResponse
    {
        $lowAttendanceAlert->delete();

        return $this->respondNoContent('Low attendance alert deleted successfully.');
    }
}
