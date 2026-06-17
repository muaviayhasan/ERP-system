<?php

namespace Tests\Feature;

use App\Models\Guardian;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuardianManagementTest extends TestCase
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

    public function test_admin_can_view_guardian_pages(): void
    {
        $admin = $this->superAdmin();
        $guardian = Guardian::create(['full_name' => 'Jonathan Doe', 'phone' => '0300-1234567']);

        $this->actingAs($admin)->get('/guardians')->assertOk()->assertSee('Guardian Management');
        $this->actingAs($admin)->get('/guardians/create')->assertOk();
        $this->actingAs($admin)->get("/guardians/{$guardian->id}/edit")->assertOk()->assertSee('Jonathan Doe');
    }

    public function test_admin_can_create_a_guardian_with_linked_students_and_toggles(): void
    {
        $admin = $this->superAdmin();
        $a = Student::create(['student_code' => 'S-A', 'first_name' => 'Kid', 'last_name' => 'A', 'full_name' => 'Kid A']);
        $b = Student::create(['student_code' => 'S-B', 'first_name' => 'Kid', 'last_name' => 'B', 'full_name' => 'Kid B']);

        $this->actingAs($admin)->post('/guardians', [
            'full_name' => 'Robert Johnson',
            'relationship' => 'father',
            'phone' => '0300-9999999',
            'students' => [$a->id, $b->id],
            'is_primary_fee_payer' => '1',
            // is_emergency_authorized omitted => false
        ])->assertRedirect(route('guardians.index'));

        $guardian = Guardian::where('full_name', 'Robert Johnson')->first();
        $this->assertTrue($guardian->is_primary_fee_payer);
        $this->assertFalse($guardian->is_emergency_authorized);
        $this->assertEqualsCanonicalizing([$a->id, $b->id], $guardian->students->pluck('id')->all());
        $this->assertSame('father', $guardian->students->first()->pivot->relationship);
    }

    public function test_phone_is_required(): void
    {
        $this->actingAs($this->superAdmin())
            ->from('/guardians/create')
            ->post('/guardians', ['full_name' => 'No Phone'])
            ->assertSessionHasErrors('phone');
    }

    public function test_admin_can_update_and_delete_a_guardian(): void
    {
        $admin = $this->superAdmin();
        $guardian = Guardian::create(['full_name' => 'Old Name', 'phone' => '0300-0000000', 'status' => 'active']);

        $this->actingAs($admin)->put("/guardians/{$guardian->id}", [
            'full_name' => 'New Name', 'phone' => '0300-1111111', 'status' => 'inactive',
        ])->assertRedirect(route('guardians.index'));

        $guardian->refresh();
        $this->assertSame('New Name', $guardian->full_name);
        $this->assertSame('inactive', $guardian->status);

        $this->actingAs($admin)->delete("/guardians/{$guardian->id}")->assertRedirect();
        $this->assertDatabaseMissing('guardians', ['id' => $guardian->id]);
    }

    public function test_non_privileged_user_is_forbidden(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $user->assignRole('librarian');

        $this->actingAs($user)->get('/guardians')->assertForbidden();
    }
}
