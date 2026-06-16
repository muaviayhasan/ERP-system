<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Order matters: core/lookup entities (campuses, programs, teachers,
     * students) are seeded first so that the transactional module seeders can
     * reference ids 1..n. Spatie roles are created up front.
     */
    public function run(): void
    {
        // Default admin user.
        User::firstOrCreate(
            ['email' => 'admin@erp.test'],
            [
                'name' => 'System Administrator',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'status' => 'active',
            ]
        );

        $this->call([
            // 0. Roles & permissions (assigns super-admin to the admin user).
            RolePermissionSeeder::class,
            // 1. Academic foundation (campuses, programs, semesters, classes...).
            AcademicStructureSeeder::class,
            // 2. People who are referenced by students/attendance/exams.
            HumanResourceSeeder::class,
            // 3. Students & guardians.
            StudentSeeder::class,
            // 4. Scholarships/fines (scholarships referenced by fee assignments).
            ScholarshipFineSeeder::class,
            // 5. Fees.
            FeeSeeder::class,
            // 6. Exams & results.
            ExamSeeder::class,
            // 7. Attendance & academic delivery.
            AttendanceAcademicSeeder::class,
            // 8. Accounting.
            AccountingSeeder::class,
            // 9. Library / transport / hostel.
            FacilitySeeder::class,
            // 10. Settings, communication & system.
            SystemSettingsSeeder::class,
        ]);
    }
}
