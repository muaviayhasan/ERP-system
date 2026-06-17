<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Program;
use App\Models\Staff;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FacultyStaffManagementTest extends TestCase
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

    private function withRole(string $role): User
    {
        $user = User::factory()->create(['status' => 'active']);
        $user->assignRole($role);

        return $user;
    }

    // --- Teachers ----------------------------------------------------------

    public function test_admin_can_view_teacher_pages(): void
    {
        $admin = $this->superAdmin();
        $teacher = Teacher::create([
            'teacher_code' => 'TCH-1', 'first_name' => 'Sarah', 'last_name' => 'Jenkins',
            'full_name' => 'Sarah Jenkins', 'email' => 's@erp.test', 'designation' => 'Professor',
        ]);

        $this->actingAs($admin)->get('/teachers')->assertOk()->assertSee('Teacher Management');
        $this->actingAs($admin)->get('/teachers/create')->assertOk();
        $this->actingAs($admin)->get("/teachers/{$teacher->id}")->assertOk()->assertSee('Sarah Jenkins');
        $this->actingAs($admin)->get("/teachers/{$teacher->id}/edit")->assertOk();
    }

    public function test_admin_can_create_a_teacher_with_programs_and_photo(): void
    {
        Storage::fake('public');
        $program = Program::create(['name' => 'BS CS', 'code' => 'BSCS-T']);

        $this->actingAs($this->superAdmin())->post('/teachers', [
            'teacher_code' => 'TCH-2024-042',
            'first_name' => 'Sarah',
            'last_name' => 'Jenkins',
            'email' => 'sarah@erp.test',
            'designation' => 'Professor',
            'programs' => [$program->id],
            'photo' => UploadedFile::fake()->image('t.jpg'),
        ])->assertRedirect();

        $teacher = Teacher::where('teacher_code', 'TCH-2024-042')->first();
        $this->assertSame('Sarah Jenkins', $teacher->full_name);
        $this->assertEqualsCanonicalizing([$program->id], $teacher->programs->pluck('id')->all());
        Storage::disk('public')->assertExists($teacher->photo_url);
    }

    public function test_teacher_code_is_unique(): void
    {
        Teacher::create(['teacher_code' => 'DUP-T', 'first_name' => 'A', 'last_name' => 'B', 'email' => 'a@erp.test', 'designation' => 'Lecturer']);

        $this->actingAs($this->superAdmin())
            ->from('/teachers/create')
            ->post('/teachers', ['teacher_code' => 'DUP-T', 'first_name' => 'C', 'last_name' => 'D', 'email' => 'c@erp.test', 'designation' => 'Lecturer'])
            ->assertSessionHasErrors('teacher_code');
    }

    // --- Teacher assignments ----------------------------------------------

    public function test_admin_can_create_and_update_an_assignment(): void
    {
        $admin = $this->superAdmin();
        $teacher = Teacher::create(['teacher_code' => 'TCH-AS', 'first_name' => 'X', 'last_name' => 'Y', 'email' => 'x@erp.test', 'designation' => 'Lecturer']);

        $this->actingAs($admin)->get('/teacher-assignments')->assertOk()->assertSee('Teacher Assignment');
        $this->actingAs($admin)->get('/teacher-assignments/create')->assertOk();

        $this->actingAs($admin)->post('/teacher-assignments', [
            'teacher_id' => $teacher->id,
            'weekly_hours' => 12,
            'timetable_status' => 'scheduled',
            'has_conflict' => '1',
        ])->assertRedirect(route('teacher-assignments.index'));

        $assignment = TeacherAssignment::first();
        $this->assertSame('scheduled', $assignment->timetable_status);
        $this->assertTrue($assignment->has_conflict);

        $this->actingAs($admin)->put("/teacher-assignments/{$assignment->id}", [
            'teacher_id' => $teacher->id, 'timetable_status' => 'published',
            // has_conflict omitted => false
        ])->assertRedirect();
        $this->assertFalse($assignment->fresh()->has_conflict);
        $this->assertSame('published', $assignment->fresh()->timetable_status);
    }

    // --- Staff -------------------------------------------------------------

    public function test_admin_can_manage_staff(): void
    {
        $admin = $this->superAdmin();

        $this->actingAs($admin)->get('/staff')->assertOk()->assertSee('Staff Management');
        $this->actingAs($admin)->get('/staff/create')->assertOk();

        $this->actingAs($admin)->post('/staff', [
            'staff_code' => 'EMP-2024-001',
            'first_name' => 'Michael',
            'last_name' => 'Scott',
            'role' => 'IT Manager',
            'shift' => 'Morning',
        ])->assertRedirect(route('staff.index'));

        $member = Staff::where('staff_code', 'EMP-2024-001')->first();
        $this->assertSame('Michael Scott', $member->full_name);

        $this->actingAs($admin)->put("/staff/{$member->id}", [
            'staff_code' => 'EMP-2024-001', 'first_name' => 'Mike', 'last_name' => 'Scott', 'role' => 'IT Manager', 'status' => 'inactive',
        ])->assertRedirect();
        $this->assertSame('inactive', $member->fresh()->status);

        $this->actingAs($admin)->delete("/staff/{$member->id}")->assertRedirect();
        $this->assertDatabaseMissing('staff', ['id' => $member->id]);
    }

    public function test_staff_code_is_unique(): void
    {
        Staff::create(['staff_code' => 'DUP-S', 'first_name' => 'A', 'last_name' => 'B', 'role' => 'Clerk']);

        $this->actingAs($this->superAdmin())
            ->from('/staff/create')
            ->post('/staff', ['staff_code' => 'DUP-S', 'first_name' => 'C', 'last_name' => 'D', 'role' => 'Clerk'])
            ->assertSessionHasErrors('staff_code');
    }

    // --- RBAC --------------------------------------------------------------

    public function test_non_privileged_user_is_forbidden(): void
    {
        $user = $this->withRole('librarian');

        $this->actingAs($user)->get('/teachers')->assertForbidden();
        $this->actingAs($user)->get('/teacher-assignments')->assertForbidden();
        $this->actingAs($user)->get('/staff')->assertForbidden();
    }
}
