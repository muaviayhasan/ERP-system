<?php

namespace App\Services\Finance;

use App\Models\SalaryPayment;
use Illuminate\Support\Facades\DB;

/**
 * Persists a salary payment and posts a matching DEBIT to the general ledger
 * (Staff Payroll account) — documentation.md §6.6 / §8.5.
 */
class SalaryPaymentService
{
    public function __construct(private readonly LedgerService $ledger)
    {
    }

    public function create(array $data, ?int $actorId = null): SalaryPayment
    {
        return DB::transaction(function () use ($data, $actorId) {
            $payment = SalaryPayment::create($data);

            $this->ledger->post('Staff Payroll', 'expense', [
                'type' => 'salary',
                'debit' => (float) $payment->net_salary,
                'description' => 'Salary — '.($payment->role_label ?? 'staff').' ('.$payment->payroll_month.')',
                'reference_no' => $payment->transaction_ref ?: null,
                'source_module' => 'salary-payments',
                'created_by' => $actorId,
            ]);

            return $payment;
        });
    }
}
