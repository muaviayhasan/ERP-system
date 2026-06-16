<?php

namespace App\Http\Controllers\Api\V1\System;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\System\StoreReportRequest;
use App\Http\Requests\System\UpdateReportRequest;
use App\Http\Resources\ReportResource;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends ApiController
{
    protected array $filterable = ['category', 'format', 'generated_by'];
    protected array $searchable = ['name', 'category'];
    protected array $sortable = ['id', 'name', 'category', 'generated_at', 'created_at'];
    protected array $includable = ['generatedBy', 'scheduledReports'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Report::query(), $request);

        return $this->respondSuccess(
            ReportResource::collection($query->paginate($this->perPage($request))),
            'Reports retrieved successfully.'
        );
    }

    public function store(StoreReportRequest $request): JsonResponse
    {
        $report = Report::create($request->validated());

        return $this->respondCreated(ReportResource::make($report), 'Report created successfully.');
    }

    public function show(Report $report): JsonResponse
    {
        $report->load(['generatedBy', 'scheduledReports']);

        return $this->respondSuccess(ReportResource::make($report), 'Report retrieved successfully.');
    }

    public function update(UpdateReportRequest $request, Report $report): JsonResponse
    {
        $report->update($request->validated());

        return $this->respondSuccess(ReportResource::make($report), 'Report updated successfully.');
    }

    public function destroy(Report $report): JsonResponse
    {
        $report->delete();

        return $this->respondNoContent('Report deleted successfully.');
    }
}
