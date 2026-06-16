<?php

namespace App\Http\Controllers\Api\V1\Academic;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Academic\StoreCampusRequest;
use App\Http\Requests\Academic\UpdateCampusRequest;
use App\Http\Resources\CampusResource;
use App\Models\Campus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CampusController extends ApiController
{
    protected array $filterable = ['status', 'institution_type', 'city', 'state_province', 'code'];
    protected array $searchable = ['name', 'code', 'city'];
    protected array $sortable = ['id', 'name', 'code', 'created_at'];
    protected array $includable = ['departments', 'programs', 'courses', 'sections', 'batches', 'semesters'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Campus::query(), $request);

        return $this->respondSuccess(
            CampusResource::collection($query->paginate($this->perPage($request))),
            'Campuses retrieved successfully.'
        );
    }

    public function store(StoreCampusRequest $request): JsonResponse
    {
        $campus = Campus::create($request->validated());

        return $this->respondCreated(CampusResource::make($campus), 'Campus created successfully.');
    }

    public function show(Campus $campus): JsonResponse
    {
        return $this->respondSuccess(CampusResource::make($campus), 'Campus retrieved successfully.');
    }

    public function update(UpdateCampusRequest $request, Campus $campus): JsonResponse
    {
        $campus->update($request->validated());

        return $this->respondSuccess(CampusResource::make($campus), 'Campus updated successfully.');
    }

    public function destroy(Campus $campus): JsonResponse
    {
        $campus->delete();

        return $this->respondNoContent('Campus deleted successfully.');
    }
}
