<?php

namespace App\Http\Controllers\Api\V1\Academic;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Academic\StoreBatchRequest;
use App\Http\Requests\Academic\UpdateBatchRequest;
use App\Http\Resources\BatchResource;
use App\Models\Batch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BatchController extends ApiController
{
    protected array $filterable = ['status', 'batch_type', 'institution_type', 'campus_id', 'program_id', 'class_id', 'semester_id', 'code'];
    protected array $searchable = ['name', 'code'];
    protected array $sortable = ['id', 'name', 'code', 'start_date', 'created_at'];
    protected array $includable = ['campus', 'program', 'schoolClass', 'semester', 'primaryInstructor', 'feePlan', 'students'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Batch::query(), $request);

        return $this->respondSuccess(
            BatchResource::collection($query->paginate($this->perPage($request))),
            'Batches retrieved successfully.'
        );
    }

    public function store(StoreBatchRequest $request): JsonResponse
    {
        $batch = Batch::create($request->validated());

        return $this->respondCreated(BatchResource::make($batch), 'Batch created successfully.');
    }

    public function show(Batch $batch): JsonResponse
    {
        return $this->respondSuccess(BatchResource::make($batch), 'Batch retrieved successfully.');
    }

    public function update(UpdateBatchRequest $request, Batch $batch): JsonResponse
    {
        $batch->update($request->validated());

        return $this->respondSuccess(BatchResource::make($batch), 'Batch updated successfully.');
    }

    public function destroy(Batch $batch): JsonResponse
    {
        $batch->delete();

        return $this->respondNoContent('Batch deleted successfully.');
    }
}
