<?php

namespace Tests\Feature;

use App\Models\Scholarship;
use App\Models\ScholarshipApplication;
use App\Models\ScholarshipAssignment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScholarshipTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRbac();
    }

    private function superAdmin(): User
    {
        return User::where('email', 'admin@erp.test')->first();
    }

    // --- Management --------------------------------------------------------

    public function test_admin_can_manage_scholarship_policies(): void
    {
        $admin = $this->superAdmin();

        $this->actingAs($admin)->get('/scholarships')->assertOk()->assertSee('Scholarship Management');
        $this->actingAs($admin)->get('/scholarships/create')->assertOk();

        $this->actingAs($admin)->post('/scholarships', [
            'name' => 'Academic Excellence 50%', 'code' => 'SCH-MER-01', 'type' => 'merit',
            'value_type' => 'percentage', 'value' => 50,
        ])->assertRedirect(route('scholarships.index'));

        $this->assertDatabaseHas('scholarships', ['code' => 'SCH-MER-01', 'type' => 'merit']);
    }

    public function test_admin_can_assign_a_scholarship_to_a_student(): void
    {
        $admin = $this->superAdmin();
        $student = Student::create(['student_code' => 'S-SCH', 'first_name' => 'A', 'full_name' => 'Aid Student']);
        $scholarship = Scholarship::create(['name' => 'Need Aid', 'code' => 'NEED-1', 'type' => 'need', 'value_type' => 'fixed_amount', 'value' => 2000]);

        $this->actingAs($admin)->get('/scholarship-assignments/create')->assertOk();
        $this->actingAs($admin)->post('/scholarship-assignments', [
            'student_id' => $student->id, 'scholarship_id' => $scholarship->id, 'discount_amount' => 2000,
        ])->assertRedirect(route('scholarships.index'));

        $assignment = ScholarshipAssignment::first();
        $this->assertSame($admin->id, $assignment->assigned_by);
        $this->assertEqualsWithDelta(2000.0, (float) $assignment->discount_amount, 0.01);
    }

    // --- Approval workflow -------------------------------------------------

    public function test_admin_can_view_approval_screens(): void
    {
        $admin = $this->superAdmin();
        $student = Student::create(['student_code' => 'S-AP', 'first_name' => 'B', 'full_name' => 'Apply Student']);
        $app = ScholarshipApplication::create(['student_id' => $student->id, 'type' => 'Need Based', 'application_date' => now(), 'status' => 'pending']);

        $this->actingAs($admin)->get('/scholarship-applications')->assertOk()->assertSee('Scholarship Approval');
        $this->actingAs($admin)->get('/scholarship-applications/create')->assertOk();
        $this->actingAs($admin)->get("/scholarship-applications/{$app->id}")->assertOk()->assertSee('Apply Student');
    }

    public function test_approving_an_application_grants_assignment_and_logs(): void
    {
        $admin = $this->superAdmin();
        $student = Student::create(['student_code' => 'S-OK', 'first_name' => 'C', 'full_name' => 'Ok Student']);
        $scholarship = Scholarship::create(['name' => 'Merit', 'code' => 'MER-1', 'type' => 'merit', 'value_type' => 'percentage', 'value' => 25]);
        $app = ScholarshipApplication::create([
            'student_id' => $student->id, 'scholarship_id' => $scholarship->id, 'type' => 'Merit',
            'original_fee' => 8000, 'requested_value' => 2000, 'application_date' => now(), 'status' => 'pending',
        ]);

        $this->actingAs($admin)->post("/scholarship-applications/{$app->id}/decide", [
            'status' => 'approved', 'decision_notes' => 'Strong candidate',
        ])->assertRedirect(route('scholarship-applications.show', $app));

        $app->refresh();
        $this->assertSame('approved', $app->status);
        $this->assertSame($admin->id, $app->reviewed_by);

        $this->assertDatabaseHas('scholarship_assignments', ['student_id' => $student->id, 'scholarship_id' => $scholarship->id, 'discount_amount' => 2000]);
        $this->assertDatabaseHas('scholarship_application_logs', ['scholarship_application_id' => $app->id, 'action' => 'decision', 'status' => 'approved']);
    }

    public function test_rejecting_does_not_create_an_assignment(): void
    {
        $admin = $this->superAdmin();
        $student = Student::create(['student_code' => 'S-NO', 'first_name' => 'D', 'full_name' => 'No Student']);
        $app = ScholarshipApplication::create(['student_id' => $student->id, 'type' => 'Need', 'application_date' => now(), 'status' => 'pending']);

        $this->actingAs($admin)->post("/scholarship-applications/{$app->id}/decide", ['status' => 'rejected'])->assertRedirect();

        $this->assertSame('rejected', $app->fresh()->status);
        $this->assertDatabaseCount('scholarship_assignments', 0);
    }

    public function test_non_privileged_user_is_forbidden(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $user->assignRole('librarian');

        $this->actingAs($user)->get('/scholarships')->assertForbidden();
        $this->actingAs($user)->get('/scholarship-applications')->assertForbidden();
    }
}
