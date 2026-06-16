<?php

namespace App\Services\Fees;

use App\Models\FeeInstallment;
use App\Models\FeeLedgerEntry;
use App\Models\FeePayment;
use App\Models\FeeReceipt;
use App\Models\PendingFee;
use App\Models\StudentFeeAssignment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Records a fee payment as a single atomic financial transaction:
 *   1. persists the payment,
 *   2. issues a receipt,
 *   3. updates the student fee assignment balances + status,
 *   4. updates the installment (if any),
 *   5. posts a CREDIT to the fee ledger (the financial source of truth),
 *   6. refreshes the pending-fee record.
 *
 * Everything happens inside a DB transaction so a failure rolls the whole
 * operation back — the ledger can never drift from the payment.
 */
class FeePaymentService
{
    public function record(array $data, ?int $actorId = null): FeePayment
    {
        return DB::transaction(function () use ($data, $actorId) {
            $assignment = isset($data['student_fee_assignment_id'])
                ? StudentFeeAssignment::lockForUpdate()->find($data['student_fee_assignment_id'])
                : null;

            $amountPaid = round((float) ($data['amount_paid'] ?? 0), 2);
            $payable = round((float) ($data['amount_payable'] ?? ($assignment?->final_payable ?? $amountPaid)), 2);

            // Running balance after this payment.
            $alreadyPaid = (float) ($assignment?->total_paid ?? 0);
            $newTotalPaid = $alreadyPaid + $amountPaid;
            $finalPayable = (float) ($assignment?->final_payable ?? $payable);
            $pending = max($finalPayable - $newTotalPaid, 0);

            $payment = FeePayment::create([
                'student_id' => $data['student_id'] ?? $assignment?->student_id,
                'student_fee_assignment_id' => $assignment?->id,
                'fee_installment_id' => $data['fee_installment_id'] ?? null,
                'transaction_id' => $data['transaction_id'] ?? 'TXN-'.strtoupper(Str::random(10)),
                'amount_payable' => $payable,
                'amount_paid' => $amountPaid,
                'balance' => $pending,
                'late_fee_amount' => $data['late_fee_amount'] ?? 0,
                'payment_method' => $data['payment_method'] ?? 'cash',
                'reference_number' => $data['reference_number'] ?? null,
                'collected_by' => $actorId,
                'paid_at' => $data['paid_at'] ?? now(),
                'status' => $pending <= 0 ? 'paid' : 'partial',
            ]);

            $receipt = $this->issueReceipt($payment, $assignment, $payable, $amountPaid, $pending, $actorId);
            $payment->update(['receipt_id' => $receipt->id]);

            if ($assignment) {
                $assignment->update([
                    'total_paid' => $newTotalPaid,
                    'total_pending' => $pending,
                    'status' => $pending <= 0 ? 'paid' : 'partial',
                ]);

                $this->applyToInstallment($data['fee_installment_id'] ?? null, $amountPaid);
                $this->postLedgerCredit($assignment, $payment, $amountPaid, $pending, $actorId);
                $this->refreshPendingFee($assignment, $pending);
            }

            return $payment->fresh(['receipt']);
        });
    }

    private function issueReceipt(FeePayment $payment, ?StudentFeeAssignment $assignment, float $payable, float $paid, float $balance, ?int $actorId): FeeReceipt
    {
        return FeeReceipt::create([
            'receipt_number' => 'RCPT-'.now()->format('Y').'-'.strtoupper(Str::random(8)),
            'transaction_id' => $payment->transaction_id,
            'student_id' => $payment->student_id,
            'fee_payment_id' => $payment->id,
            'program_id' => $assignment?->program_id,
            'campus_id' => $assignment?->campus_id,
            'total_payable' => $payable,
            'amount_paid' => $paid,
            'balance' => $balance,
            'payment_method' => $payment->payment_method,
            'reference_number' => $payment->reference_number,
            'collected_by' => $actorId,
            'issued_at' => now(),
            'status' => $balance <= 0 ? 'paid' : 'partial',
        ]);
    }

    private function applyToInstallment(?int $installmentId, float $amountPaid): void
    {
        if (! $installmentId) {
            return;
        }

        $installment = FeeInstallment::lockForUpdate()->find($installmentId);
        if (! $installment) {
            return;
        }

        $paid = (float) $installment->amount_paid + $amountPaid;
        $installment->update([
            'amount_paid' => $paid,
            'status' => $paid >= (float) $installment->amount ? 'paid' : 'pending',
            'paid_at' => $paid >= (float) $installment->amount ? now() : $installment->paid_at,
        ]);
    }

    private function postLedgerCredit(StudentFeeAssignment $assignment, FeePayment $payment, float $amountPaid, float $pending, ?int $actorId): void
    {
        FeeLedgerEntry::create([
            'student_id' => $assignment->student_id,
            'student_fee_assignment_id' => $assignment->id,
            'academic_year_id' => $assignment->academic_year_id,
            'entry_date' => now()->toDateString(),
            'reference_number' => $payment->transaction_id,
            'transaction_type' => 'payment',
            'description' => 'Fee payment received (receipt '.$payment->receipt_id.')',
            'debit' => 0,
            'credit' => $amountPaid,
            'balance' => $pending,
            'status' => 'completed',
            'created_by' => $actorId ? (string) $actorId : 'system',
        ]);
    }

    private function refreshPendingFee(StudentFeeAssignment $assignment, float $pending): void
    {
        PendingFee::updateOrCreate(
            ['student_fee_assignment_id' => $assignment->id],
            [
                'student_id' => $assignment->student_id,
                'program_id' => $assignment->program_id,
                'amount_payable' => $assignment->final_payable,
                'amount_paid' => $assignment->total_paid,
                'amount_pending' => $pending,
                'due_date' => $assignment->next_due_date,
                'status' => $pending <= 0 ? 'paid' : 'pending',
            ]
        );
    }
}
