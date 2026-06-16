<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRbac(); // creates roles/permissions + admin@erp.test (super-admin)
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

    // --- Web authentication -------------------------------------------------

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/users')->assertRedirect('/login');
    }

    public function test_login_screen_renders_without_campus_select(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('Welcome Back')
            ->assertDontSee('Campus Selector');
    }

    public function test_user_can_login_and_logout(): void
    {
        $this->post('/login', ['email' => 'admin@erp.test', 'password' => 'password'])
            ->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($this->superAdmin());

        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_login_fails_with_bad_credentials(): void
    {
        $this->from('/login')
            ->post('/login', ['email' => 'admin@erp.test', 'password' => 'wrong'])
            ->assertRedirect('/login')
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_inactive_account_cannot_login(): void
    {
        User::factory()->create(['email' => 'off@erp.test', 'password' => Hash::make('password'), 'status' => 'suspended']);

        $this->post('/login', ['email' => 'off@erp.test', 'password' => 'password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_forgot_password_sends_reset_link(): void
    {
        $this->post('/forgot-password', ['email' => 'admin@erp.test'])
            ->assertSessionHas('status');
    }

    // --- Users CRUD ---------------------------------------------------------

    public function test_admin_can_create_a_user_with_roles(): void
    {
        $this->actingAs($this->superAdmin())
            ->post('/users', [
                'name' => 'New Teacher',
                'username' => 'newteacher',
                'email' => 'new@erp.test',
                'status' => 'active',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'roles' => ['teacher'],
            ])
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', ['email' => 'new@erp.test', 'username' => 'newteacher']);
        $this->assertTrue(User::where('email', 'new@erp.test')->first()->hasRole('teacher'));
    }

    public function test_admin_can_view_all_management_pages(): void
    {
        $admin = $this->superAdmin();
        $user = User::factory()->create();
        $role = Role::where('name', 'teacher')->first();

        $this->actingAs($admin)->get('/dashboard')->assertOk();
        $this->actingAs($admin)->get('/users')->assertOk()->assertSee('User Management');
        $this->actingAs($admin)->get('/users/create')->assertOk();
        $this->actingAs($admin)->get("/users/{$user->id}/edit")->assertOk();
        $this->actingAs($admin)->get('/roles')->assertOk()->assertSee('Roles');
        $this->actingAs($admin)->get('/roles/create')->assertOk()->assertSee('Permissions');
        $this->actingAs($admin)->get("/roles/{$role->id}/edit")->assertOk();
    }

    public function test_admin_can_update_and_delete_a_user(): void
    {
        $admin = $this->superAdmin();
        $user = User::factory()->create(['status' => 'active']);

        $this->actingAs($admin)->put("/users/{$user->id}", [
            'name' => 'Updated Name',
            'email' => $user->email,
            'status' => 'inactive',
            'roles' => ['librarian'],
        ])->assertRedirect(route('users.index'));

        $user->refresh();
        $this->assertSame('Updated Name', $user->name);
        $this->assertSame('inactive', $user->status);
        $this->assertTrue($user->hasRole('librarian'));

        $this->actingAs($admin)->delete("/users/{$user->id}")->assertRedirect();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $admin = $this->superAdmin();
        $this->actingAs($admin)->delete("/users/{$admin->id}")->assertSessionHasErrors('user');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_non_privileged_user_cannot_access_user_management(): void
    {
        $this->actingAs($this->withRole('librarian'))->get('/users')->assertForbidden();
        $this->actingAs($this->withRole('librarian'))->get('/roles')->assertForbidden();
    }

    // --- Roles & permissions ------------------------------------------------

    public function test_admin_can_create_a_role_with_permissions(): void
    {
        $this->actingAs($this->superAdmin())
            ->post('/roles', [
                'name' => 'campus coordinator',
                'permissions' => ['students.view', 'students.edit', 'attendances.view'],
            ])
            ->assertRedirect(route('roles.index'));

        $role = Role::where('name', 'campus coordinator')->first();
        $this->assertNotNull($role);
        $this->assertTrue($role->hasPermissionTo('students.view'));
        $this->assertTrue($role->hasPermissionTo('attendances.view'));
        $this->assertFalse($role->hasPermissionTo('students.delete'));
    }

    public function test_protected_role_cannot_be_edited_or_deleted(): void
    {
        $superAdminRole = Role::where('name', 'super-admin')->first();

        $this->actingAs($this->superAdmin())->get("/roles/{$superAdminRole->id}/edit")->assertForbidden();
        $this->actingAs($this->superAdmin())->delete("/roles/{$superAdminRole->id}")->assertSessionHasErrors('role');
        $this->assertDatabaseHas('roles', ['name' => 'super-admin']);
    }
}
