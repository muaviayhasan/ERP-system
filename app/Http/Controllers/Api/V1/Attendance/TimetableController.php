<?php

namespace App\Http\Controllers\Api\V1\Attendance;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Attendance\StoreTimetableRequest;
use App\Http\Requests\Attendance\UpdateTimetableRequest;
use App\Http\Resources\TimetableResource;
use App\Models\Timetable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TimetableController extends ApiController
{
    protected array $filterable = ['campus_id', 'program_id', 'semester_id', 'institute_type'];
    protected array $searchable = ['name', 'institute_type'];
    protected array $sortable = ['id', 'name', 'week_start_date', 'created_at'];
    protected array $includable = ['campus', 'program', 'semester', 'slots'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Timetable::query(), $request);

        return $this->respondSuccess(
            TimetableResource::collection($query->paginate($this->perPage($request))),
            'Timetables retrieved successfully.'
        );
    }

    public function store(StoreTimetableRequest $request): JsonResponse
    {
        $timetable = Timetable::create($request->validated());

        return $this->respondCreated(TimetableResource::make($timetable), 'Timetable created successfully.');
    }

    public function show(Timetable $timetable): JsonResponse
    {
        $timetable->load(['campus', 'program', 'semester', 'slots']);

        return $this->respondSuccess(TimetableResource::make($timetable), 'Timetable retrieved successfully.');
    }

    public function update(UpdateTimetableRequest $request, Timetable $timetable): JsonResponse
    {
        $timetable->update($request->validated());

        return $this->respondSuccess(TimetableResource::make($timetable), 'Timetable updated successfully.');
    }

    public function destroy(Timetable $timetable): JsonResponse
    {
        $timetable->delete();

        return $this->respondNoContent('Timetable deleted successfully.');
    }
}
