<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Attendance;
use App\Models\AttendanceAlertRule;
use App\Models\Homework;
use App\Models\HomeworkSubmission;
use App\Models\LowAttendanceAlert;
use App\Models\StudyMaterial;
use App\Models\StudyMaterialFolder;
use App\Models\Timetable;
use App\Models\TimetableSlot;
use Illuminate\Database\Seeder;

class AttendanceAcademicSeeder extends Seeder
{
    public function run(): void
    {
        // --- Attendance alert rules (3 config rows) ---
        $rules = [
            [
                'name' => 'Critical Attendance Drop',
                'description' => 'Triggers when attendance falls below the critical threshold.',
                'trigger_type' => 'percentage_threshold',
                'threshold_percentage' => 60.00,
                'absence_count_trigger' => null,
                'is_enabled' => true,
            ],
            [
                'name' => 'Low Attendance Warning',
                'description' => 'Warns guardians when attendance dips below the required minimum.',
                'trigger_type' => 'percentage_threshold',
                'threshold_percentage' => 75.00,
                'absence_count_trigger' => null,
                'is_enabled' => true,
            ],
            [
                'name' => 'Consecutive Absences',
                'description' => 'Triggers after a number of consecutive or cumulative absences.',
                'trigger_type' => 'absence_count',
                'threshold_percentage' => null,
                'absence_count_trigger' => 5,
                'is_enabled' => true,
            ],
        ];
        foreach ($rules as $rule) {
            AttendanceAlertRule::create($rule);
        }

        // --- Attendances (~10 rows, students 1..10, class/section 1..3) ---
        $statuses = ['present', 'present', 'present', 'absent', 'late', 'present', 'leave', 'present', 'late', 'absent'];
        for ($i = 1; $i <= 10; $i++) {
            $classId = (($i - 1) % 3) + 1;
            Attendance::create([
                'student_id' => $i,
                'class_id' => $classId,
                'section_id' => $classId,
                'subject_id' => (($i - 1) % 5) + 1,
                'teacher_id' => (($i - 1) % 5) + 1,
                'campus_id' => 1,
                'date' => '2026-06-15',
                'session' => 'morning',
                'status' => $statuses[$i - 1],
                'lecture_no' => 'L'.$i,
                'room' => 'Room '.(100 + $classId),
                'start_time' => '08:00:00',
                'end_time' => '08:50:00',
                'remarks' => $statuses[$i - 1] === 'late' ? 'Arrived 10 minutes late' : null,
                'marked_by' => 1,
                'marked_method' => 'manual_web',
                'marked_at' => '2026-06-15 08:05:00',
            ]);
        }

        // --- Low attendance alerts (a few demo rows) ---
        $alerts = [
            [
                'student_id' => 4,
                'class_id' => 1,
                'attendance_percentage' => 58.50,
                'required_percentage' => 75.00,
                'risk_level' => 'critical',
                'absents_count' => 12,
                'lates_count' => 3,
                'trend' => -8.20,
                'scholarship_status' => 'at_risk',
                'exam_eligibility_restricted' => true,
                'sms_warning_sent' => true,
                'guardian_notified' => true,
                'last_warning_sent_at' => '2026-06-14 10:00:00',
            ],
            [
                'student_id' => 7,
                'class_id' => 1,
                'attendance_percentage' => 68.00,
                'required_percentage' => 75.00,
                'risk_level' => 'high',
                'absents_count' => 8,
                'lates_count' => 4,
                'trend' => -3.50,
                'scholarship_status' => 'active',
                'exam_eligibility_restricted' => false,
                'sms_warning_sent' => true,
                'guardian_notified' => false,
                'last_warning_sent_at' => '2026-06-13 09:30:00',
            ],
            [
                'student_id' => 10,
                'class_id' => 1,
                'attendance_percentage' => 72.30,
                'required_percentage' => 75.00,
                'risk_level' => 'moderate',
                'absents_count' => 5,
                'lates_count' => 2,
                'trend' => -1.10,
                'scholarship_status' => 'active',
                'exam_eligibility_restricted' => false,
                'sms_warning_sent' => false,
                'guardian_notified' => false,
                'last_warning_sent_at' => null,
            ],
        ];
        foreach ($alerts as $alert) {
            LowAttendanceAlert::create($alert);
        }

        // --- Assignments (3) + submissions ---
        $assignments = [
            ['title' => 'Algebra Problem Set 1', 'code' => 'ASG-MATH-01', 'subject_id' => 1, 'class_id' => 1, 'section_id' => 1, 'teacher_id' => 1, 'due_date' => '2026-06-20', 'total_marks' => 50, 'expected_submissions' => 30],
            ['title' => 'Essay on Climate Change', 'code' => 'ASG-ENG-02', 'subject_id' => 2, 'class_id' => 2, 'section_id' => 2, 'teacher_id' => 2, 'due_date' => '2026-06-22', 'total_marks' => 40, 'expected_submissions' => 28],
            ['title' => 'Physics Lab Report', 'code' => 'ASG-PHY-03', 'subject_id' => 3, 'class_id' => 3, 'section_id' => 3, 'teacher_id' => 3, 'due_date' => '2026-06-25', 'total_marks' => 60, 'expected_submissions' => 25],
        ];
        foreach ($assignments as $data) {
            $assignment = Assignment::create(array_merge($data, ['description' => $data['title'].' for the current term.', 'status' => 'active']));
            for ($s = 1; $s <= 3; $s++) {
                $graded = $s <= 2;
                AssignmentSubmission::create([
                    'assignment_id' => $assignment->id,
                    'student_id' => $s,
                    'submitted_at' => '2026-06-18 14:30:00',
                    'status' => $graded ? 'graded' : 'submitted',
                    'marks_obtained' => $graded ? (int) round($data['total_marks'] * 0.8) : null,
                    'attachment_path' => 'submissions/assignment_'.$assignment->id.'_student_'.$s.'.pdf',
                    'graded_by' => $graded ? $data['teacher_id'] : null,
                    'graded_at' => $graded ? '2026-06-19 11:00:00' : null,
                ]);
            }
        }

        // --- Homeworks (3) + submissions ---
        $homeworks = [
            ['title' => 'Reading Comprehension Ch. 4', 'code' => 'HW-ENG-01', 'subject_id' => 2, 'class_id' => 1, 'teacher_id' => 2, 'due_date' => '2026-06-18', 'total_marks' => 20],
            ['title' => 'Multiplication Tables Practice', 'code' => 'HW-MATH-02', 'subject_id' => 1, 'class_id' => 2, 'teacher_id' => 1, 'due_date' => '2026-06-19', 'total_marks' => 15],
            ['title' => 'Science Diagram Labelling', 'code' => 'HW-SCI-03', 'subject_id' => 4, 'class_id' => 3, 'teacher_id' => 4, 'due_date' => '2026-06-21', 'total_marks' => 25],
        ];
        foreach ($homeworks as $data) {
            $homework = Homework::create(array_merge($data, ['description' => $data['title'].' to be completed at home.', 'expected_submissions' => 30, 'status' => 'assigned']));
            for ($s = 1; $s <= 3; $s++) {
                $submitted = $s <= 2;
                HomeworkSubmission::create([
                    'homework_id' => $homework->id,
                    'student_id' => $s,
                    'status' => $submitted ? 'submitted' : 'not_submitted',
                    'submitted_at' => $submitted ? '2026-06-17 16:00:00' : null,
                    'file_path' => $submitted ? 'homework/homework_'.$homework->id.'_student_'.$s.'.pdf' : null,
                    'file_type' => $submitted ? 'pdf' : null,
                    'marks_obtained' => $submitted ? (int) round($data['total_marks'] * 0.75) : null,
                    'total_marks' => $data['total_marks'],
                    'graded_by' => $submitted ? $data['teacher_id'] : null,
                ]);
            }
        }

        // --- Study material folder + 4 study materials ---
        $folder = StudyMaterialFolder::create([
            'name' => 'Term 1 Resources',
            'parent_id' => null,
            'subject_id' => 1,
            'class_id' => 1,
            'created_by' => 1,
        ]);

        $materials = [
            ['title' => 'Algebra Lecture Notes', 'type' => 'pdf', 'subject_id' => 1, 'file_path' => 'materials/algebra-notes.pdf', 'external_url' => null, 'file_size' => 1048576],
            ['title' => 'Photosynthesis Explained', 'type' => 'video', 'subject_id' => 4, 'file_path' => null, 'external_url' => 'https://videos.example.com/photosynthesis', 'file_size' => null],
            ['title' => 'Recommended Reading List', 'type' => 'link', 'subject_id' => 2, 'file_path' => null, 'external_url' => 'https://library.example.com/reading-list', 'file_size' => null],
            ['title' => 'Physics Formula Sheet', 'type' => 'doc', 'subject_id' => 3, 'file_path' => 'materials/physics-formulas.docx', 'external_url' => null, 'file_size' => 262144],
        ];
        foreach ($materials as $idx => $data) {
            StudyMaterial::create(array_merge($data, [
                'description' => $data['title'].' shared with the class.',
                'class_id' => 1,
                'folder_id' => $folder->id,
                'uploaded_by' => ($idx % 5) + 1,
                'download_count' => 10 * ($idx + 1),
                'view_count' => 25 * ($idx + 1),
                'is_active' => true,
                'published_at' => '2026-06-10',
            ]));
        }

        // --- Timetable + ~6 slots ---
        $timetable = Timetable::create([
            'name' => 'Week 24 Timetable',
            'campus_id' => 1,
            'program_id' => 1,
            'semester_id' => 1,
            'institute_type' => 'School',
            'week_start_date' => '2026-06-15',
            'week_end_date' => '2026-06-19',
        ]);

        $slots = [
            ['day_of_week' => 'Monday', 'subject_id' => 1, 'teacher_id' => 1, 'period' => 'P1', 'start_time' => '08:00:00', 'end_time' => '08:50:00', 'room' => 'Room 101'],
            ['day_of_week' => 'Monday', 'subject_id' => 2, 'teacher_id' => 2, 'period' => 'P2', 'start_time' => '09:00:00', 'end_time' => '09:50:00', 'room' => 'Room 102'],
            ['day_of_week' => 'Tuesday', 'subject_id' => 3, 'teacher_id' => 3, 'period' => 'P1', 'start_time' => '08:00:00', 'end_time' => '08:50:00', 'room' => 'Lab A'],
            ['day_of_week' => 'Wednesday', 'subject_id' => 4, 'teacher_id' => 4, 'period' => 'P3', 'start_time' => '10:00:00', 'end_time' => '10:50:00', 'room' => 'Room 103'],
            ['day_of_week' => 'Thursday', 'subject_id' => 5, 'teacher_id' => 5, 'period' => 'P2', 'start_time' => '09:00:00', 'end_time' => '09:50:00', 'room' => 'Room 104'],
            ['day_of_week' => 'Friday', 'subject_id' => 1, 'teacher_id' => 1, 'period' => 'P1', 'start_time' => '08:00:00', 'end_time' => '08:50:00', 'room' => 'Room 101'],
        ];
        foreach ($slots as $idx => $slot) {
            TimetableSlot::create(array_merge($slot, [
                'timetable_id' => $timetable->id,
                'section_id' => 1,
                'slot_date' => '2026-06-15',
                'duration_hours' => 0.83,
                'capacity' => 40,
                'slot_type' => 'lecture',
                'has_conflict' => false,
                'conflict_reason' => null,
            ]));
        }
    }
}
