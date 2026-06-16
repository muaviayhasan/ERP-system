<?php

namespace App\Http\Controllers\Api\V1\Hr;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Hr\StorePayrollRuleRequest;
use App\Http\Requests\Hr\UpdatePayrollRuleRequest;
use App\Http\Resources\PayrollRuleResource;
use App\Models\PayrollRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PayrollRuleController extends ApiController
{
    protected array $filterable = ['rule_type', 'is_active'];
    protected array $searchable = ['name', 'rule_type', 'description'];
    protected array $sortable = ['id', 'name', 'rule_type', 'created_at'];
    protected array $includable = [];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(PayrollRule::query(), $request);

        return $this->respondSuccess(
            PayrollRuleResource::collection($query->paginate($this->perPage($request))),
            'Payroll rules retrieved successfully.'
        );
    }

    public function store(StorePayrollRuleRequest $request): JsonResponse
    {
        $rule = PayrollRule::create($request->validated());

        return $this->respondCreated(PayrollRuleResource::make($rule), 'Payroll rule created successfully.');
    }

    public function show(PayrollRule $payrollRule): JsonResponse
    {
        return $this->respondSuccess(PayrollRuleResource::make($payrollRule), 'Payroll rule retrieved successfully.');
    }

    public function update(UpdatePayrollRuleRequest $request, PayrollRule $payrollRule): JsonResponse
    {
        $payrollRule->update($request->validated());

        return $this->respondSuccess(PayrollRuleResource::make($payrollRule), 'Payroll rule updated successfully.');
    }

    public function destroy(PayrollRule $payrollRule): JsonResponse
    {
        $payrollRule->delete();

        return $this->respondNoContent('Payroll rule deleted successfully.');
    }
}
