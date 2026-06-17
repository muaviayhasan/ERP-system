<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use App\Support\SettingsApplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsWiringTest extends TestCase
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

    public function test_settings_applier_pushes_values_into_config(): void
    {
        Setting::putGroup('general', ['institution_name' => 'My School']);
        Setting::putGroup('localization', ['timezone' => 'Europe/London', 'default_language' => 'ur']);
        Setting::putGroup('security', ['session_timeout_minutes' => 45], ['session_timeout_minutes' => 'integer']);
        Setting::putGroup('notifications', [
            'mail_from_address' => 'no-reply@test.dev',
            'smtp_host' => 'smtp.test',
            'smtp_port' => '2525',
            'smtp_encryption' => 'ssl',
        ]);

        SettingsApplier::apply();

        $this->assertSame('My School', config('app.name'));
        $this->assertSame('Europe/London', config('app.timezone'));
        $this->assertSame('ur', config('app.locale'));
        $this->assertSame(45, config('session.lifetime'));
        $this->assertSame('smtp', config('mail.default'));
        $this->assertSame('smtp.test', config('mail.mailers.smtp.host'));
        $this->assertSame(2525, config('mail.mailers.smtp.port'));
        $this->assertSame('ssl', config('mail.mailers.smtp.encryption'));
        $this->assertSame('no-reply@test.dev', config('mail.from.address'));
    }

    public function test_maintenance_mode_blocks_non_admins_but_allows_admins_and_logout(): void
    {
        Setting::putGroup('general', ['maintenance_mode' => true], ['maintenance_mode' => 'boolean']);

        $this->actingAs($this->withRole('librarian'))->get('/dashboard')->assertStatus(503);
        $this->actingAs($this->superAdmin())->get('/dashboard')->assertOk();
        $this->actingAs($this->withRole('librarian'))->post('/logout')->assertRedirect('/login');
    }

    public function test_password_policy_is_enforced_on_user_creation(): void
    {
        Setting::putGroup('security', [
            'password_min_length' => 12,
            'password_require_symbol' => true,
        ], ['password_min_length' => 'integer', 'password_require_symbol' => 'boolean']);

        $admin = $this->superAdmin();

        $this->actingAs($admin)->from('/users/create')->post('/users', [
            'name' => 'Weak', 'email' => 'weak@erp.test', 'status' => 'active',
            'password' => 'short', 'password_confirmation' => 'short',
        ])->assertRedirect('/users/create')->assertSessionHasErrors('password');

        $this->actingAs($admin)->post('/users', [
            'name' => 'Strong', 'email' => 'strong@erp.test', 'status' => 'active',
            'password' => 'StrongPass1!', 'password_confirmation' => 'StrongPass1!',
        ])->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', ['email' => 'strong@erp.test']);
    }

    public function test_setting_helper_reads_values_and_groups(): void
    {
        Setting::putGroup('general', ['institution_name' => 'Helper U']);

        $this->assertSame('Helper U', setting('general', 'institution_name'));
        $this->assertSame('fallback', setting('general', 'missing', 'fallback'));
        $this->assertIsArray(setting('general'));
    }

    public function test_format_money_uses_finance_settings(): void
    {
        Setting::putGroup('finance', [
            'currency_symbol' => '$',
            'currency_position' => 'before',
            'decimal_places' => 2,
            'thousand_separator' => ',',
            'decimal_separator' => '.',
        ], ['decimal_places' => 'integer']);

        $this->assertSame('$ 1,234.50', format_money(1234.5));

        Setting::putGroup('finance', ['currency_position' => 'after', 'currency_symbol' => 'PKR']);
        $this->assertSame('1,234.50 PKR', format_money(1234.5));
    }

    public function test_date_and_time_helpers_use_localization_settings(): void
    {
        config(['app.timezone' => 'UTC']);
        Setting::putGroup('localization', ['date_format' => 'Y-m-d', 'time_format' => '24']);

        $this->assertSame('2025-12-31', format_date('2025-12-31 14:30:00'));
        $this->assertSame('14:30', format_time('2025-12-31 14:30:00'));
        $this->assertSame('2025-12-31 14:30', format_datetime('2025-12-31 14:30:00'));
        $this->assertSame('', format_date(null));

        Setting::putGroup('localization', ['date_format' => 'd/m/Y', 'time_format' => '12']);
        $this->assertSame('31/12/2025', format_date('2025-12-31 14:30:00'));
        $this->assertSame('02:30 PM', format_time('2025-12-31 14:30:00'));
    }

    public function test_per_page_helper_reads_user_defaults(): void
    {
        $this->assertSame(15, per_page());
        Setting::putGroup('user_defaults', ['items_per_page' => 50], ['items_per_page' => 'integer']);
        $this->assertSame(50, per_page());
    }

    // --- IP allowlist -------------------------------------------------------

    public function test_ip_allowlist_blocks_disallowed_ips_only(): void
    {
        Setting::putGroup('security', ['allowed_ips' => '203.0.113.10'], ['allowed_ips' => 'text']);
        $admin = $this->superAdmin();

        $this->actingAs($admin)->withServerVariables(['REMOTE_ADDR' => '203.0.113.10'])->get('/dashboard')->assertOk();
        $this->actingAs($admin)->withServerVariables(['REMOTE_ADDR' => '198.51.100.9'])->get('/dashboard')->assertForbidden();
        $this->actingAs($admin)->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])->get('/dashboard')->assertOk(); // loopback always allowed
        $this->actingAs($admin)->withServerVariables(['REMOTE_ADDR' => '198.51.100.9'])->post('/logout')->assertRedirect('/login'); // logout always allowed
    }

    public function test_empty_allowlist_allows_any_ip(): void
    {
        $this->actingAs($this->superAdmin())
            ->withServerVariables(['REMOTE_ADDR' => '198.51.100.9'])
            ->get('/dashboard')->assertOk();
    }

    public function test_saving_allowlist_auto_includes_current_admin_ip(): void
    {
        $this->actingAs($this->superAdmin())
            ->withServerVariables(['REMOTE_ADDR' => '8.8.8.8'])
            ->put(route('settings.security.update'), [
                'password_min_length' => 8,
                'password_expiry_days' => 0,
                'session_timeout_minutes' => 120,
                'max_login_attempts' => 5,
                'lockout_minutes' => 15,
                'allowed_ips' => '203.0.113.10',
            ])->assertRedirect();

        $stored = Setting::getValue('security', 'allowed_ips');
        $this->assertStringContainsString('203.0.113.10', $stored);
        $this->assertStringContainsString('8.8.8.8', $stored); // self-bypass added the configuring IP
    }
}
