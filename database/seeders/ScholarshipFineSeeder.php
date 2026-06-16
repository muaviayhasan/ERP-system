<?php

namespace Database\Seeders;

use App\Models\Fine;
use App\Models\FineRule;
use App\Models\Refund;
use App\Models\Scholarship;
use App\Models\ScholarshipApplication;
use App\Models\ScholarshipApplicationDocument;
use App\Models\ScholarshipApplicationLog;
use App\Models\ScholarshipAssignment;
use Illuminate\Database\Seeder;

class ScholarshipFineSeeder extends Seeder
{
    public function run(): void
    {
        // ---- Fine Rules ----
        $libraryRule = FineRule::create([
            'name' => 'Library Late Return',
            'type' => 'library',
            'level' => 'All',
            'calculation_method' => 'per_day',
            'amount' => 5.00,
            'grace_period_days' => 2,
            'enable_max_cap' => true,
            'max_cap_amount' => 100.00,
            'status' => 'active',
        ]);

        $disciplineRule = FineRule::create([
            'name' => 'Disciplinary Violation',
            'type' => 'discipline',
            'level' => 'Undergraduate',
            'calculation_method' => 'fixed',
            'amount' => 50.00,
            'grace_period_days' => 0,
            'enable_max_cap' => false,
            'max_cap_amount' => null,
            'status' => 'active',
        ]);

        $attendanceRule = FineRule::create([
            'name' => 'Low Attendance Penalty',
            'type' => 'attendance',
            'level' => 'All',
            'calculation_method' => 'percentage_of_fee',
            'amount' => 2.50,
            'grace_period_days' => 0,
            'enable_max_cap' => true,
            'max_cap_amount' => 250.00,
            'status' => 'active',
        ]);

        // ---- Fines (students 1..5) ----
        Fine::create([
            'student_id' => 1,
            'fine_rule_id' => $libraryRule->id,
            'reason' => 'Returned 6 books 4 days late',
            'amount' => 20.00,
            'date_applied' => '2026-05-10',
            'status' => 'paid',
            'collected_by' => 1,
            'collected_at' => '2026-05-12 10:30:00',
        ]);

        Fine::create([
            'student_id' => 2,
            'fine_rule_id' => $disciplineRule->id,
            'reason' => 'Misconduct during exam hall',
            'amount' => 50.00,
            'date_applied' => '2026-05-15',
            'status' => 'pending',
        ]);

        Fine::create([
            'student_id' => 3,
            'fine_rule_id' => $attendanceRule->id,
            'reason' => 'Attendance below 60% threshold',
            'amount' => 75.00,
            'date_applied' => '2026-04-28',
            'status' => 'overdue',
        ]);

        Fine::create([
            'student_id' => 4,
            'fine_rule_id' => $libraryRule->id,
            'reason' => 'Damaged reference book',
            'amount' => 35.00,
            'date_applied' => '2026-05-20',
            'status' => 'waived',
            'waived_by' => 1,
            'waived_at' => '2026-05-22 09:15:00',
        ]);

        Fine::create([
            'student_id' => 5,
            'fine_rule_id' => $disciplineRule->id,
            'reason' => 'Dress code violation',
            'amount' => 50.00,
            'date_applied' => '2026-06-01',
            'status' => 'pending',
        ]);

        // ---- Refunds ----
        Refund::create([
            'reference_no' => 'REF-2026-0001',
            'student_id' => 1,
            'program_id' => 1,
            'semester_id' => 1,
            'refund_type' => 'overpayment',
            'reason' => 'Duplicate installment payment',
            'description' => 'Student paid the spring installment twice via online gateway.',
            'payment_reference' => 'PAY-88231',
            'total_paid' => 4000.00,
            'actual_due' => 2000.00,
            'max_eligible_refund' => 2000.00,
            'requested_amount' => 2000.00,
            'approved_amount' => 2000.00,
            'payment_verified' => true,
            'ledger_reconciled' => true,
            'status' => 'approved',
            'remarks' => 'Verified against gateway settlement report.',
            'approved_by' => 1,
            'payment_method' => 'bank',
            'payout_date' => '2026-05-25',
            'payout_reference' => 'PAYOUT-5521',
            'request_date' => '2026-05-18',
        ]);

        Refund::create([
            'reference_no' => 'REF-2026-0002',
            'student_id' => 3,
            'program_id' => 2,
            'semester_id' => 1,
            'refund_type' => 'withdrawal',
            'reason' => 'Student withdrew before census date',
            'description' => 'Eligible for 80% refund per withdrawal policy.',
            'payment_reference' => 'PAY-88455',
            'total_paid' => 5000.00,
            'actual_due' => 1000.00,
            'max_eligible_refund' => 4000.00,
            'requested_amount' => 4000.00,
            'approved_amount' => null,
            'payment_verified' => true,
            'ledger_reconciled' => false,
            'status' => 'pending',
            'remarks' => null,
            'payment_method' => null,
            'request_date' => '2026-06-05',
        ]);

        Refund::create([
            'reference_no' => 'REF-2026-0003',
            'student_id' => 4,
            'program_id' => 1,
            'semester_id' => 2,
            'refund_type' => 'course_change',
            'reason' => 'Dropped two credit-hour courses',
            'description' => 'Fee adjustment for reduced credit load.',
            'payment_reference' => 'PAY-88990',
            'total_paid' => 3000.00,
            'actual_due' => 2400.00,
            'max_eligible_refund' => 600.00,
            'requested_amount' => 600.00,
            'approved_amount' => 600.00,
            'payment_verified' => true,
            'ledger_reconciled' => true,
            'status' => 'approved',
            'remarks' => 'Processed in next payout cycle.',
            'approved_by' => 1,
            'payment_method' => 'card',
            'payout_date' => '2026-06-10',
            'payout_reference' => 'PAYOUT-5604',
            'request_date' => '2026-06-02',
        ]);

        // ---- Scholarships ----
        $meritScholarship = Scholarship::create([
            'name' => 'Merit Excellence Award',
            'code' => 'SCH-MERIT',
            'type' => 'merit',
            'value_type' => 'percentage',
            'value' => 50.00,
            'level' => 'Undergraduate',
            'criteria' => 'CGPA >= 3.5 with no disciplinary record.',
            'estimated_liability' => 25000.00,
            'status' => 'active',
        ]);

        $needScholarship = Scholarship::create([
            'name' => 'Need-Based Financial Aid',
            'code' => 'SCH-NEED',
            'type' => 'need',
            'value_type' => 'fixed_amount',
            'value' => 1500.00,
            'level' => 'All',
            'criteria' => 'Household income below threshold; documentation required.',
            'estimated_liability' => 18000.00,
            'status' => 'active',
        ]);

        $sportsScholarship = Scholarship::create([
            'name' => 'Athletic Talent Grant',
            'code' => 'SCH-SPORTS',
            'type' => 'sports',
            'value_type' => 'percentage',
            'value' => 30.00,
            'level' => 'All',
            'criteria' => 'Represented institution at regional/national level.',
            'estimated_liability' => 9000.00,
            'status' => 'active',
        ]);

        // ---- Scholarship Assignments ----
        ScholarshipAssignment::create([
            'student_id' => 1,
            'scholarship_id' => $meritScholarship->id,
            'discount_amount' => 2000.00,
            'status' => 'active',
            'assigned_by' => 1,
            'expires_at' => '2026-12-31',
        ]);

        ScholarshipAssignment::create([
            'student_id' => 2,
            'scholarship_id' => $needScholarship->id,
            'discount_amount' => 1500.00,
            'status' => 'active',
            'assigned_by' => 1,
            'expires_at' => '2026-12-31',
        ]);

        ScholarshipAssignment::create([
            'student_id' => 5,
            'scholarship_id' => $sportsScholarship->id,
            'discount_amount' => 1200.00,
            'status' => 'active',
            'assigned_by' => 1,
            'expires_at' => '2026-12-31',
        ]);

        // ---- Scholarship Applications (+ documents + logs) ----
        $app1 = ScholarshipApplication::create([
            'student_id' => 3,
            'scholarship_id' => $meritScholarship->id,
            'program_id' => 1,
            'semester_id' => 1,
            'institute' => 'University',
            'type' => 'merit',
            'requested_discount_percent' => 50.00,
            'requested_value' => 2000.00,
            'original_fee' => 4000.00,
            'final_payable' => 2000.00,
            'reason' => 'Maintained top of class with CGPA 3.8.',
            'cgpa' => 3.80,
            'documents_count' => 2,
            'gpa_check_passed' => true,
            'policy_compliance_passed' => true,
            'no_duplicate_passed' => true,
            'priority' => 'high',
            'status' => 'under_review',
            'decision_notes' => null,
            'reviewed_by' => 1,
            'application_date' => '2026-05-30',
        ]);

        $app2 = ScholarshipApplication::create([
            'student_id' => 4,
            'scholarship_id' => $needScholarship->id,
            'program_id' => 2,
            'semester_id' => 1,
            'institute' => 'University',
            'type' => 'need',
            'requested_discount_percent' => null,
            'requested_value' => 1500.00,
            'original_fee' => 3500.00,
            'final_payable' => 2000.00,
            'reason' => 'Family financial hardship after parental job loss.',
            'cgpa' => 3.10,
            'documents_count' => 3,
            'gpa_check_passed' => true,
            'policy_compliance_passed' => true,
            'no_duplicate_passed' => true,
            'priority' => 'normal',
            'status' => 'approved',
            'decision_notes' => 'Approved after income verification.',
            'reviewed_by' => 1,
            'application_date' => '2026-05-22',
        ]);

        $app3 = ScholarshipApplication::create([
            'student_id' => 5,
            'scholarship_id' => $sportsScholarship->id,
            'program_id' => 1,
            'semester_id' => 2,
            'institute' => 'University',
            'type' => 'sports',
            'requested_discount_percent' => 30.00,
            'requested_value' => 1200.00,
            'original_fee' => 4000.00,
            'final_payable' => 2800.00,
            'reason' => 'National-level athletics representation.',
            'cgpa' => 2.90,
            'documents_count' => 1,
            'gpa_check_passed' => false,
            'policy_compliance_passed' => true,
            'no_duplicate_passed' => true,
            'priority' => 'normal',
            'status' => 'changes_requested',
            'decision_notes' => 'Please submit official athletic certificate.',
            'reviewed_by' => 1,
            'application_date' => '2026-06-08',
        ]);

        // Documents
        ScholarshipApplicationDocument::create([
            'scholarship_application_id' => $app1->id,
            'file_name' => 'transcript.pdf',
            'file_path' => 'scholarships/3/transcript.pdf',
            'document_type' => 'transcript',
        ]);
        ScholarshipApplicationDocument::create([
            'scholarship_application_id' => $app1->id,
            'file_name' => 'recommendation.pdf',
            'file_path' => 'scholarships/3/recommendation.pdf',
            'document_type' => 'recommendation_letter',
        ]);
        ScholarshipApplicationDocument::create([
            'scholarship_application_id' => $app2->id,
            'file_name' => 'income_certificate.pdf',
            'file_path' => 'scholarships/4/income_certificate.pdf',
            'document_type' => 'income_proof',
        ]);
        ScholarshipApplicationDocument::create([
            'scholarship_application_id' => $app2->id,
            'file_name' => 'utility_bills.pdf',
            'file_path' => 'scholarships/4/utility_bills.pdf',
            'document_type' => 'income_proof',
        ]);
        ScholarshipApplicationDocument::create([
            'scholarship_application_id' => $app2->id,
            'file_name' => 'cnic.pdf',
            'file_path' => 'scholarships/4/cnic.pdf',
            'document_type' => 'identity',
        ]);
        ScholarshipApplicationDocument::create([
            'scholarship_application_id' => $app3->id,
            'file_name' => 'sports_record.pdf',
            'file_path' => 'scholarships/5/sports_record.pdf',
            'document_type' => 'achievement',
        ]);

        // Logs
        ScholarshipApplicationLog::create([
            'scholarship_application_id' => $app1->id,
            'action' => 'submitted',
            'status' => 'pending',
            'performed_by' => 1,
            'performed_at' => '2026-05-30 09:00:00',
        ]);
        ScholarshipApplicationLog::create([
            'scholarship_application_id' => $app1->id,
            'action' => 'moved_to_review',
            'status' => 'under_review',
            'performed_by' => 1,
            'performed_at' => '2026-06-01 11:30:00',
        ]);
        ScholarshipApplicationLog::create([
            'scholarship_application_id' => $app2->id,
            'action' => 'submitted',
            'status' => 'pending',
            'performed_by' => 1,
            'performed_at' => '2026-05-22 08:15:00',
        ]);
        ScholarshipApplicationLog::create([
            'scholarship_application_id' => $app2->id,
            'action' => 'approved',
            'status' => 'approved',
            'performed_by' => 1,
            'performed_at' => '2026-05-28 14:45:00',
        ]);
        ScholarshipApplicationLog::create([
            'scholarship_application_id' => $app3->id,
            'action' => 'changes_requested',
            'status' => 'changes_requested',
            'performed_by' => 1,
            'performed_at' => '2026-06-09 10:00:00',
        ]);
    }
}
