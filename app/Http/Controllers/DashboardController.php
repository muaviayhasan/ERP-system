<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

/**
 * Institutional overview dashboard.
 *
 * The academic/finance domain modules are not built yet, so the figures below are
 * representative placeholders assembled in one place. As each module ships, replace
 * the corresponding section with a real aggregation (ideally behind a read service)
 * — the view binds to this same structure, so no Blade changes will be needed.
 */
class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard', [
            'metrics' => $this->metrics(),
        ]);
    }

    /** @return array<string, mixed> */
    private function metrics(): array
    {
        return [
            'students' => ['total' => 12450, 'school' => 8200, 'college' => 4250, 'change' => '+2.4%'],
            'staff' => ['total' => 850, 'teaching' => 600, 'admin' => 250],
            'attendance' => ['present' => 9800, 'total' => 12450, 'rate' => 94.2, 'absent' => 400, 'late' => 150],
            'fees' => ['received' => 1200000, 'target' => 1450000, 'pending' => 250000],
            'expenses' => ['current' => 450000, 'previous' => 420000, 'change' => '+7.1%'],

            'weekly_attendance' => [
                ['day' => 'Mon', 'present' => 90, 'absent' => 10],
                ['day' => 'Tue', 'present' => 85, 'absent' => 15],
                ['day' => 'Wed', 'present' => 92, 'absent' => 8],
                ['day' => 'Thu', 'present' => 88, 'absent' => 12],
                ['day' => 'Fri', 'present' => 80, 'absent' => 20],
            ],

            'scholarships' => [
                ['label' => 'Merit', 'pct' => 45, 'dot' => 'bg-primary'],
                ['label' => 'Need-based', 'pct' => 30, 'dot' => 'bg-secondary'],
                ['label' => 'Athletic', 'pct' => 15, 'dot' => 'bg-tertiary'],
                ['label' => 'Special', 'pct' => 10, 'dot' => 'bg-outline'],
            ],

            'recent_payments' => [
                ['name' => 'Sarah Jenkins', 'detail' => 'Gr 10 - A', 'amount' => 1200, 'status' => 'Paid'],
                ['name' => 'Leo Martinez', 'detail' => 'BS Computer Sci', 'amount' => 4500, 'status' => 'Paid'],
                ['name' => 'Elena Gilbert', 'detail' => 'Gr 8 - B', 'amount' => 850, 'status' => 'Partial'],
                ['name' => 'Marcus Thorne', 'detail' => 'MA History', 'amount' => 2100, 'status' => 'Paid'],
            ],

            'recent_admissions' => [
                ['name' => 'Riley Jackson', 'initials' => 'RJ', 'detail' => 'Class 1-A', 'date' => '2024-05-22', 'tone' => 'bg-secondary-container text-on-secondary-container'],
                ['name' => 'Tara White', 'initials' => 'TW', 'detail' => 'BS Nursing', 'date' => '2024-05-21', 'tone' => 'bg-primary-container text-white'],
                ['name' => 'Matt Kim', 'initials' => 'MK', 'detail' => 'MA Psychology', 'date' => '2024-05-21', 'tone' => 'bg-tertiary/10 text-tertiary'],
                ['name' => 'Ben Lewis', 'initials' => 'BL', 'detail' => 'Class 5-C', 'date' => '2024-05-20', 'tone' => 'bg-error-container text-error'],
            ],

            'alerts' => [
                ['icon' => 'warning', 'title' => 'Attendance Drop Alert', 'body' => 'Section 4-B average attendance dropped below 75% this week.', 'classes' => 'bg-error-container/20 border-error', 'icon_color' => 'text-error', 'title_color' => 'text-error'],
                ['icon' => 'campaign', 'title' => 'Fee Reminder Pending', 'body' => '1,240 automated SMS reminders are scheduled for tomorrow.', 'classes' => 'bg-orange-50 border-orange-500', 'icon_color' => 'text-orange-500', 'title_color' => 'text-orange-700'],
                ['icon' => 'info', 'title' => 'System Maintenance', 'body' => 'ERP cloud migration scheduled for Saturday, 11 PM UTC.', 'classes' => 'bg-primary/5 border-primary', 'icon_color' => 'text-primary', 'title_color' => 'text-primary'],
            ],

            'best_attendance' => [
                ['class' => 'Grade 12-A', 'teacher' => 'Dr. Richard Feyman', 'rate' => '99.4%'],
                ['class' => 'BS Physics (Sem 4)', 'teacher' => 'Prof. Sarah Parker', 'rate' => '98.2%'],
                ['class' => 'Grade 8-C', 'teacher' => 'James Watson', 'rate' => '97.8%'],
            ],

            'highest_dues' => [
                ['name' => 'Dominic West', 'program' => 'PhD Economics', 'amount' => 12400],
                ['name' => 'Alicia Keys', 'program' => 'MA Fine Arts', 'amount' => 8200],
                ['name' => 'Sam Wilson', 'program' => 'Grade 11-B', 'amount' => 4500],
            ],
        ];
    }
}
