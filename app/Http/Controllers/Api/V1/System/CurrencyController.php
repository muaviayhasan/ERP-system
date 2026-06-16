<?php

namespace App\Http\Controllers\Api\V1\System;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\System\StoreCurrencyRequest;
use App\Http\Requests\System\UpdateCurrencyRequest;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurrencyController extends ApiController
{
    protected array $filterable = ['code', 'is_base'];
    protected array $searchable = ['code', 'name', 'symbol'];
    protected array $sortable = ['id', 'code', 'name', 'created_at'];
    protected array $includable = [];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Currency::query(), $request);

        return $this->respondSuccess(
            CurrencyResource::collection($query->paginate($this->perPage($request))),
            'Currencies retrieved successfully.'
        );
    }

    public function store(StoreCurrencyRequest $request): JsonResponse
    {
        $currency = Currency::create($request->validated());

        return $this->respondCreated(CurrencyResource::make($currency), 'Currency created successfully.');
    }

    public function show(Currency $currency): JsonResponse
    {
        return $this->respondSuccess(CurrencyResource::make($currency), 'Currency retrieved successfully.');
    }

    public function update(UpdateCurrencyRequest $request, Currency $currency): JsonResponse
    {
        $currency->update($request->validated());

        return $this->respondSuccess(CurrencyResource::make($currency), 'Currency updated successfully.');
    }

    public function destroy(Currency $currency): JsonResponse
    {
        $currency->delete();

        return $this->respondNoContent('Currency deleted successfully.');
    }
}
