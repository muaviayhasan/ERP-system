<?php

namespace App\Http\Controllers\Api\V1\Scholarships;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Scholarship\StoreFineRuleRequest;
use App\Http\Requests\Scholarship\UpdateFineRuleRequest;
use App\Http\Resources\FineRuleResource;
use App\Models\FineRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FineRuleController extends ApiController
{
    protected array $filterable = ['status', 'type', 'level', 'calculation_method'];
    protected array $searchable = ['name'];
    protected array $sortable = ['id', 'name', 'type', 'amount', 'created_at'];
    protected array $includable = ['fines'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(FineRule::query(), $request);

        return $this->respondSuccess(
            FineRuleResource::collection($query->paginate($this->perPage($request))),
            'Fine rules retrieved successfully.'
        );
    }

    public function store(StoreFineRuleRequest $request): JsonResponse
    {
        $fineRule = FineRule::create($request->validated());

        return $this->respondCreated(FineRuleResource::make($fineRule), 'Fine rule created successfully.');
    }

    public function show(FineRule $fineRule): JsonResponse
    {
        $fineRule->load(['fines']);

        return $this->respondSuccess(FineRuleResource::make($fineRule), 'Fine rule retrieved successfully.');
    }

    public function update(UpdateFineRuleRequest $request, FineRule $fineRule): JsonResponse
    {
        $fineRule->update($request->validated());

        return $this->respondSuccess(FineRuleResource::make($fineRule), 'Fine rule updated successfully.');
    }

    public function destroy(FineRule $fineRule): JsonResponse
    {
        $fineRule->delete();

        return $this->respondNoContent('Fine rule deleted successfully.');
    }
}
