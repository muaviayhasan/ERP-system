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
    /**
     * Modules that receive a standard CRUD permission set. Permission names
     * follow the `{module}.{action}` convention used by the route middleware.
     */
    private array $modules = [
        'campuses', 'departments', 'programs', 'courses', 'subjects', 'classes',
        'sections', 'batches', 'semesters', 'academic-years', 'students',
        'guardians', 'teachers', 'staff', 'attendance', 'assignments',
        'homeworks', 'study-materials', 'timetables', 'exams', 'results',
        'fees', 'fee-structures', 'fee-payments', 'scholarships', 'fines',
        'refunds', 'expenses', 'incomes', 'ledger', 'books', 'transport',
        'hostels', 'notices', 'reports', 'settings', 'users', 'roles',
    ];

    private array $actions = ['view', 'create', 'edit', 'delete'];

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach ($this->modules as $module) {
            foreach ($this->actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$module}.{$action}",
                    'guard_name' => 'web',
                ]);
            }
        }

        // Roles.
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $accountant = Role::firstOrCreate(['name' => 'accountant', 'guard_name' => 'web']);
        $teacher = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
        $librarian = Role::firstOrCreate(['name' => 'librarian', 'guard_name' => 'web']);
        $student = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

        // super-admin is granted everything via a Gate::before bypass
        // (see AppServiceProvider); we still attach all permissions explicitly.
        $superAdmin->syncPermissions(Permission::all());
        $admin->syncPermissions(Permission::all());

        $accountant->syncPermissions(
            Permission::where('name', 'like', 'fees%')
                ->orWhere('name', 'like', 'fee-%')
                ->orWhere('name', 'like', 'expenses%')
                ->orWhere('name', 'like', 'incomes%')
                ->orWhere('name', 'like', 'ledger%')
                ->orWhere('name', 'like', 'refunds%')
                ->orWhere('name', 'like', 'scholarships%')
                ->orWhere('name', 'like', 'fines%')
                ->get()
        );

        $teacher->syncPermissions(
            Permission::whereIn('name', $this->viewAndManage([
                'attendance', 'assignments', 'homeworks', 'study-materials',
                'exams', 'results', 'timetables',
            ]))->orWhere('name', 'like', 'students.view')->get()
        );

        $librarian->syncPermissions(Permission::where('name', 'like', 'books%')->get());

        $student->syncPermissions(
            Permission::whereIn('name', [
                'results.view', 'timetables.view', 'assignments.view',
                'homeworks.view', 'study-materials.view', 'fees.view',
            ])->get()
        );

        // Default admin user gets super-admin.
        $user = User::firstOrCreate(
            ['email' => 'admin@erp.test'],
            [
                'name' => 'System Administrator',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'status' => 'active',
            ]
        );
        $user->syncRoles(['super-admin']);
    }

    private function viewAndManage(array $modules): array
    {
        $names = [];
        foreach ($modules as $module) {
            foreach ($this->actions as $action) {
                $names[] = "{$module}.{$action}";
            }
        }

        return $names;
    }
}
