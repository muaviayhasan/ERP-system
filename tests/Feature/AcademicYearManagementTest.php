<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Campus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicYearManagementTest extends TestCase
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

    public function test_admin_can_view_academic_year_pages(): void
    {
        $admin = $this->superAdmin();
        $year = AcademicYear::create([
            'name' => '2024-2025', 'start_date' => '2024-06-01', 'end_date' => '2025-05-31', 'status' => 'active',
        ]);

        $this->actingAs($admin)->get('/academic-years')->assertOk()->assertSee('Academic Year Management');
        $this->actingAs($admin)->get('/academic-years/create')->assertOk();
        $this->actingAs($admin)->get("/academic-years/{$year->id}/edit")->assertOk()->assertSee('2024-2025');
    }

    public function test_admin_can_create_an_institution_wide_year(): void
    {
        $this->actingAs($this->superAdmin())
            ->post('/academic-years', [
                'name' => '2026-2027',
                'start_date' => '2026-06-01',
                'end_date' => '2027-05-31',
                'scope' => 'all_campuses',
                'status' => 'upcoming',
                'link_fee_structure' => '1',
                // prevent_date_overlap omitted => false, so no overlap check
            ])
            ->assertRedirect(route('academic-years.index'));

        $this->assertDatabaseHas('academic_years', [
            'name' => '2026-2027',
            'scope' => 'all_campuses',
            'link_fee_structure' => true,
            'auto_roll_attendance' => false,
        ]);
    }

    public function test_specific_scope_syncs_campuses(): void
    {
        $a = Campus::create(['name' => 'Campus A', 'code' => 'A-1']);
        $b = Campus::create(['name' => 'Campus B', 'code' => 'B-1']);

        $this->actingAs($this->superAdmin())
            ->post('/academic-years', [
                'name' => '2027-2028',
                'start_date' => '2027-06-01',
                'end_date' => '2028-05-31',
                'scope' => 'specific_campuses',
                'campuses' => [$a->id, $b->id],
            ])
            ->assertRedirect(route('academic-years.index'));

        $year = AcademicYear::where('name', '2027-2028')->first();
        $this->assertEqualsCanonicalizing([$a->id, $b->id], $year->campuses->pluck('id')->all());
    }

    public function test_prevent_date_overlap_blocks_a_clashing_cycle(): void
    {
        AcademicYear::create([
            'name' => '2024-2025', 'start_date' => '2024-06-01', 'end_date' => '2025-05-31',
            'scope' => 'all_campuses', 'status' => 'active',
        ]);

        $this->actingAs($this->superAdmin())
            ->from('/academic-years/create')
            ->post('/academic-years', [
                'name' => 'Overlapping',
                'start_date' => '2024-09-01',
                'end_date' => '2025-08-31',
                'scope' => 'all_campuses',
                'prevent_date_overlap' => '1',
            ])
            ->assertSessionHasErrors('start_date');

        $this->assertDatabaseMissing('academic_years', ['name' => 'Overlapping']);
    }

    public function test_admin_can_activate_an_upcoming_year(): void
    {
        $year = AcademicYear::create([
            'name' => '2025-2026', 'start_date' => '2025-06-01', 'end_date' => '2026-05-31', 'status' => 'upcoming',
        ]);

        $this->actingAs($this->superAdmin())
            ->post("/academic-years/{$year->id}/activate")
            ->assertRedirect();

        $this->assertSame('active', $year->fresh()->status);
    }

    public function test_non_privileged_user_cannot_manage_academic_years(): void
    {
        $this->actingAs($this->withRole('librarian'))->get('/academic-years')->assertForbidden();
    }
}
