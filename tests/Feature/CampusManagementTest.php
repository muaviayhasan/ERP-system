<?php

namespace Tests\Feature;

use App\Models\Campus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampusManagementTest extends TestCase
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

    public function test_admin_can_view_campus_pages(): void
    {
        $admin = $this->superAdmin();
        $campus = Campus::create(['name' => 'North Valley', 'code' => 'NV-201']);

        $this->actingAs($admin)->get('/campuses')->assertOk()->assertSee('Campus Management');
        $this->actingAs($admin)->get('/campuses/create')->assertOk();
        $this->actingAs($admin)->get("/campuses/{$campus->id}/edit")->assertOk()->assertSee('NV-201');
    }

    public function test_admin_can_create_a_campus_with_toggles(): void
    {
        $this->actingAs($this->superAdmin())
            ->post('/campuses', [
                'name' => 'West Coast Medical College',
                'code' => 'WCMC-001',
                'institution_type' => 'University',
                'city' => 'San Francisco',
                'state_province' => 'California',
                'status' => 'active',
                'enable_online_admissions' => '1',
                // centralized_fee_collection + hostel_management omitted => should be false
            ])
            ->assertRedirect(route('campuses.index'));

        $this->assertDatabaseHas('campuses', [
            'code' => 'WCMC-001',
            'institution_type' => 'University',
            'enable_online_admissions' => true,
            'centralized_fee_collection' => false,
            'hostel_management' => false,
        ]);
    }

    public function test_campus_code_must_be_unique(): void
    {
        Campus::create(['name' => 'Existing', 'code' => 'DUP-1']);

        $this->actingAs($this->superAdmin())
            ->from('/campuses/create')
            ->post('/campuses', ['name' => 'Another', 'code' => 'DUP-1'])
            ->assertSessionHasErrors('code');
    }

    public function test_admin_can_update_and_delete_a_campus(): void
    {
        $admin = $this->superAdmin();
        $campus = Campus::create(['name' => 'Old Name', 'code' => 'ON-1', 'status' => 'active']);

        $this->actingAs($admin)->put("/campuses/{$campus->id}", [
            'name' => 'New Name',
            'code' => 'ON-1',
            'status' => 'suspended',
        ])->assertRedirect(route('campuses.index'));

        $campus->refresh();
        $this->assertSame('New Name', $campus->name);
        $this->assertSame('suspended', $campus->status);

        $this->actingAs($admin)->delete("/campuses/{$campus->id}")->assertRedirect();
        $this->assertDatabaseMissing('campuses', ['id' => $campus->id]);
    }

    public function test_non_privileged_user_cannot_manage_campuses(): void
    {
        $this->actingAs($this->withRole('librarian'))->get('/campuses')->assertForbidden();
        $this->actingAs($this->withRole('librarian'))
            ->post('/campuses', ['name' => 'X', 'code' => 'X-1'])
            ->assertForbidden();
    }
}
