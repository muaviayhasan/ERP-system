<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\Campus;
use App\Models\Course;
use App\Models\Department;
use App\Models\Program;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicStructureTest extends TestCase
{
    use RefreshDatabase;

    /** prefix => [model class, table] */
    private array $entities = [
        'departments' => [Department::class, 'departments'],
        'programs' => [Program::class, 'programs'],
        'courses' => [Course::class, 'courses'],
        'subjects' => [Subject::class, 'subjects'],
        'classes' => [SchoolClass::class, 'classes'],
        'sections' => [Section::class, 'sections'],
        'semesters' => [Semester::class, 'semesters'],
        'batches' => [Batch::class, 'batches'],
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRbac();
    }

    private function superAdmin(): User
    {
        return User::where('email', 'admin@erp.test')->first();
    }

    public function test_admin_can_view_every_index_and_create_page(): void
    {
        $admin = $this->superAdmin();

        foreach (array_keys($this->entities) as $prefix) {
            $this->actingAs($admin)->get("/{$prefix}")->assertOk();
            $this->actingAs($admin)->get("/{$prefix}/create")->assertOk();
        }
    }

    public function test_admin_can_create_each_entity(): void
    {
        $admin = $this->superAdmin();
        $i = 0;

        foreach ($this->entities as $prefix => [$model, $table]) {
            $code = 'CODE-'.$i++;
            $this->actingAs($admin)
                ->post("/{$prefix}", ['name' => "Test {$prefix}", 'code' => $code])
                ->assertRedirect(route("{$prefix}.index"));

            $this->assertDatabaseHas($table, ['code' => $code, 'name' => "Test {$prefix}"]);
        }
    }

    public function test_admin_can_edit_update_and_delete_each_entity(): void
    {
        $admin = $this->superAdmin();
        $i = 100;

        foreach ($this->entities as $prefix => [$model, $table]) {
            $record = $model::create(['name' => 'Original', 'code' => 'ORIG-'.$i++]);

            $this->actingAs($admin)->get("/{$prefix}/{$record->id}/edit")->assertOk();

            $this->actingAs($admin)
                ->put("/{$prefix}/{$record->id}", ['name' => 'Renamed', 'code' => $record->code])
                ->assertRedirect(route("{$prefix}.index"));
            $this->assertSame('Renamed', $record->fresh()->name);

            $this->actingAs($admin)->delete("/{$prefix}/{$record->id}")->assertRedirect();
            $this->assertDatabaseMissing($table, ['id' => $record->id]);
        }
    }

    public function test_code_must_be_unique_per_entity(): void
    {
        $admin = $this->superAdmin();

        foreach ($this->entities as $prefix => [$model, $table]) {
            $model::create(['name' => 'Exists', 'code' => "DUP-{$prefix}"]);

            $this->actingAs($admin)
                ->from("/{$prefix}/create")
                ->post("/{$prefix}", ['name' => 'Another', 'code' => "DUP-{$prefix}"])
                ->assertSessionHasErrors('code');
        }
    }

    public function test_department_and_program_sync_campuses(): void
    {
        $admin = $this->superAdmin();
        $a = Campus::create(['name' => 'Campus A', 'code' => 'CA-1']);
        $b = Campus::create(['name' => 'Campus B', 'code' => 'CB-1']);

        $this->actingAs($admin)->post('/departments', [
            'name' => 'Computer Science', 'code' => 'CS-DEP', 'campuses' => [$a->id, $b->id],
        ])->assertRedirect();
        $dept = Department::where('code', 'CS-DEP')->first();
        $this->assertEqualsCanonicalizing([$a->id, $b->id], $dept->campuses->pluck('id')->all());

        $this->actingAs($admin)->post('/programs', [
            'name' => 'BS CS', 'code' => 'BSCS-1', 'campuses' => [$a->id],
        ])->assertRedirect();
        $program = Program::where('code', 'BSCS-1')->first();
        $this->assertEqualsCanonicalizing([$a->id], $program->campuses->pluck('id')->all());
    }

    public function test_batch_persists_weekly_days_and_toggles(): void
    {
        $this->actingAs($this->superAdmin())->post('/batches', [
            'name' => 'Morning 2026', 'code' => 'M-2026',
            'weekly_days' => ['Mon', 'Wed', 'Fri'],
            'allow_waitlist' => '1',
        ])->assertRedirect();

        $batch = Batch::where('code', 'M-2026')->first();
        $this->assertSame(['Mon', 'Wed', 'Fri'], $batch->weekly_days);
        $this->assertTrue($batch->allow_waitlist);
        $this->assertFalse($batch->open_for_admissions);
    }

    public function test_non_privileged_user_is_forbidden(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $user->assignRole('librarian');

        foreach (array_keys($this->entities) as $prefix) {
            $this->actingAs($user)->get("/{$prefix}")->assertForbidden();
        }
    }
}
