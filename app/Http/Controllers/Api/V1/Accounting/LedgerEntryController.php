<?php

namespace App\Http\Controllers\Api\V1\Accounting;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Accounting\StoreLedgerEntryRequest;
use App\Http\Requests\Accounting\UpdateLedgerEntryRequest;
use App\Http\Resources\LedgerEntryResource;
use App\Models\LedgerEntry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LedgerEntryController extends ApiController
{
    protected array $filterable = ['status', 'type', 'campus_id', 'account_id', 'student_id', 'source_module'];
    protected array $searchable = ['reference_no', 'invoice_no', 'description'];
    protected array $sortable = ['id', 'reference_no', 'entry_date', 'debit', 'credit', 'created_at'];
    protected array $includable = ['account', 'campus', 'student', 'createdBy', 'audits', 'reconciliations'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(LedgerEntry::query(), $request);

        return $this->respondSuccess(
            LedgerEntryResource::collection($query->paginate($this->perPage($request))),
            'Ledger entries retrieved successfully.'
        );
    }

    public function store(StoreLedgerEntryRequest $request): JsonResponse
    {
        $ledgerEntry = LedgerEntry::create($request->validated());

        return $this->respondCreated(LedgerEntryResource::make($ledgerEntry), 'Ledger entry created successfully.');
    }

    public function show(LedgerEntry $ledgerEntry): JsonResponse
    {
        $ledgerEntry->load(['account', 'campus', 'student']);

        return $this->respondSuccess(LedgerEntryResource::make($ledgerEntry), 'Ledger entry retrieved successfully.');
    }

    public function update(UpdateLedgerEntryRequest $request, LedgerEntry $ledgerEntry): JsonResponse
    {
        $ledgerEntry->update($request->validated());

        return $this->respondSuccess(LedgerEntryResource::make($ledgerEntry), 'Ledger entry updated successfully.');
    }

    public function destroy(LedgerEntry $ledgerEntry): JsonResponse
    {
        $ledgerEntry->delete();

        return $this->respondNoContent('Ledger entry deleted successfully.');
    }
}
