<?php

namespace App\Http\Controllers\Api\V1\Hr;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Hr\StoreSalaryStructureRequest;
use App\Http\Requests\Hr\UpdateSalaryStructureRequest;
use App\Http\Resources\SalaryStructureResource;
use App\Models\SalaryStructure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SalaryStructureController extends ApiController
{
    protected array $filterable = ['employee_type', 'employee_id', 'currency'];
    protected array $searchable = ['employee_type', 'currency'];
    protected array $sortable = ['id', 'basic_salary', 'effective_from', 'created_at'];
    protected array $includable = ['employee', 'payments'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(SalaryStructure::query(), $request);

        return $this->respondSuccess(
            SalaryStructureResource::collection($query->paginate($this->perPage($request))),
            'Salary structures retrieved successfully.'
        );
    }

    public function store(StoreSalaryStructureRequest $request): JsonResponse
    {
        $structure = SalaryStructure::create($request->validated());

        return $this->respondCreated(SalaryStructureResource::make($structure), 'Salary structure created successfully.');
    }

    public function show(SalaryStructure $salaryStructure): JsonResponse
    {
        $salaryStructure->load(['payments']);

        return $this->respondSuccess(SalaryStructureResource::make($salaryStructure), 'Salary structure retrieved successfully.');
    }

    public function update(UpdateSalaryStructureRequest $request, SalaryStructure $salaryStructure): JsonResponse
    {
        $salaryStructure->update($request->validated());

        return $this->respondSuccess(SalaryStructureResource::make($salaryStructure), 'Salary structure updated successfully.');
    }

    public function destroy(SalaryStructure $salaryStructure): JsonResponse
    {
        $salaryStructure->delete();

        return $this->respondNoContent('Salary structure deleted successfully.');
    }
}
