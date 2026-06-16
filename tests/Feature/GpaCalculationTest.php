<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\GradeScale;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentGpa;
use App\Models\Subject;
use App\Services\Academics\GpaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GpaCalculationTest extends TestCase
{
    use RefreshDatabase;

    private function seedGradeScale(): void
    {
        $bands = [
            ['grade' => 'A', 'min_percent' => 80, 'max_percent' => 100, 'gpa_point' => 4.0],
            ['grade' => 'B', 'min_percent' => 70, 'max_percent' => 79.99, 'gpa_point' => 3.0],
            ['grade' => 'C', 'min_percent' => 60, 'max_percent' => 69.99, 'gpa_point' => 2.0],
            ['grade' => 'F', 'min_percent' => 0, 'max_percent' => 59.99, 'gpa_point' => 0.0],
        ];
        foreach ($bands as $b) {
            GradeScale::create($b + ['is_passing' => $b['gpa_point'] > 0]);
        }
    }

    public function test_it_computes_credit_weighted_gpa_and_cgpa(): void
    {
        $this->seedGradeScale();
        $student = Student::create(['student_code' => 'S1', 'first_name' => 'Ann', 'status' => 'active']);
        $semester = Semester::create(['name' => 'Fall', 'code' => 'F1']);

        $math = Subject::create(['name' => 'Math', 'code' => 'M1', 'credits' => 3]);
        $phys = Subject::create(['name' => 'Physics', 'code' => 'P1', 'credits' => 3]);
        $exam = Exam::create(['name' => 'Final', 'exam_type' => 'Final', 'semester_id' => $semester->id]);

        // 90% (A=4.0) and 70% (B=3.0), equal credits → GPA (4+3)/2 = 3.5.
        ExamResult::create(['exam_id' => $exam->id, 'student_id' => $student->id, 'subject_id' => $math->id, 'marks_obtained' => 90, 'total_marks' => 100]);
        ExamResult::create(['exam_id' => $exam->id, 'student_id' => $student->id, 'subject_id' => $phys->id, 'marks_obtained' => 70, 'total_marks' => 100]);

        $gpa = app(GpaService::class)->calculate($student->id, $semester->id);

        $this->assertEquals(3.5, (float) $gpa->gpa);
        $this->assertEquals(3.5, (float) $gpa->cgpa);
        $this->assertEquals(6, $gpa->credits);
        $this->assertSame('Excellent', $gpa->performance_status);
        $this->assertSame('Active', $gpa->academic_standing);
    }

    public function test_low_marks_put_the_student_on_probation(): void
    {
        $this->seedGradeScale();
        $student = Student::create(['student_code' => 'S2', 'first_name' => 'Bo', 'status' => 'active']);
        $semester = Semester::create(['name' => 'Fall', 'code' => 'F2']);
        $sub = Subject::create(['name' => 'Chem', 'code' => 'C1', 'credits' => 3]);
        $exam = Exam::create(['name' => 'Final', 'exam_type' => 'Final', 'semester_id' => $semester->id]);

        ExamResult::create(['exam_id' => $exam->id, 'student_id' => $student->id, 'subject_id' => $sub->id, 'marks_obtained' => 40, 'total_marks' => 100]);

        $gpa = app(GpaService::class)->calculate($student->id, $semester->id);

        $this->assertEquals(0.0, (float) $gpa->cgpa);
        $this->assertSame('At Risk', $gpa->performance_status);
        $this->assertSame('Probation', $gpa->academic_standing);
    }

    public function test_calculate_endpoint_requires_permission(): void
    {
        $this->seedRbac();
        $this->seedGradeScale();
        $student = Student::create(['student_code' => 'S3', 'first_name' => 'Cy', 'status' => 'active']);
        $semester = Semester::create(['name' => 'Fall', 'code' => 'F3']);
        $sub = Subject::create(['name' => 'Bio', 'code' => 'B1', 'credits' => 3]);
        $exam = Exam::create(['name' => 'Final', 'exam_type' => 'Final', 'semester_id' => $semester->id]);
        ExamResult::create(['exam_id' => $exam->id, 'student_id' => $student->id, 'subject_id' => $sub->id, 'marks_obtained' => 85, 'total_marks' => 100]);

        $payload = ['student_id' => $student->id, 'semester_id' => $semester->id];

        // A student lacks student-gpas.edit → 403.
        $this->actingAsRole('student');
        $this->postJson('/api/v1/student-gpas/calculate', $payload)->assertForbidden();

        // An admin (super-admin bypass) can calculate.
        $this->actingAsRole('admin');
        $this->postJson('/api/v1/student-gpas/calculate', $payload)
            ->assertOk()
            ->assertJsonPath('data.gpa', '4.00');

        $this->assertDatabaseHas('student_gpas', ['student_id' => $student->id, 'semester_id' => $semester->id]);
    }
}
