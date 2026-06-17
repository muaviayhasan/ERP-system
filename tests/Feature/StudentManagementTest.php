<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Models\Student;
use App\Models\StudentDocument;
use App\Models\StudentPromotion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StudentManagementTest extends TestCase
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

    // --- Students ----------------------------------------------------------

    public function test_admin_can_view_student_pages(): void
    {
        $admin = $this->superAdmin();
        $student = Student::create(['student_code' => 'STU-1', 'first_name' => 'Aarav', 'last_name' => 'Sharma', 'full_name' => 'Aarav Sharma']);

        $this->actingAs($admin)->get('/students')->assertOk()->assertSee('Student Control Center');
        $this->actingAs($admin)->get('/students/create')->assertOk()->assertSee('Student Admission');
        $this->actingAs($admin)->get("/students/{$student->id}")->assertOk()->assertSee('Aarav Sharma');
        $this->actingAs($admin)->get("/students/{$student->id}/edit")->assertOk();
    }

    public function test_admin_can_admit_a_student_and_full_name_is_derived(): void
    {
        $this->actingAs($this->superAdmin())
            ->post('/students', [
                'student_code' => 'STU-2024-001',
                'first_name' => 'Alex',
                'last_name' => 'Johnson',
                'status' => 'active',
                'admission_status' => 'enrolled',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('students', [
            'student_code' => 'STU-2024-001',
            'full_name' => 'Alex Johnson',
        ]);
    }

    public function test_student_code_is_unique(): void
    {
        Student::create(['student_code' => 'DUP-1', 'first_name' => 'A']);

        $this->actingAs($this->superAdmin())
            ->from('/students/create')
            ->post('/students', ['student_code' => 'DUP-1', 'first_name' => 'B'])
            ->assertSessionHasErrors('student_code');
    }

    public function test_photo_upload_is_stored(): void
    {
        Storage::fake('public');

        $this->actingAs($this->superAdmin())->post('/students', [
            'student_code' => 'STU-PHOTO',
            'first_name' => 'Photo',
            'photo' => UploadedFile::fake()->image('me.jpg'),
        ])->assertRedirect();

        $student = Student::where('student_code', 'STU-PHOTO')->first();
        $this->assertNotNull($student->photo_url);
        Storage::disk('public')->assertExists($student->photo_url);
    }

    public function test_admin_can_update_and_delete_a_student(): void
    {
        $admin = $this->superAdmin();
        $student = Student::create(['student_code' => 'STU-9', 'first_name' => 'Old']);

        $this->actingAs($admin)->put("/students/{$student->id}", [
            'student_code' => 'STU-9', 'first_name' => 'New', 'last_name' => 'Name',
        ])->assertRedirect(route('students.show', $student));
        $this->assertSame('New Name', $student->fresh()->full_name);

        $this->actingAs($admin)->delete("/students/{$student->id}")->assertRedirect(route('students.index'));
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }

    // --- Documents ---------------------------------------------------------

    public function test_admin_can_upload_and_verify_a_document(): void
    {
        Storage::fake('public');
        $admin = $this->superAdmin();
        $student = Student::create(['student_code' => 'STU-DOC', 'first_name' => 'Doc']);

        $this->actingAs($admin)->get('/student-documents')->assertOk()->assertSee('Student Documents');
        $this->actingAs($admin)->get('/student-documents/create')->assertOk();

        $this->actingAs($admin)->post('/student-documents', [
            'student_id' => $student->id,
            'document_type' => 'CNIC / ID',
            'title' => 'ID_Card_Front.pdf',
            'file' => UploadedFile::fake()->create('id.pdf', 100, 'application/pdf'),
            'status' => 'pending',
        ])->assertRedirect(route('student-documents.index'));

        $doc = StudentDocument::first();
        $this->assertSame('pending', $doc->status);
        $this->assertNotNull($doc->file_path);

        // Verifying stamps the verifier + timestamp.
        $this->actingAs($admin)->put("/student-documents/{$doc->id}", [
            'student_id' => $student->id,
            'document_type' => 'CNIC / ID',
            'title' => 'ID_Card_Front.pdf',
            'status' => 'verified',
            'verification_notes' => 'Looks good',
        ])->assertRedirect();

        $doc->refresh();
        $this->assertSame('verified', $doc->status);
        $this->assertSame($admin->id, $doc->verified_by);
        $this->assertNotNull($doc->verified_at);
    }

    // --- Promotion ---------------------------------------------------------

    public function test_admin_can_promote_selected_students(): void
    {
        $admin = $this->superAdmin();
        $toProgram = Program::create(['name' => 'BS Year 2', 'code' => 'BSY2']);
        $s1 = Student::create(['student_code' => 'P-1', 'first_name' => 'One', 'status' => 'active']);
        $s2 = Student::create(['student_code' => 'P-2', 'first_name' => 'Two', 'status' => 'active']);

        $this->actingAs($admin)->get('/student-promotions')->assertOk()->assertSee('Student Promotion');

        $this->actingAs($admin)->post('/student-promotions/promote', [
            'student_ids' => [$s1->id, $s2->id],
            'to_program_id' => $toProgram->id,
        ])->assertRedirect();

        $this->assertDatabaseCount('student_promotions', 2);
        $this->assertSame($toProgram->id, $s1->fresh()->program_id);
        $this->assertTrue(StudentPromotion::where('student_id', $s1->id)->first()->promoted);
    }

    public function test_promote_requires_at_least_one_student(): void
    {
        $this->actingAs($this->superAdmin())
            ->from('/student-promotions')
            ->post('/student-promotions/promote', ['student_ids' => []])
            ->assertSessionHasErrors('student_ids');
    }

    // --- RBAC --------------------------------------------------------------

    public function test_non_privileged_user_is_forbidden(): void
    {
        $user = $this->withRole('librarian');

        $this->actingAs($user)->get('/students')->assertForbidden();
        $this->actingAs($user)->get('/student-documents')->assertForbidden();
        $this->actingAs($user)->get('/student-promotions')->assertForbidden();
    }
}
