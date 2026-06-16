<?php

namespace App\Http\Controllers\Api\V1\Hr;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Hr\StoreSalaryPaymentRequest;
use App\Http\Requests\Hr\UpdateSalaryPaymentRequest;
use App\Http\Resources\SalaryPaymentResource;
use App\Models\SalaryPayment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SalaryPaymentController extends ApiController
{
    protected array $filterable = ['employee_type', 'employee_id', 'salary_structure_id', 'payroll_month', 'status', 'role_label', 'department_label'];
    protected array $searchable = ['payroll_month', 'transaction_ref', 'role_label', 'department_label'];
    protected array $sortable = ['id', 'payroll_month', 'net_salary', 'status', 'processed_at', 'created_at'];
    protected array $includable = ['employee', 'salaryStructure'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(SalaryPayment::query(), $request);

        return $this->respondSuccess(
            SalaryPaymentResource::collection($query->paginate($this->perPage($request))),
            'Salary payments retrieved successfully.'
        );
    }

    public function store(StoreSalaryPaymentRequest $request): JsonResponse
    {
        $payment = SalaryPayment::create($request->validated());

        return $this->respondCreated(SalaryPaymentResource::make($payment), 'Salary payment created successfully.');
    }

    public function show(SalaryPayment $salaryPayment): JsonResponse
    {
        $salaryPayment->load(['salaryStructure']);

        return $this->respondSuccess(SalaryPaymentResource::make($salaryPayment), 'Salary payment retrieved successfully.');
    }

    public function update(UpdateSalaryPaymentRequest $request, SalaryPayment $salaryPayment): JsonResponse
    {
        $salaryPayment->update($request->validated());

        return $this->respondSuccess(SalaryPaymentResource::make($salaryPayment), 'Salary payment updated successfully.');
    }

    public function destroy(SalaryPayment $salaryPayment): JsonResponse
    {
        $salaryPayment->delete();

        return $this->respondNoContent('Salary payment deleted successfully.');
    }
}
