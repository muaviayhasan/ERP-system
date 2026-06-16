<?php

namespace App\Services\Academics;

use App\Models\ExamResult;
use App\Models\GradeScale;
use App\Models\StudentGpa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Computes a student's semester GPA and cumulative CGPA from entered exam marks
 * (documentation.md §6.2, §8.3). Grade points come from the configured grade
 * scale; subject credit hours weight the average.
 */
class GpaService
{
    private const DEFAULT_CREDITS = 3.0;

    public function calculate(int $studentId, int $semesterId): StudentGpa
    {
        return DB::transaction(function () use ($studentId, $semesterId) {
            $scales = GradeScale::all();

            $semesterResults = $this->gradedResults($studentId)
                ->filter(fn (ExamResult $r) => optional($r->exam)->semester_id === $semesterId);

            [$gpa, $credits] = $this->weightedAverage($semesterResults, $scales);
            [$cgpa] = $this->weightedAverage($this->gradedResults($studentId), $scales);

            $reference = $semesterResults->first();

            return StudentGpa::updateOrCreate(
                ['student_id' => $studentId, 'semester_id' => $semesterId],
                [
                    'program_id' => optional($reference?->exam)->program_id,
                    'department_id' => optional($reference?->exam)->department_id,
                    'academic_year_id' => optional($reference?->exam)->academic_year_id,
                    'credits' => (int) round($credits),
                    'gpa' => round($gpa, 2),
                    'cgpa' => round($cgpa, 2),
                    'performance_status' => $this->performanceStatus($gpa),
                    'academic_standing' => $cgpa < 2.0 ? 'Probation' : 'Active',
                    'last_calculated_at' => now(),
                ]
            );
        });
    }

    /**
     * @return Collection<int, ExamResult>
     */
    private function gradedResults(int $studentId): Collection
    {
        return ExamResult::with(['exam', 'subject'])
            ->where('student_id', $studentId)
            ->whereNotNull('marks_obtained')
            ->get();
    }

    /**
     * @param  Collection<int, ExamResult>  $results
     * @param  Collection<int, GradeScale>  $scales
     * @return array{0: float, 1: float}  [weightedGpa, totalCredits]
     */
    private function weightedAverage(Collection $results, Collection $scales): array
    {
        $totalPoints = 0.0;
        $totalCredits = 0.0;

        foreach ($results as $result) {
            $percentage = $this->percentage($result);
            $credits = (float) (optional($result->subject)->credits ?? self::DEFAULT_CREDITS);
            $totalPoints += $this->gradePoint($percentage, $scales) * $credits;
            $totalCredits += $credits;
        }

        $gpa = $totalCredits > 0 ? $totalPoints / $totalCredits : 0.0;

        return [$gpa, $totalCredits];
    }

    private function percentage(ExamResult $result): float
    {
        if ($result->percentage !== null) {
            return (float) $result->percentage;
        }

        $total = (float) ($result->total_marks ?: 100);

        return $total > 0 ? (float) $result->marks_obtained / $total * 100 : 0.0;
    }

    /**
     * @param  Collection<int, GradeScale>  $scales
     */
    private function gradePoint(float $percentage, Collection $scales): float
    {
        $match = $scales->first(function (GradeScale $scale) use ($percentage) {
            $min = $scale->min_percent;
            $max = $scale->max_percent;

            return $min !== null && $max !== null && $percentage >= (float) $min && $percentage <= (float) $max;
        });

        if ($match && $match->gpa_point !== null) {
            return (float) $match->gpa_point;
        }

        // Fallback 4.0 scale when no grade band matches.
        return match (true) {
            $percentage >= 80 => 4.0,
            $percentage >= 70 => 3.0,
            $percentage >= 60 => 2.0,
            $percentage >= 50 => 1.0,
            default => 0.0,
        };
    }

    private function performanceStatus(float $gpa): string
    {
        return match (true) {
            $gpa >= 3.5 => 'Excellent',
            $gpa >= 3.0 => 'Good',
            $gpa >= 2.0 => 'Satisfactory',
            default => 'At Risk',
        };
    }
}
