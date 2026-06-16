<?php

namespace App\Services\Attendance;

use App\Models\Attendance;
use App\Models\AcademicSetting;
use App\Models\LowAttendanceAlert;
use Illuminate\Support\Facades\DB;

/**
 * Records a student attendance entry and keeps the low-attendance alert for
 * that (student, class) in sync: once the attendance rate drops below the
 * institute's required threshold an alert is raised (or updated); when the
 * student recovers above the threshold the alert is cleared.
 */
class AttendanceService
{
    /** Statuses that count as the student having attended. */
    private const ATTENDED = ['present', 'late'];

    public function mark(array $data, ?int $actorId = null): Attendance
    {
        return DB::transaction(function () use ($data, $actorId) {
            $attendance = Attendance::create(array_merge($data, [
                'marked_by' => $data['marked_by'] ?? $actorId,
                'marked_at' => $data['marked_at'] ?? now(),
            ]));

            if ($attendance->student_id && $attendance->class_id) {
                $this->evaluate($attendance->student_id, $attendance->class_id);
            }

            return $attendance;
        });
    }

    /**
     * Recompute the student's attendance percentage for a class and raise,
     * update, or clear the low-attendance alert accordingly.
     */
    public function evaluate(int $studentId, int $classId): ?LowAttendanceAlert
    {
        $rows = Attendance::where('student_id', $studentId)
            ->where('class_id', $classId)
            ->get(['status']);

        $total = $rows->count();
        if ($total === 0) {
            return null;
        }

        $attended = $rows->whereIn('status', self::ATTENDED)->count();
        $absents = $rows->where('status', 'absent')->count();
        $lates = $rows->where('status', 'late')->count();
        $percentage = round($attended / $total * 100, 2);

        $settings = AcademicSetting::query()->latest('id')->first();
        $required = (int) ($settings->min_attendance_required ?? 75);
        $critical = (int) ($settings->attendance_critical_threshold ?? 60);

        // Recovered above the threshold → clear any existing alert.
        if ($percentage >= $required) {
            LowAttendanceAlert::where('student_id', $studentId)->where('class_id', $classId)->delete();

            return null;
        }

        $riskLevel = $percentage < ($critical - 10)
            ? 'critical'
            : ($percentage < $critical ? 'high' : 'moderate');

        return LowAttendanceAlert::updateOrCreate(
            ['student_id' => $studentId, 'class_id' => $classId],
            [
                'attendance_percentage' => $percentage,
                'required_percentage' => $required,
                'risk_level' => $riskLevel,
                'absents_count' => $absents,
                'lates_count' => $lates,
                'exam_eligibility_restricted' => $percentage < $required,
            ]
        );
    }
}
