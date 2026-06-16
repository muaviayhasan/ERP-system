<?php

namespace App\Services\Finance;

use App\Models\Income;
use App\Models\IncomeCategory;
use Illuminate\Support\Facades\DB;

/**
 * Persists an income record and posts a matching CREDIT to the general ledger
 * (documentation.md §6.6).
 */
class IncomeService
{
    public function __construct(private readonly LedgerService $ledger)
    {
    }

    public function create(array $data, ?int $actorId = null): Income
    {
        return DB::transaction(function () use ($data, $actorId) {
            $data['created_by'] ??= $actorId;
            $income = Income::create($data);

            $accountName = $this->accountName($income->category_id);

            $this->ledger->post($accountName, 'income', [
                'type' => 'fee',
                'credit' => (float) $income->amount,
                'description' => $income->title,
                'campus_id' => $income->campus_id,
                'invoice_no' => $income->reference_no,
                'source_module' => 'incomes',
                'created_by' => $actorId,
                'entry_date' => optional($income->income_date)->toDateString(),
            ]);

            return $income;
        });
    }

    private function accountName(?int $categoryId): string
    {
        $category = $categoryId ? IncomeCategory::find($categoryId) : null;

        return $category?->name ? "Income — {$category->name}" : 'General Income';
    }
}
