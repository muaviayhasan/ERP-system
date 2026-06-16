<?php

namespace App\Http\Controllers\Api\V1\Academic;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Academic\StoreProgramRequest;
use App\Http\Requests\Academic\UpdateProgramRequest;
use App\Http\Resources\ProgramResource;
use App\Models\Program;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProgramController extends ApiController
{
    protected array $filterable = ['status', 'degree_level', 'department_id', 'faculty', 'code'];
    protected array $searchable = ['name', 'code', 'faculty'];
    protected array $sortable = ['id', 'name', 'code', 'created_at'];
    protected array $includable = ['department', 'coordinatorUser', 'courses', 'batches', 'semesters', 'campuses'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Program::query(), $request);

        return $this->respondSuccess(
            ProgramResource::collection($query->paginate($this->perPage($request))),
            'Programs retrieved successfully.'
        );
    }

    public function store(StoreProgramRequest $request): JsonResponse
    {
        $program = Program::create($request->validated());

        return $this->respondCreated(ProgramResource::make($program), 'Program created successfully.');
    }

    public function show(Program $program): JsonResponse
    {
        return $this->respondSuccess(ProgramResource::make($program), 'Program retrieved successfully.');
    }

    public function update(UpdateProgramRequest $request, Program $program): JsonResponse
    {
        $program->update($request->validated());

        return $this->respondSuccess(ProgramResource::make($program), 'Program updated successfully.');
    }

    public function destroy(Program $program): JsonResponse
    {
        $program->delete();

        return $this->respondNoContent('Program deleted successfully.');
    }
}
