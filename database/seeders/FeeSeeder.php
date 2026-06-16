<?php

namespace Database\Seeders;

use App\Models\FeeCategory;
use App\Models\FeeInstallment;
use App\Models\FeeLedgerEntry;
use App\Models\FeePayment;
use App\Models\FeePlan;
use App\Models\FeeReceipt;
use App\Models\FeeReminder;
use App\Models\FeeStructure;
use App\Models\FeeStructureComponent;
use App\Models\PendingFee;
use App\Models\StudentFeeAssignment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class FeeSeeder extends Seeder
{
    public function run(): void
    {
        // ---------------------------------------------------------------
        // 1. Fee categories (4)
        // ---------------------------------------------------------------
        $tuition = FeeCategory::create([
            'name' => 'Tuition Fee',
            'code' => 'FEE-TUITION',
            'code_assignment' => 'auto',
            'description' => 'Core academic tuition fee charged per semester.',
            'fee_type' => 'semester_based',
            'default_amount' => 2500.00,
            'currency' => 'USD',
            'applies_to_school' => true,
            'applies_to_college' => true,
            'applies_to_university' => true,
            'late_fee_enabled' => true,
            'late_fee_type' => 'fixed',
            'late_fee_amount' => 50.00,
            'grace_period_days' => 7,
            'tax_applicable' => false,
            'scholarship_eligible' => true,
            'refundable' => false,
            'auto_generate_on_admission' => true,
            'status' => 'active',
        ]);

        $admission = FeeCategory::create([
            'name' => 'Admission Fee',
            'code' => 'FEE-ADMISSION',
            'code_assignment' => 'auto',
            'description' => 'One-time admission processing fee.',
            'fee_type' => 'one_time',
            'default_amount' => 500.00,
            'currency' => 'USD',
            'applies_to_school' => true,
            'applies_to_college' => true,
            'applies_to_university' => true,
            'late_fee_enabled' => false,
            'grace_period_days' => 0,
            'tax_applicable' => false,
            'scholarship_eligible' => false,
            'refundable' => false,
            'auto_generate_on_admission' => true,
            'status' => 'active',
        ]);

        $library = FeeCategory::create([
            'name' => 'Library Fee',
            'code' => 'FEE-LIBRARY',
            'code_assignment' => 'auto',
            'description' => 'Annual library membership and access fee.',
            'fee_type' => 'annual',
            'default_amount' => 150.00,
            'currency' => 'USD',
            'applies_to_school' => true,
            'applies_to_college' => true,
            'applies_to_university' => true,
            'late_fee_enabled' => false,
            'grace_period_days' => 0,
            'tax_applicable' => false,
            'scholarship_eligible' => false,
            'refundable' => true,
            'auto_generate_on_admission' => false,
            'status' => 'active',
        ]);

        $transport = FeeCategory::create([
            'name' => 'Transport Fee',
            'code' => 'FEE-TRANSPORT',
            'code_assignment' => 'auto',
            'description' => 'Monthly transport / bus service fee.',
            'fee_type' => 'monthly',
            'default_amount' => 120.00,
            'currency' => 'USD',
            'applies_to_school' => true,
            'applies_to_college' => true,
            'applies_to_university' => false,
            'late_fee_enabled' => true,
            'late_fee_type' => 'percentage',
            'late_fee_amount' => 5.00,
            'grace_period_days' => 5,
            'tax_applicable' => true,
            'tax_percentage' => 5.00,
            'scholarship_eligible' => false,
            'refundable' => false,
            'auto_generate_on_admission' => false,
            'status' => 'active',
        ]);

        // ---------------------------------------------------------------
        // 2. Fee structures (2) + components
        // ---------------------------------------------------------------
        $structureA = FeeStructure::create([
            'name' => 'Undergraduate Fee Structure 2026',
            'code' => 'FS-UG-2026',
            'campus_id' => 1,
            'institute_type' => 'University',
            'program_id' => 1,
            'level' => 'Bachelor',
            'academic_year_id' => 1,
            'billing_cycle' => 'semester',
            'total_fee' => 3150.00,
            'scholarship_available' => true,
            'installments_enabled' => true,
            'installment_count' => 3,
            'billing_day_of_month' => 5,
            'status' => 'published',
            'students_count' => 5,
        ]);

        $structureB = FeeStructure::create([
            'name' => 'Postgraduate Fee Structure 2026',
            'code' => 'FS-PG-2026',
            'campus_id' => 2,
            'institute_type' => 'University',
            'program_id' => 2,
            'level' => 'Master',
            'academic_year_id' => 1,
            'billing_cycle' => 'semester',
            'total_fee' => 4000.00,
            'scholarship_available' => true,
            'installments_enabled' => false,
            'installment_count' => null,
            'billing_day_of_month' => 10,
            'status' => 'draft',
            'students_count' => 0,
        ]);

        // Components for structure A
        FeeStructureComponent::create([
            'fee_structure_id' => $structureA->id,
            'fee_category_id' => $tuition->id,
            'name' => 'Tuition Fee',
            'amount' => 2500.00,
            'frequency' => 'semester',
            'taxable' => false,
        ]);
        FeeStructureComponent::create([
            'fee_structure_id' => $structureA->id,
            'fee_category_id' => $admission->id,
            'name' => 'Admission Fee',
            'amount' => 500.00,
            'frequency' => 'one_time',
            'taxable' => false,
        ]);
        FeeStructureComponent::create([
            'fee_structure_id' => $structureA->id,
            'fee_category_id' => $library->id,
            'name' => 'Library Fee',
            'amount' => 150.00,
            'frequency' => 'annual',
            'taxable' => false,
        ]);

        // Components for structure B
        FeeStructureComponent::create([
            'fee_structure_id' => $structureB->id,
            'fee_category_id' => $tuition->id,
            'name' => 'Tuition Fee',
            'amount' => 3500.00,
            'frequency' => 'semester',
            'taxable' => false,
        ]);
        FeeStructureComponent::create([
            'fee_structure_id' => $structureB->id,
            'fee_category_id' => $admission->id,
            'name' => 'Admission Fee',
            'amount' => 500.00,
            'frequency' => 'one_time',
            'taxable' => false,
        ]);

        // ---------------------------------------------------------------
        // 3. Fee plans (3)
        // ---------------------------------------------------------------
        $planInstallments = FeePlan::create([
            'name' => '3-Installment Plan',
            'fee_structure_id' => $structureA->id,
            'schedule_type' => 'installments',
            'number_of_payments' => 3,
            'start_date' => '2026-08-01',
            'status' => 'active',
        ]);

        $planFull = FeePlan::create([
            'name' => 'Full Payment Plan',
            'fee_structure_id' => $structureA->id,
            'schedule_type' => 'full_payment',
            'number_of_payments' => 1,
            'start_date' => '2026-08-01',
            'status' => 'active',
        ]);

        $planLump = FeePlan::create([
            'name' => 'Lump Sum Plan (PG)',
            'fee_structure_id' => $structureB->id,
            'schedule_type' => 'lump_sum',
            'number_of_payments' => 1,
            'start_date' => '2026-09-01',
            'status' => 'active',
        ]);

        // ---------------------------------------------------------------
        // 4. Student fee assignments for students 1..5
        //    + installments, payments, receipts, ledger, pending, reminders
        // ---------------------------------------------------------------
        $receiptCounter = 1001;

        for ($studentId = 1; $studentId <= 5; $studentId++) {
            // Alternate plan: students 1,3,5 -> installments; 2,4 -> full payment
            $useInstallments = ($studentId % 2 === 1);
            $plan = $useInstallments ? $planInstallments : $planFull;

            $totalFee = 3150.00;
            $scholarshipAmount = ($studentId === 1) ? 500.00 : 0.00;
            $finalPayable = $totalFee - $scholarshipAmount;

            $assignment = StudentFeeAssignment::create([
                'student_id' => $studentId,
                'fee_structure_id' => $structureA->id,
                'fee_plan_id' => $plan->id,
                'program_id' => 1,
                'semester_id' => (($studentId - 1) % 3) + 1,
                'campus_id' => 1,
                'academic_year_id' => 1,
                'scholarship_id' => ($studentId === 1) ? 1 : null,
                'scholarship_amount' => $scholarshipAmount,
                'total_fee' => $totalFee,
                'final_payable' => $finalPayable,
                'total_paid' => 0,
                'total_pending' => $finalPayable,
                'next_due_date' => '2026-09-05',
                'late_fee_enabled' => true,
                'email_notifications_enabled' => true,
                'status' => 'pending',
            ]);

            // Build installments
            if ($useInstallments) {
                $installmentDefs = [
                    ['n' => 1, 'label' => 'Installment 1', 'pct' => 40.00, 'due' => '2026-09-05'],
                    ['n' => 2, 'label' => 'Installment 2', 'pct' => 30.00, 'due' => '2026-11-05'],
                    ['n' => 3, 'label' => 'Installment 3', 'pct' => 30.00, 'due' => '2027-01-05'],
                ];
            } else {
                $installmentDefs = [
                    ['n' => 1, 'label' => 'Full Payment', 'pct' => 100.00, 'due' => '2026-09-05'],
                ];
            }

            $installments = [];
            foreach ($installmentDefs as $def) {
                $amount = round($finalPayable * $def['pct'] / 100, 2);
                $installments[] = FeeInstallment::create([
                    'student_fee_assignment_id' => $assignment->id,
                    'installment_number' => $def['n'],
                    'label' => $def['label'],
                    'due_date' => $def['due'],
                    'percentage' => $def['pct'],
                    'amount' => $amount,
                    'amount_paid' => 0,
                    'status' => 'pending',
                    'paid_at' => null,
                ]);
            }

            // Opening ledger entry: fee charged (debit)
            $runningBalance = $finalPayable;
            FeeLedgerEntry::create([
                'student_id' => $studentId,
                'student_fee_assignment_id' => $assignment->id,
                'academic_year_id' => 1,
                'entry_date' => '2026-08-01',
                'reference_number' => 'LED-' . str_pad((string) $studentId, 4, '0', STR_PAD_LEFT) . '-FEE',
                'transaction_type' => 'fee',
                'description' => 'Semester fee charged',
                'debit' => $finalPayable,
                'credit' => 0,
                'balance' => $runningBalance,
                'status' => 'completed',
                'created_by' => 'system',
            ]);

            // Scholarship ledger entry for student 1
            if ($scholarshipAmount > 0) {
                $runningBalance -= $scholarshipAmount;
                FeeLedgerEntry::create([
                    'student_id' => $studentId,
                    'student_fee_assignment_id' => $assignment->id,
                    'academic_year_id' => 1,
                    'entry_date' => '2026-08-02',
                    'reference_number' => 'LED-' . str_pad((string) $studentId, 4, '0', STR_PAD_LEFT) . '-SCH',
                    'transaction_type' => 'scholarship',
                    'description' => 'Merit scholarship discount applied',
                    'debit' => 0,
                    'credit' => $scholarshipAmount,
                    'balance' => $runningBalance,
                    'status' => 'completed',
                    'created_by' => 'system',
                ]);
            }

            // Students 1, 2, 3 make a first payment against installment 1
            $hasPaid = $studentId <= 3;

            if ($hasPaid) {
                $firstInstallment = $installments[0];
                $payAmount = (float) $firstInstallment->amount;

                // Receipt
                $receipt = FeeReceipt::create([
                    'receipt_number' => 'RCPT-2026-' . $receiptCounter,
                    'transaction_id' => 'TXN-' . $receiptCounter,
                    'student_id' => $studentId,
                    'fee_payment_id' => null,
                    'program_id' => 1,
                    'campus_id' => 1,
                    'total_payable' => $payAmount,
                    'amount_paid' => $payAmount,
                    'balance' => 0,
                    'payment_method' => 'bank',
                    'reference_number' => 'BNK-' . $receiptCounter,
                    'collected_by' => 1,
                    'notes' => 'First installment payment received.',
                    'issued_at' => '2026-09-04',
                    'status' => 'paid',
                ]);

                // Payment
                $payment = FeePayment::create([
                    'student_id' => $studentId,
                    'student_fee_assignment_id' => $assignment->id,
                    'fee_installment_id' => $firstInstallment->id,
                    'receipt_id' => $receipt->id,
                    'transaction_id' => 'TXN-' . $receiptCounter,
                    'amount_payable' => $payAmount,
                    'amount_paid' => $payAmount,
                    'balance' => 0,
                    'late_fee_amount' => 0,
                    'payment_method' => 'bank',
                    'reference_number' => 'BNK-' . $receiptCounter,
                    'auto_allocate_installments' => true,
                    'collected_by' => 1,
                    'paid_at' => '2026-09-04 10:30:00',
                    'status' => 'completed',
                ]);

                // Link receipt back to payment
                $receipt->update(['fee_payment_id' => $payment->id]);

                // Mark installment paid
                $firstInstallment->update([
                    'amount_paid' => $payAmount,
                    'status' => 'paid',
                    'paid_at' => '2026-09-04',
                ]);

                // Update assignment totals
                $newPaid = $payAmount;
                $newPending = $finalPayable - $newPaid;
                $assignment->update([
                    'total_paid' => $newPaid,
                    'total_pending' => $newPending,
                    'status' => $newPending > 0 ? 'partial' : 'paid',
                ]);

                // Payment ledger entry (credit)
                $runningBalance -= $payAmount;
                FeeLedgerEntry::create([
                    'student_id' => $studentId,
                    'student_fee_assignment_id' => $assignment->id,
                    'academic_year_id' => 1,
                    'entry_date' => '2026-09-04',
                    'reference_number' => $receipt->receipt_number,
                    'transaction_type' => 'payment',
                    'description' => 'Installment 1 payment received',
                    'debit' => 0,
                    'credit' => $payAmount,
                    'balance' => $runningBalance,
                    'status' => 'completed',
                    'created_by' => 'system',
                ]);

                $receiptCounter++;
            }

            // Pending fee record (remaining balance) for everyone with a balance
            $amountPaidSoFar = $hasPaid ? (float) $installments[0]->amount : 0.00;
            $amountPending = $finalPayable - $amountPaidSoFar;

            if ($amountPending > 0) {
                $dueDate = Carbon::parse('2026-09-05');
                $today = Carbon::parse('2026-06-16');
                $daysOverdue = $today->greaterThan($dueDate) ? $today->diffInDays($dueDate) : 0;

                $pending = PendingFee::create([
                    'student_id' => $studentId,
                    'student_fee_assignment_id' => $assignment->id,
                    'program_id' => 1,
                    'amount_payable' => $finalPayable,
                    'amount_paid' => $amountPaidSoFar,
                    'amount_pending' => $amountPending,
                    'late_fee_amount' => 0,
                    'due_date' => '2026-09-05',
                    'days_overdue' => $daysOverdue,
                    'status' => 'pending',
                ]);

                // Send a reminder for students 4 and 5 (no payment yet)
                if ($studentId >= 4) {
                    FeeReminder::create([
                        'pending_fee_id' => $pending->id,
                        'student_id' => $studentId,
                        'template' => 'fee_due_reminder',
                        'channels' => 'email,sms',
                        'message' => 'Dear student, your semester fee payment is due. Please clear your dues before the due date to avoid late charges.',
                        'sent_by' => 1,
                        'sent_at' => '2026-06-10 09:00:00',
                    ]);
                }
            }
        }

        // ---------------------------------------------------------------
        // Standalone reminder templates (not tied to a specific pending fee)
        // ---------------------------------------------------------------
        FeeReminder::create([
            'pending_fee_id' => null,
            'student_id' => 1,
            'template' => 'final_notice',
            'channels' => 'email',
            'message' => 'Final notice: outstanding fees must be cleared immediately to retain enrollment.',
            'sent_by' => 1,
            'sent_at' => '2026-06-15 12:00:00',
        ]);

        FeeReminder::create([
            'pending_fee_id' => null,
            'student_id' => 2,
            'template' => 'gentle_reminder',
            'channels' => 'sms',
            'message' => 'A friendly reminder that your next installment is approaching its due date.',
            'sent_by' => 1,
            'sent_at' => '2026-06-12 08:30:00',
        ]);
    }
}
