<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\ResultCard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RbacTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRbac();
    }

    public function test_super_admin_can_access_everything(): void
    {
        $this->actingAsRole('super-admin');

        $this->getJson('/api/v1/students')->assertOk();
        $this->getJson('/api/v1/users')->assertOk();
        $this->getJson('/api/v1/fee-payments')->assertOk();
    }

    public function test_librarian_is_limited_to_library_resources(): void
    {
        $this->actingAsRole('librarian');

        $this->getJson('/api/v1/books')->assertOk();                 // has books.view
        $this->getJson('/api/v1/users')->assertForbidden();          // no users.view
        $this->getJson('/api/v1/fee-payments')->assertForbidden();   // no fee-payments.view
        $this->postJson('/api/v1/students', [])->assertForbidden();  // no students.create
    }

    public function test_student_role_is_read_only_on_permitted_resources(): void
    {
        $this->actingAsRole('student');

        $this->getJson('/api/v1/result-cards')->assertOk();          // has result-cards.view
        $this->getJson('/api/v1/students')->assertForbidden();       // no students.view
    }

    public function test_authorization_runs_before_route_model_binding(): void
    {
        // A forbidden user must get 403 even for a non-existent id — never a 404
        // that would reveal whether the record exists.
        $this->actingAsRole('student');

        $this->deleteJson('/api/v1/result-cards/999999')->assertForbidden();
        $this->deleteJson('/api/v1/result-cards/1')->assertForbidden();
    }

    public function test_permitted_user_still_gets_404_for_missing_record(): void
    {
        $this->actingAsRole('librarian');
        Book::query()->delete();

        $this->getJson('/api/v1/books/999999')->assertNotFound();
    }

    public function test_unknown_or_unnamed_routes_fail_closed(): void
    {
        // Sanity: an authenticated user without the ability cannot reach a
        // resource they lack permission for.
        $this->actingAsRole('librarian');
        $this->getJson('/api/v1/ledger-entries')->assertForbidden();
    }
}
