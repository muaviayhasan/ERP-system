<?php

namespace App\Http\Controllers\Api\V1\Scholarships;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Scholarship\StoreFineRequest;
use App\Http\Requests\Scholarship\UpdateFineRequest;
use App\Http\Resources\FineResource;
use App\Models\Fine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FineController extends ApiController
{
    protected array $filterable = ['status', 'student_id', 'fine_rule_id', 'collected_by', 'waived_by'];
    protected array $searchable = ['reason'];
    protected array $sortable = ['id', 'amount', 'date_applied', 'status', 'created_at'];
    protected array $includable = ['student', 'fineRule', 'collectedBy', 'waivedBy'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Fine::query(), $request);

        return $this->respondSuccess(
            FineResource::collection($query->paginate($this->perPage($request))),
            'Fines retrieved successfully.'
        );
    }

    public function store(StoreFineRequest $request): JsonResponse
    {
        $fine = Fine::create($request->validated());

        return $this->respondCreated(FineResource::make($fine), 'Fine created successfully.');
    }

    public function show(Fine $fine): JsonResponse
    {
        $fine->load(['student', 'fineRule']);

        return $this->respondSuccess(FineResource::make($fine), 'Fine retrieved successfully.');
    }

    public function update(UpdateFineRequest $request, Fine $fine): JsonResponse
    {
        $fine->update($request->validated());

        return $this->respondSuccess(FineResource::make($fine), 'Fine updated successfully.');
    }

    public function destroy(Fine $fine): JsonResponse
    {
        $fine->delete();

        return $this->respondNoContent('Fine deleted successfully.');
    }
}
