<?php

namespace App\Http\Controllers\Api\V1\Accounting;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Accounting\StoreLedgerAccountRequest;
use App\Http\Requests\Accounting\UpdateLedgerAccountRequest;
use App\Http\Resources\LedgerAccountResource;
use App\Models\LedgerAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LedgerAccountController extends ApiController
{
    protected array $filterable = ['type', 'campus_id', 'is_active'];
    protected array $searchable = ['code', 'name'];
    protected array $sortable = ['id', 'code', 'name', 'type', 'created_at'];
    protected array $includable = ['campus', 'ledgerEntries'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(LedgerAccount::query(), $request);

        return $this->respondSuccess(
            LedgerAccountResource::collection($query->paginate($this->perPage($request))),
            'Ledger accounts retrieved successfully.'
        );
    }

    public function store(StoreLedgerAccountRequest $request): JsonResponse
    {
        $ledgerAccount = LedgerAccount::create($request->validated());

        return $this->respondCreated(LedgerAccountResource::make($ledgerAccount), 'Ledger account created successfully.');
    }

    public function show(LedgerAccount $ledgerAccount): JsonResponse
    {
        $ledgerAccount->load(['campus']);

        return $this->respondSuccess(LedgerAccountResource::make($ledgerAccount), 'Ledger account retrieved successfully.');
    }

    public function update(UpdateLedgerAccountRequest $request, LedgerAccount $ledgerAccount): JsonResponse
    {
        $ledgerAccount->update($request->validated());

        return $this->respondSuccess(LedgerAccountResource::make($ledgerAccount), 'Ledger account updated successfully.');
    }

    public function destroy(LedgerAccount $ledgerAccount): JsonResponse
    {
        $ledgerAccount->delete();

        return $this->respondNoContent('Ledger account deleted successfully.');
    }
}
