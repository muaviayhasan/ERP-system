<?php

namespace Tests\Feature;

use App\Models\LowAttendanceAlert;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Services\Attendance\AttendanceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceAlertTest extends TestCase
{
    use RefreshDatabase;

    private function studentAndClass(): array
    {
        $student = Student::create(['student_code' => 'STU-1', 'first_name' => 'Test', 'status' => 'active']);
        $class = SchoolClass::create(['name' => 'Grade 10', 'code' => 'G10']);

        return [$student, $class];
    }

    private function mark(int $studentId, int $classId, string $status, string $date): void
    {
        app(AttendanceService::class)->mark([
            'student_id' => $studentId,
            'class_id' => $classId,
            'status' => $status,
            'date' => $date,
        ]);
    }

    public function test_low_attendance_raises_an_alert(): void
    {
        [$student, $class] = $this->studentAndClass();

        // 2 present, 8 absent → 20% attendance.
        for ($i = 1; $i <= 2; $i++) {
            $this->mark($student->id, $class->id, 'present', "2026-03-0{$i}");
        }
        for ($i = 1; $i <= 8; $i++) {
            $this->mark($student->id, $class->id, 'absent', '2026-03-1'.$i);
        }

        $alert = LowAttendanceAlert::where('student_id', $student->id)->where('class_id', $class->id)->first();

        $this->assertNotNull($alert);
        $this->assertEquals(20, (float) $alert->attendance_percentage);
        $this->assertSame('critical', $alert->risk_level);
        $this->assertTrue((bool) $alert->exam_eligibility_restricted);
        $this->assertEquals(8, $alert->absents_count);
    }

    public function test_recovering_above_threshold_clears_the_alert(): void
    {
        [$student, $class] = $this->studentAndClass();

        // Start poor: 1 present, 4 absent → 20% → alert raised.
        $this->mark($student->id, $class->id, 'present', '2026-03-01');
        for ($i = 1; $i <= 4; $i++) {
            $this->mark($student->id, $class->id, 'absent', '2026-03-0'.($i + 1));
        }
        $this->assertDatabaseHas('low_attendance_alerts', ['student_id' => $student->id]);

        // Now attend many sessions to climb above 75%.
        for ($i = 0; $i < 20; $i++) {
            $this->mark($student->id, $class->id, 'present', '2026-04-'.str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT));
        }

        $this->assertDatabaseMissing('low_attendance_alerts', ['student_id' => $student->id]);
    }

    public function test_good_attendance_never_raises_an_alert(): void
    {
        [$student, $class] = $this->studentAndClass();

        for ($i = 1; $i <= 9; $i++) {
            $this->mark($student->id, $class->id, 'present', "2026-03-0{$i}");
        }
        $this->mark($student->id, $class->id, 'absent', '2026-03-10');

        $this->assertDatabaseMissing('low_attendance_alerts', ['student_id' => $student->id]);
    }
}
