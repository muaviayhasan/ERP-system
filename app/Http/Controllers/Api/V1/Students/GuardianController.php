<?php

namespace App\Http\Controllers\Api\V1\Students;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Student\StoreGuardianRequest;
use App\Http\Requests\Student\UpdateGuardianRequest;
use App\Http\Resources\GuardianResource;
use App\Models\Guardian;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GuardianController extends ApiController
{
    protected array $filterable = ['status', 'relationship', 'is_primary_fee_payer'];
    protected array $searchable = ['full_name', 'cnic', 'phone', 'email'];
    protected array $sortable = ['id', 'full_name', 'created_at'];
    protected array $includable = ['students'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Guardian::query(), $request);

        return $this->respondSuccess(
            GuardianResource::collection($query->paginate($this->perPage($request))),
            'Guardians retrieved successfully.'
        );
    }

    public function store(StoreGuardianRequest $request): JsonResponse
    {
        $guardian = Guardian::create($request->validated());

        return $this->respondCreated(GuardianResource::make($guardian), 'Guardian created successfully.');
    }

    public function show(Guardian $guardian): JsonResponse
    {
        $guardian->load('students');

        return $this->respondSuccess(GuardianResource::make($guardian), 'Guardian retrieved successfully.');
    }

    public function update(UpdateGuardianRequest $request, Guardian $guardian): JsonResponse
    {
        $guardian->update($request->validated());

        return $this->respondSuccess(GuardianResource::make($guardian), 'Guardian updated successfully.');
    }

    public function destroy(Guardian $guardian): JsonResponse
    {
        $guardian->delete();

        return $this->respondNoContent('Guardian deleted successfully.');
    }
}
