<?php

namespace App\Http\Controllers\Api\V1\Attendance;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Attendance\StoreAttendanceRequest;
use App\Http\Requests\Attendance\UpdateAttendanceRequest;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use App\Services\Attendance\AttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends ApiController
{
    public function __construct(private readonly AttendanceService $attendanceService)
    {
    }

    protected array $filterable = ['status', 'session', 'student_id', 'class_id', 'section_id', 'subject_id', 'teacher_id', 'campus_id', 'date', 'marked_by'];
    protected array $searchable = ['remarks', 'lecture_no', 'room'];
    protected array $sortable = ['id', 'date', 'status', 'student_id', 'created_at'];
    protected array $includable = ['student', 'schoolClass', 'section', 'subject', 'teacher', 'campus', 'markedBy'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Attendance::query(), $request);

        return $this->respondSuccess(
            AttendanceResource::collection($query->paginate($this->perPage($request))),
            'Attendances retrieved successfully.'
        );
    }

    public function store(StoreAttendanceRequest $request): JsonResponse
    {
        // Marking attendance also re-evaluates the student's low-attendance alert.
        $attendance = $this->attendanceService->mark($request->validated(), $request->user()?->id);

        return $this->respondCreated(AttendanceResource::make($attendance), 'Attendance recorded successfully.');
    }

    public function show(Attendance $attendance): JsonResponse
    {
        $attendance->load(['student', 'schoolClass', 'subject', 'teacher']);

        return $this->respondSuccess(AttendanceResource::make($attendance), 'Attendance retrieved successfully.');
    }

    public function update(UpdateAttendanceRequest $request, Attendance $attendance): JsonResponse
    {
        $attendance->update($request->validated());

        return $this->respondSuccess(AttendanceResource::make($attendance), 'Attendance updated successfully.');
    }

    public function destroy(Attendance $attendance): JsonResponse
    {
        $attendance->delete();

        return $this->respondNoContent('Attendance deleted successfully.');
    }
}
