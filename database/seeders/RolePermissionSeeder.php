<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /** CRUD verbs every resource permission is generated for. */
    private array $actions = ['view', 'create', 'edit', 'delete'];

    /**
     * Resource groups — names match the API resource URIs exactly so the
     * EnsureApiPermission middleware can map "{resource}.{action}" routes
     * to "{resource}.{verb}" abilities.
     */
    private array $groups = [
        'students' => ['students', 'guardians'],
        'academic' => ['campuses', 'departments', 'programs', 'courses', 'subjects', 'classes', 'sections', 'batches', 'semesters', 'academic-years', 'academic-settings'],
        'hr' => ['teachers', 'staff', 'teacher-assignments', 'staff-attendances', 'salary-structures', 'salary-payments', 'payroll-rules'],
        'teaching' => ['attendances', 'low-attendance-alerts', 'assignments', 'homeworks', 'study-materials', 'timetables'],
        'exams' => ['exams', 'exam-schedules', 'exam-results', 'grade-scales', 'student-gpas', 'result-cards'],
        'finance' => ['fee-categories', 'fee-structures', 'fee-plans', 'student-fee-assignments', 'fee-installments', 'fee-payments', 'fee-receipts', 'pending-fees', 'fine-rules', 'fines', 'refunds', 'scholarships', 'scholarship-applications', 'scholarship-assignments', 'expense-categories', 'expenses', 'income-categories', 'incomes', 'ledger-accounts', 'ledger-entries'],
        'library' => ['books', 'book-issues'],
        'transport' => ['vehicles', 'transport-routes', 'transport-assignments'],
        'hostel' => ['hostels', 'hostel-rooms', 'hostel-allocations'],
        'system' => ['settings', 'notices', 'notification-templates', 'reports', 'integrations', 'languages', 'currencies', 'activity-logs', 'users', 'roles'],
    ];

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Create the full {resource}.{action} permission set.
        foreach ($this->allResources() as $resource) {
            foreach ($this->actions as $action) {
                Permission::firstOrCreate(['name' => "{$resource}.{$action}", 'guard_name' => 'web']);
            }
        }

        $this->role('super-admin', Permission::all());
        $this->role('admin', Permission::all());

        $this->role('hod', array_merge(
            $this->allActions(['academic', 'exams', 'teaching']),
            $this->viewOnly(['students', 'hr']),
            ['reports.view']
        ));

        $this->role('teacher', array_merge(
            $this->actionsFor(['teaching', 'exams'], ['view', 'create', 'edit']),
            ['students.view', 'sections.view', 'subjects.view', 'classes.view', 'guardians.view']
        ));

        $this->role('accountant', array_merge(
            $this->allActions(['finance']),
            ['students.view', 'reports.view']
        ));

        $this->role('librarian', $this->allActions(['library']));
        $this->role('transport-manager', $this->allActions(['transport']));
        $this->role('hostel-warden', $this->allActions(['hostel']));

        $this->role('student', [
            'result-cards.view', 'exam-results.view', 'student-gpas.view', 'exams.view',
            'timetables.view', 'assignments.view', 'homeworks.view', 'study-materials.view',
            'attendances.view', 'fee-payments.view', 'fee-receipts.view', 'pending-fees.view',
            'notices.view',
        ]);

        $this->role('parent', [
            'students.view', 'attendances.view', 'result-cards.view', 'exam-results.view',
            'student-fee-assignments.view', 'fee-payments.view', 'fee-receipts.view',
            'pending-fees.view', 'notices.view',
        ]);

        // The seeded admin account is the system super-admin.
        $admin = User::firstOrCreate(
            ['email' => 'admin@erp.test'],
            ['name' => 'System Administrator', 'username' => 'admin', 'password' => Hash::make('password'), 'status' => 'active']
        );
        $admin->syncRoles(['super-admin']);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function allResources(): array
    {
        return array_merge(...array_values($this->groups));
    }

    private function role(string $name, $permissions): void
    {
        $role = Role::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        $role->syncPermissions($permissions);
    }

    /** All four actions for every resource in the given groups. */
    private function allActions(array $groupKeys): array
    {
        return $this->actionsFor($groupKeys, $this->actions);
    }

    /** Specific actions for every resource in the given groups. */
    private function actionsFor(array $groupKeys, array $actions): array
    {
        $names = [];
        foreach ($groupKeys as $key) {
            foreach ($this->groups[$key] as $resource) {
                foreach ($actions as $action) {
                    $names[] = "{$resource}.{$action}";
                }
            }
        }

        return $names;
    }

    /** View-only permissions for every resource in the given groups. */
    private function viewOnly(array $groupKeys): array
    {
        return $this->actionsFor($groupKeys, ['view']);
    }
}
