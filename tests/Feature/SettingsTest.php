<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRbac(); // roles/permissions + admin@erp.test (super-admin)
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

    // --- Access control -----------------------------------------------------

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/settings/general')->assertRedirect('/login');
    }

    public function test_index_redirects_to_general(): void
    {
        $this->actingAs($this->superAdmin())->get('/settings')->assertRedirect(route('settings.general'));
    }

    public function test_admin_can_view_every_settings_page(): void
    {
        $admin = $this->superAdmin();

        foreach ([
            'settings.general' => 'General Settings',
            'settings.localization' => 'Localization Settings',
            'settings.seo' => 'SEO Settings',
            'settings.academic' => 'Academic Settings',
            'settings.financial' => 'Financial Settings',
            'settings.notifications' => 'Notification Settings',
            'settings.integrations' => 'Integrations',
            'settings.security' => 'Security Settings',
            'settings.user-defaults' => 'User Defaults',
            'settings.backup' => 'Backup',
        ] as $route => $heading) {
            $this->actingAs($admin)->get(route($route))->assertOk()->assertSee($heading);
        }
    }

    public function test_section_titles_render_ampersand_without_double_escaping(): void
    {
        $this->actingAs($this->superAdmin())->get(route('settings.user-defaults'))
            ->assertOk()
            ->assertSee('Locale & Display')      // escaped once by Blade => &amp;
            ->assertDontSee('&amp;amp;', false); // never double-escaped
    }

    public function test_non_privileged_user_is_forbidden(): void
    {
        $user = $this->withRole('librarian');

        $this->actingAs($user)->get('/settings/general')->assertForbidden();
        $this->actingAs($user)->put('/settings/general', ['institution_name' => 'X'])->assertForbidden();
    }

    // --- Persistence & casting ---------------------------------------------

    public function test_general_settings_are_saved(): void
    {
        $this->actingAs($this->superAdmin())->put(route('settings.general.update'), [
            'institution_name' => 'Sapphire University',
            'contact_email' => 'admin@sapphire.edu',
            'maintenance_mode' => '1',
        ])->assertRedirect();

        $this->assertDatabaseHas('settings', ['group' => 'general', 'key' => 'institution_name', 'value' => 'Sapphire University']);
        $this->assertSame('Sapphire University', Setting::getValue('general', 'institution_name'));
        $this->assertTrue(Setting::getValue('general', 'maintenance_mode'));
        $this->assertFalse(Setting::getValue('general', 'debug_mode')); // unchecked => false
    }

    public function test_security_settings_cast_integers_and_booleans(): void
    {
        $this->actingAs($this->superAdmin())->put(route('settings.security.update'), [
            'password_min_length' => '12',
            'password_expiry_days' => '90',
            'session_timeout_minutes' => '60',
            'max_login_attempts' => '5',
            'lockout_minutes' => '15',
            'password_require_uppercase' => '1',
            // require_symbol omitted => false
        ])->assertRedirect();

        $this->assertSame(12, Setting::getValue('security', 'password_min_length'));
        $this->assertTrue(Setting::getValue('security', 'password_require_uppercase'));
        $this->assertFalse(Setting::getValue('security', 'password_require_symbol'));
        $this->assertDatabaseHas('settings', ['group' => 'security', 'key' => 'password_min_length', 'type' => 'integer']);
    }

    public function test_localization_stores_supported_languages_as_json(): void
    {
        $this->actingAs($this->superAdmin())->put(route('settings.localization.update'), [
            'default_language' => 'en',
            'supported_languages' => ['en', 'ur', 'ar'],
            'number_format' => 'us',
            'timezone' => 'Asia/Karachi',
            'date_format' => 'd/m/Y',
            'time_format' => '12',
            'week_start' => 'monday',
        ])->assertRedirect();

        $this->assertSame(['en', 'ur', 'ar'], Setting::getValue('localization', 'supported_languages'));
        $this->assertDatabaseHas('settings', ['group' => 'localization', 'key' => 'supported_languages', 'type' => 'json']);
    }

    public function test_academic_settings_persist_to_their_own_table(): void
    {
        $this->actingAs($this->superAdmin())->put(route('settings.academic.update'), [
            'grading_system' => 'Percentage',
            'pass_mark_threshold' => '50',
            'min_attendance_required' => '80',
            'attendance_grace_minutes' => '10',
            'attendance_warning_threshold' => '75',
            'attendance_critical_threshold' => '60',
            'exam_structure' => 'Annual Structure',
            'weight_final' => '50',
            'weight_midterm' => '30',
            'weight_assignments_lab' => '10',
            'weight_quizzes' => '10',
            'university_mode_enabled' => '1',
        ])->assertRedirect();

        $this->assertDatabaseHas('academic_settings', [
            'grading_system' => 'Percentage',
            'pass_mark_threshold' => 50,
            'university_mode_enabled' => 1,
        ]);
    }

    // --- Secrets ------------------------------------------------------------

    public function test_secret_is_encrypted_at_rest_kept_when_blank_and_never_shown(): void
    {
        $admin = $this->superAdmin();

        // Save a secret.
        $this->actingAs($admin)->put(route('settings.notifications.update'), [
            'smtp_encryption' => 'tls',
            'smtp_password' => 'super-secret-pass',
        ])->assertRedirect();

        $row = Setting::where('group', 'notifications')->where('key', 'smtp_password')->first();
        $this->assertNotNull($row);
        $this->assertSame('encrypted', $row->type);
        $this->assertNotSame('super-secret-pass', $row->value);             // stored ciphertext
        $this->assertSame('super-secret-pass', Setting::getValue('notifications', 'smtp_password')); // decrypts

        // The plaintext secret is never rendered back to the page.
        $this->actingAs($admin)->get(route('settings.notifications'))
            ->assertOk()
            ->assertDontSee('super-secret-pass');

        // Submitting again without the secret keeps the stored value.
        $this->actingAs($admin)->put(route('settings.notifications.update'), [
            'smtp_encryption' => 'ssl',
        ])->assertRedirect();
        $this->assertSame('super-secret-pass', Setting::getValue('notifications', 'smtp_password'));
    }

    // --- Backup -------------------------------------------------------------

    public function test_admin_can_create_download_and_delete_a_sqlite_backup(): void
    {
        $admin = $this->superAdmin();

        // Point the sqlite connection at a real temp file so the copy succeeds.
        $tmp = tempnam(sys_get_temp_dir(), 'dbbak_').'.sqlite';
        file_put_contents($tmp, "SQLite format 3\0test");
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => $tmp]);

        $this->cleanBackups();

        $this->actingAs($admin)->post(route('settings.backup.create'))->assertRedirect();

        $files = glob(storage_path('app/backups/*'));
        $this->assertNotEmpty($files, 'A backup file should have been created.');

        $name = basename($files[0]);
        $this->actingAs($admin)->get(route('settings.backup.download', $name))->assertOk();
        $this->actingAs($admin)->delete(route('settings.backup.destroy', $name))->assertRedirect();
        $this->assertFileDoesNotExist(storage_path('app/backups/'.$name));

        @unlink($tmp);
        $this->cleanBackups();
    }

    private function cleanBackups(): void
    {
        foreach (glob(storage_path('app/backups/*')) ?: [] as $f) {
            @unlink($f);
        }
    }
}
