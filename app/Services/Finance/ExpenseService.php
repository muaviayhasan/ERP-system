<?php

namespace App\Services\Finance;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\DB;

/**
 * Persists an expense and posts a matching DEBIT to the general ledger so the
 * ledger remains the financial source of truth (documentation.md §6.6).
 */
class ExpenseService
{
    public function __construct(private readonly LedgerService $ledger)
    {
    }

    public function create(array $data, ?int $actorId = null): Expense
    {
        return DB::transaction(function () use ($data, $actorId) {
            $data['created_by'] ??= $actorId;
            $expense = Expense::create($data);

            $accountName = $this->accountName($expense->category_id);

            $this->ledger->post($accountName, 'expense', [
                'type' => 'expense',
                'debit' => (float) $expense->amount,
                'description' => $expense->title,
                'campus_id' => $expense->campus_id,
                'invoice_no' => $expense->reference_no,
                'source_module' => 'expenses',
                'created_by' => $actorId,
                'entry_date' => optional($expense->expense_date)->toDateString(),
            ]);

            return $expense;
        });
    }

    private function accountName(?int $categoryId): string
    {
        $category = $categoryId ? ExpenseCategory::find($categoryId) : null;

        return $category?->name ? "Expense — {$category->name}" : 'General Expenses';
    }
}
