<?php

namespace Tests\Feature;

use App\Models\FeeCategory;
use App\Models\FeeLedgerEntry;
use App\Models\FeePayment;
use App\Models\FeeReceipt;
use App\Models\FeeStructure;
use App\Models\PendingFee;
use App\Models\Student;
use App\Models\StudentFeeAssignment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeeFinancialTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRbac();
    }

    private function superAdmin(): User
    {
        return User::where('email', 'admin@erp.test')->first();
    }

    // --- Fee category ------------------------------------------------------

    public function test_admin_can_manage_fee_categories(): void
    {
        $admin = $this->superAdmin();

        $this->actingAs($admin)->get('/fee-categories')->assertOk()->assertSee('Fee Categories');
        $this->actingAs($admin)->get('/fee-categories/create')->assertOk();

        $this->actingAs($admin)->post('/fee-categories', [
            'name' => 'Tuition Fee', 'code' => 'TUIT', 'fee_type' => 'semester_based',
            'default_amount' => 4200, 'applies_to_university' => '1',
        ])->assertRedirect(route('fee-categories.index'));

        $this->assertDatabaseHas('fee_categories', ['code' => 'TUIT', 'applies_to_university' => true, 'applies_to_school' => false]);
    }

    // --- Fee structure with components ------------------------------------

    public function test_fee_structure_components_set_the_total(): void
    {
        $this->actingAs($this->superAdmin())->post('/fee-structures', [
            'name' => 'BSCS Plan', 'code' => 'BSCS-FEE', 'billing_cycle' => 'Semester',
            'components' => [
                ['name' => 'Tuition', 'amount' => 4200, 'frequency' => 'Semester'],
                ['name' => 'Library', 'amount' => 150, 'frequency' => 'Semester'],
                ['name' => '', 'amount' => 999], // blank row ignored
            ],
        ])->assertRedirect(route('fee-structures.index'));

        $structure = FeeStructure::where('code', 'BSCS-FEE')->firstOrFail();
        $this->assertEqualsWithDelta(4350.0, (float) $structure->total_fee, 0.01);
        $this->assertSame(2, $structure->feeStructureComponents()->count());
    }

    // --- Assignment derives payable/pending -------------------------------

    public function test_assignment_derives_payable_and_pending(): void
    {
        $student = Student::create(['student_code' => 'S-FEE', 'first_name' => 'Fee', 'full_name' => 'Fee Payer']);

        $this->actingAs($this->superAdmin())->post('/student-fee-assignments', [
            'student_id' => $student->id, 'total_fee' => 5000, 'scholarship_amount' => 1000, 'total_paid' => 0,
        ])->assertRedirect();

        $assignment = StudentFeeAssignment::where('student_id', $student->id)->firstOrFail();
        $this->assertEqualsWithDelta(4000.0, (float) $assignment->final_payable, 0.01);
        $this->assertEqualsWithDelta(4000.0, (float) $assignment->total_pending, 0.01);
    }

    // --- Fee collection posts to the ledger (the core rule) ---------------

    public function test_collecting_a_fee_records_payment_receipt_and_ledger(): void
    {
        $admin = $this->superAdmin();
        $student = Student::create(['student_code' => 'S-PAY', 'first_name' => 'Pay', 'full_name' => 'Pay Student']);
        $assignment = StudentFeeAssignment::create([
            'student_id' => $student->id, 'final_payable' => 4500, 'total_paid' => 0, 'total_pending' => 4500, 'status' => 'pending',
        ]);

        $this->actingAs($admin)->get('/fee-payments')->assertOk()->assertSee('Fee Collection');
        $this->actingAs($admin)->get('/fee-payments/create')->assertOk();

        $this->actingAs($admin)->post('/fee-payments', [
            'student_fee_assignment_id' => $assignment->id,
            'amount_payable' => 4500,
            'amount_paid' => 4500,
            'payment_method' => 'cash',
        ])->assertRedirect();

        // Payment, receipt, fee-ledger credit, pending refresh, and a general-ledger entry.
        $this->assertDatabaseCount('fee_payments', 1);
        $this->assertDatabaseCount('fee_receipts', 1);
        $payment = FeePayment::first();
        $this->assertSame('paid', $payment->status);
        $this->assertNotNull($payment->receipt_id);

        $this->assertDatabaseHas('fee_ledger_entries', ['student_id' => $student->id, 'transaction_type' => 'payment', 'credit' => 4500]);
        $this->assertDatabaseHas('ledger_entries', ['source_module' => 'fee-payments']);

        $assignment->refresh();
        $this->assertEqualsWithDelta(4500.0, (float) $assignment->total_paid, 0.01);
        $this->assertEqualsWithDelta(0.0, (float) $assignment->total_pending, 0.01);
        $this->assertSame('paid', $assignment->status);

        $this->assertDatabaseHas('pending_fees', ['student_fee_assignment_id' => $assignment->id, 'amount_pending' => 0]);
    }

    public function test_partial_payment_leaves_a_balance(): void
    {
        $admin = $this->superAdmin();
        $student = Student::create(['student_code' => 'S-PART', 'first_name' => 'P', 'full_name' => 'Partial Payer']);
        $assignment = StudentFeeAssignment::create(['student_id' => $student->id, 'final_payable' => 5000, 'total_pending' => 5000]);

        $this->actingAs($admin)->post('/fee-payments', [
            'student_fee_assignment_id' => $assignment->id, 'amount_payable' => 5000, 'amount_paid' => 2000, 'payment_method' => 'bank',
        ])->assertRedirect();

        $this->assertSame('partial', FeePayment::first()->status);
        $this->assertEqualsWithDelta(3000.0, (float) $assignment->fresh()->total_pending, 0.01);
    }

    // --- Read screens + RBAC ----------------------------------------------

    public function test_read_only_finance_screens_render(): void
    {
        $admin = $this->superAdmin();
        $student = Student::create(['student_code' => 'S-RO', 'first_name' => 'R', 'full_name' => 'Read Only']);
        StudentFeeAssignment::create(['student_id' => $student->id, 'final_payable' => 100, 'total_pending' => 100]);
        FeeReceipt::create(['receipt_number' => 'RC-1', 'student_id' => $student->id, 'total_payable' => 100, 'amount_paid' => 100, 'issued_at' => now()]);
        PendingFee::create(['student_id' => $student->id, 'amount_payable' => 100, 'amount_pending' => 100]);

        $this->actingAs($admin)->get('/fee-receipts')->assertOk();
        $this->actingAs($admin)->get('/fee-receipts/'.FeeReceipt::first()->id)->assertOk()->assertSee('RC-1');
        $this->actingAs($admin)->get('/pending-fees')->assertOk()->assertSee('Pending Fee Management');
        $this->actingAs($admin)->get('/student-fee-ledger')->assertOk();
        $this->actingAs($admin)->get('/student-fee-ledger/'.$student->id)->assertOk()->assertSee('Read Only');
    }

    public function test_non_privileged_user_is_forbidden(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $user->assignRole('librarian');

        $this->actingAs($user)->get('/fee-categories')->assertForbidden();
        $this->actingAs($user)->get('/fee-payments')->assertForbidden();
        $this->actingAs($user)->get('/pending-fees')->assertForbidden();
    }
}
