<?php

namespace Tests;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\PermissionRegistrar;

abstract class TestCase extends BaseTestCase
{
    /**
     * Seed the roles/permissions matrix once the schema is migrated. Tests that
     * use RefreshDatabase call this from their own setUp via seedRbac().
     */
    protected function seedRbac(): void
    {
        $this->seed(RolePermissionSeeder::class);
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * Create a user, optionally assign a role, and authenticate as them for
     * Sanctum-guarded API requests.
     */
    protected function actingAsRole(?string $role = null, array $attributes = []): User
    {
        $user = User::factory()->create($attributes);

        if ($role) {
            $user->assignRole($role);
        }

        Sanctum::actingAs($user);

        return $user;
    }
}
