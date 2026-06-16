<?php

namespace Database\Seeders;

use App\Models\PayrollRule;
use App\Models\SalaryPayment;
use App\Models\SalaryStructure;
use App\Models\Staff;
use App\Models\StaffAttendance;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use App\Models\TeacherMetric;
use Illuminate\Database\Seeder;

class HumanResourceSeeder extends Seeder
{
    public function run(): void
    {
        // ----- Teachers -----
        $teachers = [
            ['teacher_code' => 'TCH-001', 'first_name' => 'Ayesha', 'last_name' => 'Khan', 'email' => 'ayesha.khan@school.edu', 'phone' => '+92-300-1112233', 'designation' => 'Professor', 'institute_type' => 'University', 'campus_id' => 1, 'department_id' => 1, 'weekly_workload_hours' => 18.0, 'joining_date' => '2018-08-15'],
            ['teacher_code' => 'TCH-002', 'first_name' => 'Bilal', 'last_name' => 'Ahmed', 'email' => 'bilal.ahmed@school.edu', 'phone' => '+92-300-2223344', 'designation' => 'Associate Professor', 'institute_type' => 'University', 'campus_id' => 1, 'department_id' => 2, 'weekly_workload_hours' => 20.0, 'joining_date' => '2019-09-01'],
            ['teacher_code' => 'TCH-003', 'first_name' => 'Sana', 'last_name' => 'Malik', 'email' => 'sana.malik@school.edu', 'phone' => '+92-300-3334455', 'designation' => 'Assistant Professor', 'institute_type' => 'College', 'campus_id' => 2, 'department_id' => 2, 'weekly_workload_hours' => 22.0, 'joining_date' => '2020-01-20'],
            ['teacher_code' => 'TCH-004', 'first_name' => 'Hamza', 'last_name' => 'Raza', 'email' => 'hamza.raza@school.edu', 'phone' => '+92-300-4445566', 'designation' => 'Lecturer', 'institute_type' => 'College', 'campus_id' => 2, 'department_id' => 3, 'weekly_workload_hours' => 24.0, 'joining_date' => '2021-03-10'],
            ['teacher_code' => 'TCH-005', 'first_name' => 'Fatima', 'last_name' => 'Iqbal', 'email' => 'fatima.iqbal@school.edu', 'phone' => '+92-300-5556677', 'designation' => 'Senior Teacher', 'institute_type' => 'School', 'campus_id' => 3, 'department_id' => 3, 'weekly_workload_hours' => 26.0, 'joining_date' => '2017-07-05'],
        ];

        foreach ($teachers as $i => $data) {
            $data['user_id'] = $i + 1;
            $data['full_name'] = $data['first_name'] . ' ' . $data['last_name'];
            $data['max_workload_hours'] = 40;
            $data['status'] = 'active';
            $teacher = Teacher::create($data);

            // pivot program_teacher (programs 1..3 seeded by core)
            $teacher->programs()->attach((($i % 3) + 1));

            // metrics row per teacher
            TeacherMetric::create([
                'teacher_id' => $teacher->id,
                'classes_count' => 3 + $i,
                'subjects_count' => 2 + ($i % 3),
                'attendance_rate' => 90.00 + $i,
                'student_rating' => 4.10 + ($i * 0.1),
                'research_papers' => $i * 2,
                'mentorship_count' => 1 + $i,
            ]);
        }

        // ----- Teacher assignments (a few) -----
        $assignments = [
            ['teacher_id' => 1, 'institute_type' => 'University', 'department_id' => 1, 'program_id' => 1, 'class_id' => 1, 'subject_id' => 1, 'course_id' => 1, 'section_id' => 1, 'semester_id' => 1, 'credits' => '3', 'lecture_hours' => 3.0, 'lab_hours' => 0.0, 'weekly_hours' => 3.0, 'timetable_status' => 'published', 'status' => 'active'],
            ['teacher_id' => 2, 'institute_type' => 'University', 'department_id' => 2, 'program_id' => 2, 'class_id' => 1, 'subject_id' => 2, 'course_id' => 2, 'section_id' => 1, 'semester_id' => 1, 'credits' => '4', 'lecture_hours' => 3.0, 'lab_hours' => 2.0, 'weekly_hours' => 5.0, 'timetable_status' => 'published', 'status' => 'active'],
            ['teacher_id' => 3, 'institute_type' => 'College', 'department_id' => 2, 'program_id' => 3, 'class_id' => 2, 'subject_id' => 3, 'course_id' => 3, 'section_id' => 2, 'semester_id' => 2, 'credits' => '3', 'lecture_hours' => 3.0, 'lab_hours' => 0.0, 'weekly_hours' => 3.0, 'timetable_status' => 'pending', 'has_conflict' => true, 'conflict_note' => 'Room overlap with TCH-004', 'status' => 'active'],
            ['teacher_id' => 4, 'institute_type' => 'College', 'department_id' => 3, 'program_id' => 1, 'class_id' => 2, 'subject_id' => 4, 'course_id' => 4, 'section_id' => 2, 'semester_id' => 2, 'credits' => '3', 'lecture_hours' => 2.0, 'lab_hours' => 2.0, 'weekly_hours' => 4.0, 'timetable_status' => 'pending', 'status' => 'active'],
        ];
        foreach ($assignments as $a) {
            TeacherAssignment::create($a);
        }

        // ----- Staff -----
        $staffRows = [
            ['staff_code' => 'STF-001', 'first_name' => 'Imran', 'last_name' => 'Sheikh', 'email' => 'imran.sheikh@school.edu', 'phone' => '+92-301-1112233', 'department_id' => 1, 'campus_id' => 1, 'role' => 'Registrar', 'shift' => 'Morning', 'reporting_to_id' => null, 'joining_date' => '2016-05-01'],
            ['staff_code' => 'STF-002', 'first_name' => 'Nadia', 'last_name' => 'Aslam', 'email' => 'nadia.aslam@school.edu', 'phone' => '+92-301-2223344', 'department_id' => 1, 'campus_id' => 1, 'role' => 'Admin Officer', 'shift' => 'Morning', 'reporting_to_id' => 1, 'joining_date' => '2018-06-15'],
            ['staff_code' => 'STF-003', 'first_name' => 'Usman', 'last_name' => 'Tariq', 'email' => 'usman.tariq@school.edu', 'phone' => '+92-301-3334455', 'department_id' => 2, 'campus_id' => 2, 'role' => 'Accountant', 'shift' => 'Morning', 'reporting_to_id' => 1, 'joining_date' => '2019-02-20'],
            ['staff_code' => 'STF-004', 'first_name' => 'Hina', 'last_name' => 'Javed', 'email' => 'hina.javed@school.edu', 'phone' => '+92-301-4445566', 'department_id' => 2, 'campus_id' => 2, 'role' => 'Librarian', 'shift' => 'Evening', 'reporting_to_id' => 2, 'joining_date' => '2020-08-10'],
            ['staff_code' => 'STF-005', 'first_name' => 'Kamran', 'last_name' => 'Butt', 'email' => 'kamran.butt@school.edu', 'phone' => '+92-301-5556677', 'department_id' => 3, 'campus_id' => 3, 'role' => 'Lab Technician', 'shift' => 'Morning', 'reporting_to_id' => 3, 'joining_date' => '2021-11-01'],
        ];
        foreach ($staffRows as $data) {
            $data['full_name'] = $data['first_name'] . ' ' . $data['last_name'];
            $data['status'] = 'active';
            Staff::create($data);
        }

        // ----- Staff attendances -----
        $dates = ['2026-06-15', '2026-06-16'];
        foreach (range(1, 5) as $staffId) {
            foreach ($dates as $d) {
                StaffAttendance::create([
                    'staff_id' => $staffId,
                    'department_id' => $staffRows[$staffId - 1]['department_id'],
                    'campus_id' => $staffRows[$staffId - 1]['campus_id'],
                    'attendance_date' => $d,
                    'shift' => $staffRows[$staffId - 1]['shift'],
                    'check_in' => '09:00:00',
                    'check_out' => '17:00:00',
                    'work_hours' => 8.0,
                    'status' => 'Present',
                    'is_overtime' => false,
                    'needs_correction' => false,
                    'marked_by' => 1,
                ]);
            }
        }

        // ----- Payroll rules -----
        $rules = [
            ['name' => 'Income Tax Slab', 'rule_type' => 'tax', 'description' => 'Progressive income tax deduction', 'config' => ['threshold' => 50000, 'rate' => 0.10], 'is_active' => true],
            ['name' => 'Provident Fund', 'rule_type' => 'deduction', 'description' => 'Monthly PF contribution', 'config' => ['percentage' => 5], 'is_active' => true],
            ['name' => 'Overtime Multiplier', 'rule_type' => 'allowance', 'description' => 'Overtime hourly multiplier', 'config' => ['multiplier' => 1.5], 'is_active' => true],
        ];
        foreach ($rules as $r) {
            PayrollRule::create($r);
        }

        // ----- Salary structures (polymorphic employee) -----
        $struct1 = SalaryStructure::create([
            'employee_type' => Teacher::class,
            'employee_id' => 1,
            'basic_salary' => 120000.00,
            'transport_allowance' => 8000.00,
            'medical_allowance' => 6000.00,
            'housing_allowance' => 20000.00,
            'overtime_rate' => 500.00,
            'performance_bonus' => 15000.00,
            'currency' => 'USD',
            'effective_from' => '2026-01-01',
        ]);
        $struct2 = SalaryStructure::create([
            'employee_type' => Staff::class,
            'employee_id' => 1,
            'basic_salary' => 70000.00,
            'transport_allowance' => 5000.00,
            'medical_allowance' => 4000.00,
            'housing_allowance' => 10000.00,
            'overtime_rate' => 300.00,
            'performance_bonus' => 6000.00,
            'currency' => 'USD',
            'effective_from' => '2026-01-01',
        ]);

        // ----- Salary payments -----
        SalaryPayment::create([
            'employee_type' => Teacher::class,
            'employee_id' => 1,
            'salary_structure_id' => $struct1->id,
            'payroll_month' => '2026-05',
            'role_label' => 'Professor',
            'department_label' => 'Department 1',
            'basic' => 120000.00,
            'allowances' => 34000.00,
            'overtime_bonus' => 0.00,
            'deductions' => 6000.00,
            'tax_deducted' => 12000.00,
            'net_salary' => 136000.00,
            'status' => 'paid',
            'transaction_ref' => 'TRX-2026-05-001',
            'processed_at' => '2026-05-31 16:00:00',
        ]);
        SalaryPayment::create([
            'employee_type' => Teacher::class,
            'employee_id' => 1,
            'salary_structure_id' => $struct1->id,
            'payroll_month' => '2026-06',
            'role_label' => 'Professor',
            'department_label' => 'Department 1',
            'basic' => 120000.00,
            'allowances' => 34000.00,
            'overtime_bonus' => 2500.00,
            'deductions' => 6000.00,
            'tax_deducted' => 12000.00,
            'net_salary' => 138500.00,
            'status' => 'pending',
        ]);
        SalaryPayment::create([
            'employee_type' => Staff::class,
            'employee_id' => 1,
            'salary_structure_id' => $struct2->id,
            'payroll_month' => '2026-05',
            'role_label' => 'Registrar',
            'department_label' => 'Department 1',
            'basic' => 70000.00,
            'allowances' => 19000.00,
            'overtime_bonus' => 0.00,
            'deductions' => 3500.00,
            'tax_deducted' => 7000.00,
            'net_salary' => 78500.00,
            'status' => 'paid',
            'transaction_ref' => 'TRX-2026-05-002',
            'processed_at' => '2026-05-31 16:30:00',
        ]);
    }
}
