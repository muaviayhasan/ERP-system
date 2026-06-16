<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRbac();
    }

    public function test_successful_writes_are_audit_logged(): void
    {
        $this->actingAsRole('admin');

        $this->postJson('/api/v1/campuses', ['name' => 'Audit Campus', 'code' => 'AUD-1'])
            ->assertCreated();

        $this->assertDatabaseHas('activity_logs', [
            'module' => 'campuses',
            'action' => 'store',
            'status' => 'success',
        ]);

        $log = ActivityLog::latest('id')->first();
        $this->assertNotNull($log->user_id);
        $this->assertNotNull($log->ip_address);
    }

    public function test_read_requests_are_not_audit_logged(): void
    {
        $this->actingAsRole('admin');
        $this->getJson('/api/v1/campuses')->assertOk();

        $this->assertSame(0, ActivityLog::count());
    }

    public function test_secrets_are_never_written_to_the_audit_log(): void
    {
        $this->actingAsRole('admin');

        $this->postJson('/api/v1/users', [
            'name' => 'Secret User',
            'email' => 'secret@erp.test',
            'password' => 'topsecretpw',
            'password_confirmation' => 'topsecretpw',
        ])->assertCreated();

        $log = ActivityLog::where('module', 'users')->latest('id')->first();
        $this->assertNotNull($log);
        // The plaintext password must never appear in the recorded payload.
        $this->assertStringNotContainsString('topsecretpw', json_encode($log->changes));
    }
}
