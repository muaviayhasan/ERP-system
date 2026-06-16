<?php

namespace Database\Seeders;

use App\Models\Guardian;
use App\Models\Student;
use App\Models\StudentActivity;
use App\Models\StudentDocument;
use App\Models\StudentPromotion;
use App\Models\StudentPromotionBatch;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            ['first_name' => 'Ali',     'last_name' => 'Khan',     'gender' => 'Male',   'father_name' => 'Imran Khan',       'specialization' => 'Computer Science',  'scholarship_type' => 'merit'],
            ['first_name' => 'Ayesha',  'last_name' => 'Malik',    'gender' => 'Female', 'father_name' => 'Tariq Malik',      'specialization' => 'Electrical Engineering', 'scholarship_type' => null],
            ['first_name' => 'Bilal',   'last_name' => 'Ahmed',    'gender' => 'Male',   'father_name' => 'Nadeem Ahmed',     'specialization' => 'Business Administration', 'scholarship_type' => 'need'],
            ['first_name' => 'Fatima',  'last_name' => 'Sheikh',   'gender' => 'Female', 'father_name' => 'Yousaf Sheikh',    'specialization' => 'Computer Science',  'scholarship_type' => null],
            ['first_name' => 'Hassan',  'last_name' => 'Raza',     'gender' => 'Male',   'father_name' => 'Asif Raza',        'specialization' => 'Mechanical Engineering', 'scholarship_type' => null],
            ['first_name' => 'Iqra',    'last_name' => 'Javed',    'gender' => 'Female', 'father_name' => 'Javed Iqbal',      'specialization' => 'Mathematics',       'scholarship_type' => 'sports'],
            ['first_name' => 'Usman',   'last_name' => 'Farooq',   'gender' => 'Male',   'father_name' => 'Farooq Aziz',      'specialization' => 'Physics',           'scholarship_type' => null],
            ['first_name' => 'Sana',    'last_name' => 'Riaz',     'gender' => 'Female', 'father_name' => 'Riaz Hussain',     'specialization' => 'Computer Science',  'scholarship_type' => 'merit'],
            ['first_name' => 'Zain',    'last_name' => 'Abbas',    'gender' => 'Male',   'father_name' => 'Abbas Ali',        'specialization' => 'Economics',         'scholarship_type' => null],
            ['first_name' => 'Hira',    'last_name' => 'Nawaz',    'gender' => 'Female', 'father_name' => 'Nawaz Sharif',     'specialization' => 'Biology',           'scholarship_type' => 'need'],
        ];

        foreach ($students as $i => $s) {
            $n = $i + 1;
            Student::create([
                'user_id' => $n,
                'student_code' => 'STU-2026-' . str_pad((string) $n, 4, '0', STR_PAD_LEFT),
                'roll_number' => 1000 + $n,
                'first_name' => $s['first_name'],
                'last_name' => $s['last_name'],
                'full_name' => $s['first_name'] . ' ' . $s['last_name'],
                'date_of_birth' => sprintf('200%d-0%d-1%d', ($i % 6) + 2, ($i % 9) + 1, $i % 9),
                'gender' => $s['gender'],
                'cnic' => '35202-' . str_pad((string) (1000000 + $n), 7, '0', STR_PAD_LEFT) . '-' . (($i % 9) + 1),
                'email' => strtolower($s['first_name'] . '.' . $s['last_name']) . '@student.edu.pk',
                'phone' => '+92-300-' . str_pad((string) (1000000 + $n), 7, '0', STR_PAD_LEFT),
                'father_name' => $s['father_name'],
                'photo_url' => null,
                'institute_type' => 'University',
                'campus_id' => ($i % 3) + 1,
                'program_id' => ($i % 3) + 1,
                'academic_year_id' => 1,
                'current_semester_id' => ($i % 3) + 1,
                'section_id' => ($i % 3) + 1,
                'batch_id' => ($i % 3) + 1,
                'advisor_id' => ($i % 5) + 1,
                'specialization' => $s['specialization'],
                'current_credit_hours' => 15 + ($i % 4) * 3,
                'scholarship_type' => $s['scholarship_type'],
                'enrollment_session' => 'Fall 2026',
                'status' => 'active',
                'admission_status' => 'enrolled',
            ]);
        }

        $guardians = [
            ['full_name' => 'Imran Khan',    'relationship' => 'father', 'is_primary_fee_payer' => true,  'phone' => '+92-321-1111111'],
            ['full_name' => 'Tariq Malik',   'relationship' => 'father', 'is_primary_fee_payer' => true,  'phone' => '+92-321-2222222'],
            ['full_name' => 'Saima Ahmed',   'relationship' => 'mother', 'is_primary_fee_payer' => false, 'phone' => '+92-321-3333333'],
            ['full_name' => 'Yousaf Sheikh', 'relationship' => 'father', 'is_primary_fee_payer' => true,  'phone' => '+92-321-4444444'],
            ['full_name' => 'Asif Raza',     'relationship' => 'guardian','is_primary_fee_payer' => false, 'phone' => '+92-321-5555555'],
        ];

        foreach ($guardians as $i => $g) {
            $n = $i + 1;
            Guardian::create([
                'user_id' => null,
                'full_name' => $g['full_name'],
                'relationship' => $g['relationship'],
                'cnic' => '35202-' . str_pad((string) (9000000 + $n), 7, '0', STR_PAD_LEFT) . '-' . $n,
                'phone' => $g['phone'],
                'email' => strtolower(str_replace(' ', '.', $g['full_name'])) . '@guardian.edu.pk',
                'residential_address' => $n . ' Model Town, Lahore',
                'is_primary_fee_payer' => $g['is_primary_fee_payer'],
                'is_emergency_authorized' => true,
                'phone_verified' => $i % 2 === 0,
                'status' => 'active',
            ]);
        }

        // Attach guardian_student links (each guardian -> a student; first is primary)
        $links = [
            ['guardian_id' => 1, 'student_id' => 1, 'relationship' => 'father',   'is_primary' => true],
            ['guardian_id' => 2, 'student_id' => 2, 'relationship' => 'father',   'is_primary' => true],
            ['guardian_id' => 3, 'student_id' => 3, 'relationship' => 'mother',   'is_primary' => true],
            ['guardian_id' => 4, 'student_id' => 4, 'relationship' => 'father',   'is_primary' => true],
            ['guardian_id' => 5, 'student_id' => 5, 'relationship' => 'guardian', 'is_primary' => true],
            ['guardian_id' => 1, 'student_id' => 6, 'relationship' => 'guardian', 'is_primary' => false],
        ];
        foreach ($links as $link) {
            $student = Student::find($link['student_id']);
            $student->guardians()->attach($link['guardian_id'], [
                'relationship' => $link['relationship'],
                'is_primary' => $link['is_primary'],
            ]);
        }

        // Student documents
        $docs = [
            ['student_id' => 1, 'document_type' => 'CNIC',           'title' => 'National ID Card',     'status' => 'verified', 'verified_by' => 1],
            ['student_id' => 1, 'document_type' => 'Transcript',     'title' => 'Previous Transcript',  'status' => 'verified', 'verified_by' => 1],
            ['student_id' => 2, 'document_type' => 'Photograph',     'title' => 'Passport Photo',       'status' => 'pending',  'verified_by' => null],
            ['student_id' => 3, 'document_type' => 'Domicile',       'title' => 'Domicile Certificate', 'status' => 'verified', 'verified_by' => 2],
            ['student_id' => 4, 'document_type' => 'Matric Result',  'title' => 'Matriculation Result', 'status' => 'pending',  'verified_by' => null],
        ];
        foreach ($docs as $i => $d) {
            $n = $i + 1;
            StudentDocument::create([
                'document_code' => 'DOC-2026-' . str_pad((string) $n, 4, '0', STR_PAD_LEFT),
                'student_id' => $d['student_id'],
                'document_type' => $d['document_type'],
                'title' => $d['title'],
                'file_path' => 'documents/students/' . $d['student_id'] . '/doc-' . $n . '.pdf',
                'file_type' => 'application/pdf',
                'status' => $d['status'],
                'uploaded_by' => 'Admissions Office',
                'verification_notes' => $d['status'] === 'verified' ? 'Document verified against original.' : null,
                'verified_by' => $d['verified_by'],
                'verified_at' => $d['status'] === 'verified' ? '2026-02-15 10:30:00' : null,
                'issue_date' => '2024-08-01',
                'expiry_date' => null,
                'uploaded_at' => '2026-02-10 09:00:00',
            ]);
        }

        // Promotion batch + promotions
        $batch = StudentPromotionBatch::create([
            'from_academic_year_id' => 1,
            'to_academic_year_id' => 1,
            'source_program_id' => 1,
            'to_program_id' => 1,
            'to_section_id' => 2,
            'to_campus_id' => 1,
            'min_attendance_rule' => true,
            'min_attendance_threshold' => 75,
            'clear_fee_arrears_rule' => true,
            'manual_override_allowed' => false,
            'fee_adjustment' => 'carry_forward',
            'total_students' => 3,
            'passed_count' => 2,
            'failed_count' => 0,
            'conditional_count' => 1,
            'status' => 'executed',
            'executed_by' => 1,
            'executed_at' => '2026-06-01 12:00:00',
        ]);

        $promotions = [
            ['student_id' => 1, 'attendance_percentage' => 92.50, 'result_status' => 'passed',      'gpa' => 3.80, 'fee_status' => 'cleared', 'eligibility' => 'eligible',    'promoted' => true],
            ['student_id' => 2, 'attendance_percentage' => 88.00, 'result_status' => 'passed',      'gpa' => 3.40, 'fee_status' => 'cleared', 'eligibility' => 'eligible',    'promoted' => true],
            ['student_id' => 3, 'attendance_percentage' => 71.00, 'result_status' => 'conditional', 'gpa' => 2.10, 'fee_status' => 'pending', 'eligibility' => 'conditional', 'promoted' => false],
        ];
        foreach ($promotions as $p) {
            StudentPromotion::create([
                'student_id' => $p['student_id'],
                'promotion_batch_id' => $batch->id,
                'from_academic_year_id' => 1,
                'to_academic_year_id' => 1,
                'from_semester_id' => 1,
                'to_semester_id' => 2,
                'to_program_id' => 1,
                'to_section_id' => 2,
                'to_batch_id' => 1,
                'to_campus_id' => 1,
                'attendance_percentage' => $p['attendance_percentage'],
                'result_status' => $p['result_status'],
                'result_detail' => ucfirst($p['result_status']) . ' with GPA ' . $p['gpa'],
                'gpa' => $p['gpa'],
                'fee_status' => $p['fee_status'],
                'fee_due_amount' => $p['fee_status'] === 'pending' ? 25000.00 : 0.00,
                'eligibility' => $p['eligibility'],
                'fee_adjustment' => 'carry_forward',
                'manual_override' => false,
                'promoted' => $p['promoted'],
                'promoted_by' => $p['promoted'] ? 1 : null,
                'promoted_at' => $p['promoted'] ? '2026-06-01 12:05:00' : null,
            ]);
        }

        // Student activities
        $activities = [
            ['student_id' => 1, 'activity_type' => 'sports',    'title' => 'Inter-University Cricket', 'activity_date' => '2026-03-12'],
            ['student_id' => 2, 'activity_type' => 'academic',  'title' => 'Dean\'s Honor List',       'activity_date' => '2026-02-20'],
            ['student_id' => 3, 'activity_type' => 'workshop',  'title' => 'AI & ML Bootcamp',         'activity_date' => '2026-04-05'],
            ['student_id' => 6, 'activity_type' => 'society',   'title' => 'Debating Society Lead',    'activity_date' => '2026-01-18'],
        ];
        foreach ($activities as $a) {
            StudentActivity::create([
                'student_id' => $a['student_id'],
                'activity_type' => $a['activity_type'],
                'title' => $a['title'],
                'description' => $a['title'] . ' participation recorded for the student.',
                'activity_date' => $a['activity_date'],
            ]);
        }
    }
}
