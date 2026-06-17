<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Section;
use App\Models\Student;
use App\Models\Timetable;
use App\Models\TimetableSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicOperationsTest extends TestCase
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

    // --- Timetable ---------------------------------------------------------

    public function test_admin_can_manage_a_timetable_and_its_slots(): void
    {
        $admin = $this->superAdmin();

        $this->actingAs($admin)->get('/timetables')->assertOk()->assertSee('Timetable Management');
        $this->actingAs($admin)->get('/timetables/create')->assertOk();

        $this->actingAs($admin)->post('/timetables', ['name' => 'Fall 2024 CS'])->assertRedirect();
        $timetable = Timetable::where('name', 'Fall 2024 CS')->firstOrFail();

        $this->actingAs($admin)->get("/timetables/{$timetable->id}")->assertOk()->assertSee('Fall 2024 CS');

        // Add a slot.
        $this->actingAs($admin)->post("/timetables/{$timetable->id}/slots", [
            'day_of_week' => 'Monday',
            'start_time' => '10:00',
            'end_time' => '11:30',
            'room' => 'Room 302',
            'slot_type' => 'lab',
        ])->assertRedirect(route('timetables.show', $timetable));

        $slot = TimetableSlot::first();
        $this->assertSame('Monday', $slot->day_of_week);
        $this->assertSame('lab', $slot->slot_type);

        // Edit + delete the slot.
        $this->actingAs($admin)->get("/timetable-slots/{$slot->id}/edit")->assertOk();
        $this->actingAs($admin)->put("/timetable-slots/{$slot->id}", [
            'day_of_week' => 'Tuesday', 'start_time' => '09:00', 'slot_type' => 'lecture',
        ])->assertRedirect();
        $this->assertSame('Tuesday', $slot->fresh()->day_of_week);

        $this->actingAs($admin)->delete("/timetable-slots/{$slot->id}")->assertRedirect();
        $this->assertDatabaseMissing('timetable_slots', ['id' => $slot->id]);
    }

    public function test_slot_requires_day_and_start_time(): void
    {
        $admin = $this->superAdmin();
        $timetable = Timetable::create(['name' => 'T']);

        $this->actingAs($admin)
            ->from(route('timetables.show', $timetable))
            ->post("/timetables/{$timetable->id}/slots", ['room' => 'R1'])
            ->assertSessionHasErrors(['day_of_week', 'start_time']);
    }

    // --- Attendance --------------------------------------------------------

    public function test_admin_can_mark_attendance_for_a_section(): void
    {
        $admin = $this->superAdmin();
        $section = Section::create(['name' => 'Grade 10-A', 'code' => 'G10A']);
        $a = Student::create(['student_code' => 'A-1', 'first_name' => 'Aaron', 'section_id' => $section->id]);
        $b = Student::create(['student_code' => 'B-1', 'first_name' => 'Bella', 'section_id' => $section->id]);

        $this->actingAs($admin)->get('/attendances')->assertOk()->assertSee('Attendance Management');
        $this->actingAs($admin)->get('/attendances/create?section_id='.$section->id)->assertOk()->assertSee('Aaron');

        $this->actingAs($admin)->post('/attendances', [
            'section_id' => $section->id,
            'date' => '2024-10-24',
            'session' => 'morning',
            'statuses' => [$a->id => 'present', $b->id => 'absent'],
        ])->assertRedirect();

        $this->assertDatabaseHas('attendances', ['student_id' => $a->id, 'status' => 'present', 'session' => 'morning']);
        $this->assertDatabaseHas('attendances', ['student_id' => $b->id, 'status' => 'absent']);
    }

    public function test_marking_again_updates_instead_of_duplicating(): void
    {
        $admin = $this->superAdmin();
        $section = Section::create(['name' => 'S', 'code' => 'S1']);
        $student = Student::create(['student_code' => 'X-1', 'first_name' => 'X', 'section_id' => $section->id]);

        $payload = fn ($status) => [
            'section_id' => $section->id, 'date' => '2024-10-24', 'session' => 'morning',
            'statuses' => [$student->id => $status],
        ];

        $this->actingAs($admin)->post('/attendances', $payload('present'))->assertRedirect();
        $this->actingAs($admin)->post('/attendances', $payload('late'))->assertRedirect();

        $this->assertDatabaseCount('attendances', 1);
        $this->assertSame('late', Attendance::first()->status);
    }

    public function test_non_privileged_user_is_forbidden(): void
    {
        $user = $this->withRole('librarian');

        $this->actingAs($user)->get('/timetables')->assertForbidden();
        $this->actingAs($user)->get('/attendances')->assertForbidden();
    }
}
