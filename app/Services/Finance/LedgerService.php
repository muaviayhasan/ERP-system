<?php

namespace App\Services\Finance;

use App\Models\LedgerAccount;
use App\Models\LedgerEntry;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Single posting point for the general ledger — the financial source of truth
 * (documentation.md §6.6). Every financial event (fee payment, expense, income,
 * salary, refund, fine) posts here so the ledger always reconciles.
 *
 * Running balance convention: a signed account balance where debits increase
 * and credits decrease it (`balance += debit - credit`). Each entry stores the
 * previous and adjusted balance for its account so the trail is auditable.
 */
class LedgerService
{
    /**
     * Post a single ledger entry against an account (resolved/created by name).
     *
     * @param  array{type?:string,debit?:float,credit?:float,description?:string,campus_id?:int,student_id?:int,reference_no?:string,invoice_no?:string,source_module?:string,created_by?:int,entry_date?:string}  $data
     */
    public function post(string $accountName, string $accountType, array $data): LedgerEntry
    {
        return DB::transaction(function () use ($accountName, $accountType, $data) {
            $account = LedgerAccount::firstOrCreate(
                ['name' => $accountName],
                ['type' => $accountType, 'is_active' => true, 'campus_id' => $data['campus_id'] ?? null]
            );

            $debit = round((float) ($data['debit'] ?? 0), 2);
            $credit = round((float) ($data['credit'] ?? 0), 2);

            $previous = (float) (LedgerEntry::where('account_id', $account->id)
                ->latest('id')
                ->value('adjusted_balance') ?? 0);
            $adjusted = round($previous + $debit - $credit, 2);

            return LedgerEntry::create([
                'reference_no' => $data['reference_no'] ?? 'LGR-'.now()->format('Ymd').'-'.strtoupper(Str::random(6)),
                'entry_date' => $data['entry_date'] ?? now()->toDateString(),
                'type' => $data['type'] ?? 'other',
                'account_id' => $account->id,
                'debit' => $debit,
                'credit' => $credit,
                'previous_balance' => $previous,
                'adjusted_balance' => $adjusted,
                'status' => 'posted',
                'campus_id' => $data['campus_id'] ?? null,
                'description' => $data['description'] ?? null,
                'student_id' => $data['student_id'] ?? null,
                'invoice_no' => $data['invoice_no'] ?? null,
                'source_module' => $data['source_module'] ?? null,
                'created_by' => $data['created_by'] ?? null,
            ]);
        });
    }
}
