<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\ExamSchedule;
use App\Models\ExamScheduleConflict;
use App\Models\GradeScale;
use App\Models\MarksEntrySession;
use App\Models\ResultCard;
use App\Models\ResultCardLine;
use App\Models\ResultReevaluation;
use App\Models\StudentGpa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::parse('2026-06-16');

        // ---- Grade scales (A/B/C/D/F) ----
        $scales = [
            ['grade' => 'A', 'min_percent' => 80, 'max_percent' => 100, 'min_gpa' => 3.50, 'max_gpa' => 4.00, 'gpa_point' => 4.00, 'is_passing' => true],
            ['grade' => 'B', 'min_percent' => 65, 'max_percent' => 79.99, 'min_gpa' => 3.00, 'max_gpa' => 3.49, 'gpa_point' => 3.00, 'is_passing' => true],
            ['grade' => 'C', 'min_percent' => 50, 'max_percent' => 64.99, 'min_gpa' => 2.00, 'max_gpa' => 2.99, 'gpa_point' => 2.00, 'is_passing' => true],
            ['grade' => 'D', 'min_percent' => 40, 'max_percent' => 49.99, 'min_gpa' => 1.00, 'max_gpa' => 1.99, 'gpa_point' => 1.00, 'is_passing' => true],
            ['grade' => 'F', 'min_percent' => 0, 'max_percent' => 39.99, 'min_gpa' => 0.00, 'max_gpa' => 0.99, 'gpa_point' => 0.00, 'is_passing' => false],
        ];
        foreach ($scales as $s) {
            GradeScale::create($s + ['program_id' => 1]);
        }

        $gradeFor = function (float $pct): array {
            foreach ([
                ['A', 80, 4.00], ['B', 65, 3.00], ['C', 50, 2.00], ['D', 40, 1.00], ['F', 0, 0.00],
            ] as [$g, $min, $gp]) {
                if ($pct >= $min) {
                    return [$g, $gp];
                }
            }
            return ['F', 0.00];
        };

        // ---- 2 Exams ----
        $midterm = Exam::create([
            'name' => 'Spring 2026 Midterm Examination',
            'code' => 'EXM-2026-MID',
            'exam_type' => 'Midterm',
            'scope_label' => 'Program Wide',
            'academic_year_id' => 1,
            'program_id' => 1,
            'department_id' => 1,
            'semester_id' => 1,
            'campus_id' => 1,
            'start_date' => $now->copy()->subDays(30),
            'end_date' => $now->copy()->subDays(24),
            'start_time' => '09:00:00',
            'end_time' => '12:00:00',
            'total_marks' => 100,
            'passing_marks' => 35,
            'is_online' => false,
            'multi_set_papers' => false,
            'status' => 'Completed',
            'result_status' => 'Published',
            'subjects_count' => 5,
            'students_count' => 10,
            'created_by' => 1,
        ]);

        $final = Exam::create([
            'name' => 'Spring 2026 Final Examination',
            'code' => 'EXM-2026-FIN',
            'exam_type' => 'Final',
            'scope_label' => 'Program Wide',
            'academic_year_id' => 1,
            'program_id' => 2,
            'department_id' => 1,
            'semester_id' => 2,
            'campus_id' => 2,
            'start_date' => $now->copy()->addDays(10),
            'end_date' => $now->copy()->addDays(18),
            'start_time' => '09:00:00',
            'end_time' => '12:00:00',
            'total_marks' => 100,
            'passing_marks' => 40,
            'is_online' => false,
            'multi_set_papers' => true,
            'status' => 'Scheduled',
            'result_status' => 'Pending',
            'subjects_count' => 5,
            'students_count' => 10,
            'created_by' => 1,
        ]);

        // ---- Exam schedules (5 subjects per exam) ----
        $venues = ['Hall A', 'Hall B', 'Lab 1', 'Hall C', 'Auditorium'];
        $midSchedules = [];
        foreach (range(1, 5) as $subjectId) {
            $midSchedules[$subjectId] = ExamSchedule::create([
                'exam_id' => $midterm->id,
                'subject_id' => $subjectId,
                'program_id' => 1,
                'class_label' => 'Semester 1',
                'exam_date' => $now->copy()->subDays(30 - ($subjectId - 1)),
                'start_time' => '09:00:00',
                'end_time' => '11:00:00',
                'duration_hours' => 2.00,
                'venue' => $venues[$subjectId - 1],
                'invigilator_id' => $subjectId,
                'exam_type' => 'Midterm',
                'status' => 'Completed',
                'has_conflict' => $subjectId === 3,
                'conflict_severity' => $subjectId === 3 ? 'Warning' : null,
                'conflict_note' => $subjectId === 3 ? 'Lab 1 double-booked with practical session.' : null,
            ]);
        }

        foreach (range(1, 5) as $subjectId) {
            ExamSchedule::create([
                'exam_id' => $final->id,
                'subject_id' => $subjectId,
                'program_id' => 2,
                'class_label' => 'Semester 2',
                'exam_date' => $now->copy()->addDays(10 + ($subjectId - 1)),
                'start_time' => '09:00:00',
                'end_time' => '12:00:00',
                'duration_hours' => 3.00,
                'venue' => $venues[$subjectId - 1],
                'invigilator_id' => (($subjectId) % 5) + 1,
                'exam_type' => 'Final',
                'status' => 'Draft',
                'has_conflict' => false,
            ]);
        }

        // ---- Exam schedule conflict (on the flagged midterm schedule) ----
        ExamScheduleConflict::create([
            'exam_schedule_id' => $midSchedules[3]->id,
            'conflict_type' => 'Venue Overlap',
            'severity' => 'Warning',
            'description' => 'Lab 1 assigned to two concurrent exam sessions.',
            'is_resolved' => false,
        ]);

        // ---- Exam results: students 1..10 across 5 subjects (midterm) ----
        $baseMarks = [78, 55, 92, 41, 67, 33, 88, 72, 49, 61];
        foreach (range(1, 10) as $studentId) {
            foreach (range(1, 5) as $subjectId) {
                $marks = min(100, max(0, $baseMarks[$studentId - 1] + (($subjectId - 3) * 3)));
                $pct = round($marks, 2);
                [$grade] = $gradeFor($pct);
                $isFail = $marks < $midterm->passing_marks;
                ExamResult::create([
                    'exam_id' => $midterm->id,
                    'student_id' => $studentId,
                    'subject_id' => $subjectId,
                    'evaluator_id' => $subjectId,
                    'attendance_status' => 'Present',
                    'marks_obtained' => $marks,
                    'total_marks' => 100,
                    'percentage' => $pct,
                    'grade' => $grade,
                    'remarks' => $isFail ? 'Below passing threshold.' : 'Cleared.',
                    'is_flagged' => $isFail,
                    'validation_error' => null,
                    'entry_status' => 'Approved',
                ]);
            }
        }

        // ---- Marks entry session (1) ----
        MarksEntrySession::create([
            'exam_id' => $midterm->id,
            'subject_id' => 1,
            'evaluator_id' => 1,
            'total_students' => 10,
            'marks_entered_count' => 10,
            'pending_count' => 0,
            'hod_review_required' => true,
            'submitted_for_approval' => true,
            'auto_publish_on_release' => true,
            'highest_mark' => 91.00,
            'average_mark' => 63.60,
            'lowest_mark' => 30.00,
            'last_synced_at' => $now->copy()->subDays(23),
        ]);

        // ---- Student GPAs: students 1..10 ----
        foreach (range(1, 10) as $studentId) {
            $pct = round(min(100, max(0, $baseMarks[$studentId - 1])), 2);
            [$grade, $gpaPoint] = $gradeFor($pct);
            $standing = $gpaPoint >= 3.5 ? 'Dean\'s List'
                : ($gpaPoint >= 2.0 ? 'Good Standing'
                : ($gpaPoint >= 1.0 ? 'Probation' : 'Academic Warning'));
            StudentGpa::create([
                'student_id' => $studentId,
                'program_id' => 1,
                'department_id' => 1,
                'semester_id' => 1,
                'academic_year_id' => 1,
                'credits' => 18,
                'gpa' => $gpaPoint,
                'cgpa' => round(max(0, $gpaPoint - 0.10), 2),
                'performance_status' => $gpaPoint >= 2.0 ? 'Passing' : 'At Risk',
                'academic_standing' => $standing,
                'last_calculated_at' => $now->copy()->subDays(22),
            ]);
        }

        // ---- 2 Result cards + lines (students 1 and 2) ----
        $subjectMeta = [
            1 => ['SUB-101', 'Mathematics'],
            2 => ['SUB-102', 'Physics'],
            3 => ['SUB-103', 'Computer Science'],
            4 => ['SUB-104', 'English'],
            5 => ['SUB-105', 'Chemistry'],
        ];

        foreach ([1, 2] as $idx => $studentId) {
            $pct = round(min(100, max(0, $baseMarks[$studentId - 1])), 2);
            [$overallGrade, $gpaPoint] = $gradeFor($pct);
            $card = ResultCard::create([
                'student_id' => $studentId,
                'exam_id' => $midterm->id,
                'academic_year_id' => 1,
                'class_id' => 1,
                'section_id' => 1,
                'campus_id' => 1,
                'verification_code' => 'RC-2026-' . str_pad((string) $studentId, 4, '0', STR_PAD_LEFT),
                'cumulative_gpa' => $gpaPoint,
                'overall_grade' => $overallGrade,
                'rank_in_class' => $idx + 1,
                'class_size' => 10,
                'result_status' => 'Published',
                'is_published' => true,
                'is_locked' => true,
                'allow_reevaluation' => true,
                'attendance_percent' => 92.50,
                'fee_status' => 'Cleared',
                'class_teacher_id' => 1,
                'registrar_id' => 1,
                'generated_at' => $now->copy()->subDays(20),
            ]);

            foreach (range(1, 5) as $subjectId) {
                $marks = min(100, max(0, $baseMarks[$studentId - 1] + (($subjectId - 3) * 3)));
                [$lineGrade] = $gradeFor((float) $marks);
                [$code, $name] = $subjectMeta[$subjectId];
                ResultCardLine::create([
                    'result_card_id' => $card->id,
                    'subject_id' => $subjectId,
                    'subject_code' => $code,
                    'subject_name' => $name,
                    'max_marks' => 100,
                    'marks_obtained' => $marks,
                    'grade' => $lineGrade,
                    'remarks' => $marks < 40 ? 'Reappear required.' : 'Pass',
                ]);
            }
        }

        // ---- A result reevaluation (student 2) ----
        $reevalCard = ResultCard::where('student_id', 2)->first();
        ResultReevaluation::create([
            'result_card_id' => $reevalCard?->id,
            'student_id' => 2,
            'subject_id' => 2,
            'rechecked_by' => 2,
            'status' => 'Requested',
            'note' => 'Student requested recheck of Physics paper marks.',
            'requested_at' => $now->copy()->subDays(18),
        ]);
    }
}
