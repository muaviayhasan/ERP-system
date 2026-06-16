<?php

namespace App\Http\Controllers\Api\V1\Academic;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Academic\StoreDepartmentRequest;
use App\Http\Requests\Academic\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartmentController extends ApiController
{
    protected array $filterable = ['campus_id', 'institution_type', 'is_active', 'code'];
    protected array $searchable = ['name', 'code'];
    protected array $sortable = ['id', 'name', 'code', 'created_at'];
    protected array $includable = ['campus', 'hodUser', 'programs', 'courses', 'subjects', 'semesters'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Department::query(), $request);

        return $this->respondSuccess(
            DepartmentResource::collection($query->paginate($this->perPage($request))),
            'Departments retrieved successfully.'
        );
    }

    public function store(StoreDepartmentRequest $request): JsonResponse
    {
        $department = Department::create($request->validated());

        return $this->respondCreated(DepartmentResource::make($department), 'Department created successfully.');
    }

    public function show(Department $department): JsonResponse
    {
        return $this->respondSuccess(DepartmentResource::make($department), 'Department retrieved successfully.');
    }

    public function update(UpdateDepartmentRequest $request, Department $department): JsonResponse
    {
        $department->update($request->validated());

        return $this->respondSuccess(DepartmentResource::make($department), 'Department updated successfully.');
    }

    public function destroy(Department $department): JsonResponse
    {
        $department->delete();

        return $this->respondNoContent('Department deleted successfully.');
    }
}
