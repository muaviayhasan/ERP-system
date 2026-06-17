<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstituteProfileTest extends TestCase
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

    public function test_admin_can_view_institute_profile_page(): void
    {
        $this->actingAs($this->superAdmin())
            ->get('/settings/institute')
            ->assertOk()
            ->assertSee('Institute Settings');
    }

    public function test_admin_can_save_institute_profile(): void
    {
        $this->actingAs($this->superAdmin())
            ->put('/settings/institute', [
                'full_name' => 'Global International',
                'short_name' => 'EDU-2024',
                'institute_type' => 'University',
                'contact_email' => 'admin@global-intl.edu',
                'website' => 'www.global-intl.edu',
                'country' => 'United States',
                'state_province' => 'California',
                'city' => 'Palo Alto',
                'established_year' => 2010,
            ])
            ->assertRedirect()
            ->assertSessionHas('status');

        $this->assertSame('Global International', Setting::getValue('institute', 'full_name'));
        $this->assertSame(2010, Setting::getValue('institute', 'established_year'));
        $this->assertSame('Palo Alto', Setting::getValue('institute', 'city'));
    }

    public function test_full_name_is_required(): void
    {
        $this->actingAs($this->superAdmin())
            ->from('/settings/institute')
            ->put('/settings/institute', ['full_name' => ''])
            ->assertSessionHasErrors('full_name');
    }

    public function test_non_privileged_user_cannot_view_institute_profile(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $user->assignRole('librarian');

        $this->actingAs($user)->get('/settings/institute')->assertForbidden();
    }
}
