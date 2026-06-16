<?php

namespace App\Http\Controllers\Api\V1\System;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\System\StoreLanguageRequest;
use App\Http\Requests\System\UpdateLanguageRequest;
use App\Http\Resources\LanguageResource;
use App\Models\Language;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LanguageController extends ApiController
{
    protected array $filterable = ['code', 'is_enabled', 'is_default', 'is_rtl'];
    protected array $searchable = ['name', 'code'];
    protected array $sortable = ['id', 'name', 'code', 'created_at'];
    protected array $includable = [];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Language::query(), $request);

        return $this->respondSuccess(
            LanguageResource::collection($query->paginate($this->perPage($request))),
            'Languages retrieved successfully.'
        );
    }

    public function store(StoreLanguageRequest $request): JsonResponse
    {
        $language = Language::create($request->validated());

        return $this->respondCreated(LanguageResource::make($language), 'Language created successfully.');
    }

    public function show(Language $language): JsonResponse
    {
        return $this->respondSuccess(LanguageResource::make($language), 'Language retrieved successfully.');
    }

    public function update(UpdateLanguageRequest $request, Language $language): JsonResponse
    {
        $language->update($request->validated());

        return $this->respondSuccess(LanguageResource::make($language), 'Language updated successfully.');
    }

    public function destroy(Language $language): JsonResponse
    {
        $language->delete();

        return $this->respondNoContent('Language deleted successfully.');
    }
}
