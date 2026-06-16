<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_register_and_receive_a_token(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@erp.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertCreated()
            ->assertJsonStructure(['user', 'token']);

        $this->assertDatabaseHas('users', ['email' => 'jane@erp.test']);
    }

    public function test_a_user_can_log_in_with_valid_credentials(): void
    {
        User::factory()->create(['email' => 'bob@erp.test', 'password' => Hash::make('secret123')]);

        $this->postJson('/api/v1/auth/login', ['email' => 'bob@erp.test', 'password' => 'secret123'])
            ->assertOk()
            ->assertJsonStructure(['user', 'token']);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        User::factory()->create(['email' => 'bob@erp.test', 'password' => Hash::make('secret123')]);

        $this->postJson('/api/v1/auth/login', ['email' => 'bob@erp.test', 'password' => 'wrong'])
            ->assertStatus(422);
    }

    public function test_protected_endpoints_reject_unauthenticated_requests(): void
    {
        $this->getJson('/api/v1/auth/me')->assertUnauthorized();
        $this->getJson('/api/v1/students')->assertUnauthorized();
    }

    public function test_me_returns_the_authenticated_user(): void
    {
        $this->seedRbac();
        $user = $this->actingAsRole('admin', ['email' => 'me@erp.test']);

        $this->getJson('/api/v1/auth/me')
            ->assertOk()
            ->assertJsonFragment(['email' => 'me@erp.test']);
    }
}
