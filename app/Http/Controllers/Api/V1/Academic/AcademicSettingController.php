<?php

namespace App\Http\Controllers\Api\V1\Academic;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Academic\StoreAcademicSettingRequest;
use App\Http\Requests\Academic\UpdateAcademicSettingRequest;
use App\Http\Resources\AcademicSettingResource;
use App\Models\AcademicSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AcademicSettingController extends ApiController
{
    protected array $filterable = ['academic_year_id', 'grading_system', 'exam_structure', 'promotion_enabled', 'university_mode_enabled'];
    protected array $searchable = ['grading_system', 'exam_structure'];
    protected array $sortable = ['id', 'created_at'];
    protected array $includable = ['academicYear'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(AcademicSetting::query(), $request);

        return $this->respondSuccess(
            AcademicSettingResource::collection($query->paginate($this->perPage($request))),
            'Academic settings retrieved successfully.'
        );
    }

    public function store(StoreAcademicSettingRequest $request): JsonResponse
    {
        $academicSetting = AcademicSetting::create($request->validated());

        return $this->respondCreated(AcademicSettingResource::make($academicSetting), 'Academic setting created successfully.');
    }

    public function show(AcademicSetting $academicSetting): JsonResponse
    {
        return $this->respondSuccess(AcademicSettingResource::make($academicSetting), 'Academic setting retrieved successfully.');
    }

    public function update(UpdateAcademicSettingRequest $request, AcademicSetting $academicSetting): JsonResponse
    {
        $academicSetting->update($request->validated());

        return $this->respondSuccess(AcademicSettingResource::make($academicSetting), 'Academic setting updated successfully.');
    }

    public function destroy(AcademicSetting $academicSetting): JsonResponse
    {
        $academicSetting->delete();

        return $this->respondNoContent('Academic setting deleted successfully.');
    }
}
