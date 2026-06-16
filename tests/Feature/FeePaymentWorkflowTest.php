<?php

namespace Tests\Feature;

use App\Models\FeeLedgerEntry;
use App\Models\PendingFee;
use App\Models\Student;
use App\Models\StudentFeeAssignment;
use App\Services\Fees\FeePaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeePaymentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private function assignment(): StudentFeeAssignment
    {
        $student = Student::create(['student_code' => 'STU-1', 'first_name' => 'Test', 'status' => 'active']);

        return StudentFeeAssignment::create([
            'student_id' => $student->id,
            'total_fee' => 1000,
            'final_payable' => 1000,
            'total_paid' => 0,
            'total_pending' => 1000,
            'status' => 'pending',
        ]);
    }

    public function test_partial_payment_updates_assignment_receipt_ledger_and_pending(): void
    {
        $assignment = $this->assignment();

        $payment = app(FeePaymentService::class)->record([
            'student_fee_assignment_id' => $assignment->id,
            'amount_paid' => 400,
            'payment_method' => 'cash',
        ]);

        // Payment + receipt.
        $this->assertSame('partial', $payment->status);
        $this->assertEquals(600, (float) $payment->balance);
        $this->assertNotNull($payment->receipt_id);

        // Assignment balances.
        $assignment->refresh();
        $this->assertEquals(400, (float) $assignment->total_paid);
        $this->assertEquals(600, (float) $assignment->total_pending);
        $this->assertSame('partial', $assignment->status);

        // Ledger credit (the financial source of truth).
        $ledger = FeeLedgerEntry::where('student_fee_assignment_id', $assignment->id)->first();
        $this->assertNotNull($ledger);
        $this->assertEquals(400, (float) $ledger->credit);
        $this->assertSame('payment', $ledger->transaction_type);

        // Pending fee snapshot.
        $pending = PendingFee::where('student_fee_assignment_id', $assignment->id)->first();
        $this->assertEquals(600, (float) $pending->amount_pending);
    }

    public function test_full_settlement_marks_assignment_paid(): void
    {
        $assignment = $this->assignment();
        $service = app(FeePaymentService::class);

        $service->record(['student_fee_assignment_id' => $assignment->id, 'amount_paid' => 400]);
        $service->record(['student_fee_assignment_id' => $assignment->id, 'amount_paid' => 600]);

        $assignment->refresh();
        $this->assertEquals(1000, (float) $assignment->total_paid);
        $this->assertEquals(0, (float) $assignment->total_pending);
        $this->assertSame('paid', $assignment->status);

        // Two payments → two ledger credits totalling the full amount.
        $credits = FeeLedgerEntry::where('student_fee_assignment_id', $assignment->id)->sum('credit');
        $this->assertEquals(1000, (float) $credits);
    }

    public function test_payment_endpoint_records_through_the_service(): void
    {
        $this->seedRbac();
        $assignment = $this->assignment();
        $this->actingAsRole('accountant');

        $this->postJson('/api/v1/fee-payments', [
            'student_id' => $assignment->student_id,
            'student_fee_assignment_id' => $assignment->id,
            'amount_payable' => 1000,
            'amount_paid' => 250,
            'payment_method' => 'card',
        ])->assertCreated();

        $this->assertDatabaseHas('fee_receipts', ['student_id' => $assignment->student_id]);
        $this->assertDatabaseHas('fee_ledger_entries', ['student_fee_assignment_id' => $assignment->id, 'credit' => 250]);
    }
}
