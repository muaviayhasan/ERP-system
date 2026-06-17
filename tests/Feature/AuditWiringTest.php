<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditWiringTest extends TestCase
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

    public function test_web_admin_actions_are_audited_by_default(): void
    {
        $this->actingAs($this->superAdmin())->post('/users', [
            'name' => 'Audited User', 'email' => 'audited@erp.test', 'status' => 'active',
            'password' => 'password123', 'password_confirmation' => 'password123',
        ])->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('activity_logs', [
            'module' => 'users', 'action' => 'store', 'status' => 'success',
        ]);
    }

    public function test_audit_toggle_off_disables_web_audit(): void
    {
        Setting::putGroup('security', ['audit_logging' => false], ['audit_logging' => 'boolean']);

        $this->actingAs($this->superAdmin())->post('/users', [
            'name' => 'No Audit', 'email' => 'noaudit@erp.test', 'status' => 'active',
            'password' => 'password123', 'password_confirmation' => 'password123',
        ])->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', ['email' => 'noaudit@erp.test']); // action still happened
        $this->assertDatabaseCount('activity_logs', 0);                      // but was not logged
    }

    public function test_audit_toggle_off_disables_api_audit(): void
    {
        Setting::putGroup('security', ['audit_logging' => false], ['audit_logging' => 'boolean']);

        $this->actingAsRole('admin');
        $this->postJson('/api/v1/campuses', ['name' => 'No Audit Campus', 'code' => 'NAUD-1'])->assertCreated();

        $this->assertDatabaseCount('activity_logs', 0);
    }

    public function test_web_audit_redacts_secrets(): void
    {
        $this->actingAs($this->superAdmin())->put(route('settings.notifications.update'), [
            'smtp_encryption' => 'tls',
            'smtp_password' => 'super-secret-pass',
        ])->assertRedirect();

        $log = ActivityLog::where('module', 'settings.notifications')->latest('id')->first();
        $this->assertNotNull($log);
        $this->assertStringNotContainsString('super-secret-pass', json_encode($log->changes));
    }

    public function test_failed_validation_is_not_audited(): void
    {
        $this->actingAs($this->superAdmin())->from('/users/create')->post('/users', [
            'name' => '', 'email' => 'not-an-email', 'status' => 'active',
            'password' => 'x', 'password_confirmation' => 'y',
        ])->assertSessionHasErrors();

        $this->assertDatabaseCount('activity_logs', 0);
    }
}
