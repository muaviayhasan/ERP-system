<?php

/*
|--------------------------------------------------------------------------
| Admin Panel Navigation
|--------------------------------------------------------------------------
|
| The sidebar menu structure. Top-level entries are either a single link
| (no "children") or a collapsible group (with "children"). Each leaf maps
| to an ERP module — URLs are placeholders ("#") for now and will point to
| real panel pages as they are built. Icons are Material Symbols names.
|
*/

return [
    [
        'label' => 'Dashboard',
        'icon' => 'dashboard',
        'route' => 'dashboard',
    ],

    [
        'label' => 'Institute Profile',
        'icon' => 'corporate_fare',
        'route' => 'settings.institute',
    ],

    [
        'label' => 'Academics',
        'icon' => 'auto_stories',
        'children' => [
            ['label' => 'Campuses', 'icon' => 'apartment', 'route' => 'campuses.index'],
            ['label' => 'Departments', 'icon' => 'account_tree', 'route' => 'departments.index'],
            ['label' => 'Programs', 'icon' => 'school', 'route' => 'programs.index'],
            ['label' => 'Courses', 'icon' => 'menu_book', 'route' => 'courses.index'],
            ['label' => 'Subjects', 'icon' => 'book_2', 'route' => 'subjects.index'],
            ['label' => 'Classes', 'icon' => 'groups', 'route' => 'classes.index'],
            ['label' => 'Sections', 'icon' => 'grid_view', 'route' => 'sections.index'],
            ['label' => 'Batches', 'icon' => 'diversity_3', 'route' => 'batches.index'],
            ['label' => 'Semesters', 'icon' => 'date_range', 'route' => 'semesters.index'],
            ['label' => 'Academic Years', 'icon' => 'calendar_month', 'route' => 'academic-years.index'],
            ['label' => 'Academic Settings', 'icon' => 'tune'],
        ],
    ],

    [
        'label' => 'Students',
        'icon' => 'school',
        'children' => [
            ['label' => 'All Students', 'icon' => 'badge', 'route' => 'students.index'],
            ['label' => 'New Admission', 'icon' => 'person_add', 'route' => 'students.create'],
            ['label' => 'Guardians', 'icon' => 'family_restroom', 'route' => 'guardians.index'],
            ['label' => 'Documents', 'icon' => 'folder_open', 'route' => 'student-documents.index'],
            ['label' => 'Promotions', 'icon' => 'trending_up', 'route' => 'student-promotions.index'],
        ],
    ],

    [
        'label' => 'Faculty & HR',
        'icon' => 'groups_2',
        'children' => [
            ['label' => 'Teachers', 'icon' => 'person', 'route' => 'teachers.index'],
            ['label' => 'Teacher Assignments', 'icon' => 'assignment_ind', 'route' => 'teacher-assignments.index'],
            ['label' => 'Staff', 'icon' => 'badge', 'route' => 'staff.index'],
            ['label' => 'Staff Attendance', 'icon' => 'how_to_reg'],
            ['label' => 'Salary Structures', 'icon' => 'account_balance_wallet'],
            ['label' => 'Salary Payments', 'icon' => 'payments'],
            ['label' => 'Payroll Rules', 'icon' => 'rule'],
        ],
    ],

    [
        'label' => 'Attendance',
        'icon' => 'fact_check',
        'children' => [
            ['label' => 'Student Attendance', 'icon' => 'how_to_reg', 'route' => 'attendances.index'],
            ['label' => 'Low Attendance Alerts', 'icon' => 'warning'],
        ],
    ],

    [
        'label' => 'Academic Delivery',
        'icon' => 'menu_book',
        'children' => [
            ['label' => 'Assignments', 'icon' => 'assignment'],
            ['label' => 'Homework', 'icon' => 'home_work'],
            ['label' => 'Study Materials', 'icon' => 'collections_bookmark'],
            ['label' => 'Timetable', 'icon' => 'schedule', 'route' => 'timetables.index'],
        ],
    ],

    [
        'label' => 'Examinations',
        'icon' => 'quiz',
        'children' => [
            ['label' => 'Exams', 'icon' => 'quiz'],
            ['label' => 'Exam Schedules', 'icon' => 'event'],
            ['label' => 'Exam Results', 'icon' => 'grade'],
            ['label' => 'Grade Scales', 'icon' => 'straighten'],
            ['label' => 'GPA / CGPA', 'icon' => 'calculate'],
            ['label' => 'Result Cards', 'icon' => 'description'],
        ],
    ],

    [
        'label' => 'Finance',
        'icon' => 'payments',
        'children' => [
            ['label' => 'Fee Categories', 'icon' => 'category', 'route' => 'fee-categories.index'],
            ['label' => 'Fee Structures', 'icon' => 'receipt_long', 'route' => 'fee-structures.index'],
            ['label' => 'Fee Plans', 'icon' => 'event_repeat', 'route' => 'fee-plans.index'],
            ['label' => 'Fee Assignments', 'icon' => 'assignment_turned_in', 'route' => 'student-fee-assignments.index'],
            ['label' => 'Fee Collection', 'icon' => 'point_of_sale', 'route' => 'fee-payments.index'],
            ['label' => 'Receipts', 'icon' => 'receipt', 'route' => 'fee-receipts.index'],
            ['label' => 'Installments', 'icon' => 'calendar_view_week', 'route' => 'fee-installments.index'],
            ['label' => 'Pending Fees', 'icon' => 'pending_actions', 'route' => 'pending-fees.index'],
            ['label' => 'Fee Ledger', 'icon' => 'menu_book', 'route' => 'student-fee-ledger.index'],
            ['label' => 'Fines', 'icon' => 'gavel'],
            ['label' => 'Refunds', 'icon' => 'undo'],
            ['label' => 'Scholarships', 'icon' => 'volunteer_activism', 'route' => 'scholarships.index'],
            ['label' => 'Scholarship Approvals', 'icon' => 'how_to_reg', 'route' => 'scholarship-applications.index'],
        ],
    ],

    [
        'label' => 'Accounting',
        'icon' => 'account_balance',
        'children' => [
            ['label' => 'Expenses', 'icon' => 'trending_down'],
            ['label' => 'Expense Categories', 'icon' => 'label'],
            ['label' => 'Income', 'icon' => 'trending_up'],
            ['label' => 'Income Categories', 'icon' => 'sell'],
            ['label' => 'Ledger Accounts', 'icon' => 'account_tree'],
            ['label' => 'Ledger Entries', 'icon' => 'receipt_long'],
        ],
    ],

    [
        'label' => 'Library',
        'icon' => 'local_library',
        'children' => [
            ['label' => 'Books', 'icon' => 'menu_book'],
            ['label' => 'Book Issues', 'icon' => 'swap_horiz'],
        ],
    ],

    [
        'label' => 'Transport',
        'icon' => 'directions_bus',
        'children' => [
            ['label' => 'Vehicles', 'icon' => 'directions_bus'],
            ['label' => 'Routes', 'icon' => 'route'],
            ['label' => 'Transport Assignments', 'icon' => 'alt_route'],
        ],
    ],

    [
        'label' => 'Hostel',
        'icon' => 'hotel',
        'children' => [
            ['label' => 'Hostels', 'icon' => 'apartment'],
            ['label' => 'Rooms', 'icon' => 'meeting_room'],
            ['label' => 'Allocations', 'icon' => 'bed'],
        ],
    ],

    [
        'label' => 'Communication',
        'icon' => 'campaign',
        'children' => [
            ['label' => 'Notices', 'icon' => 'campaign'],
            ['label' => 'Notification Templates', 'icon' => 'mail'],
        ],
    ],

    [
        'label' => 'Reports',
        'icon' => 'analytics',
        'route' => 'dashboard',
    ],

    [
        'label' => 'Settings',
        'icon' => 'settings',
        'children' => [
            ['label' => 'General', 'icon' => 'tune', 'route' => 'settings.general'],
            ['label' => 'Localization', 'icon' => 'language', 'route' => 'settings.localization'],
            ['label' => 'SEO', 'icon' => 'search_check', 'route' => 'settings.seo'],
            ['label' => 'Academic', 'icon' => 'school', 'route' => 'settings.academic'],
            ['label' => 'Financial', 'icon' => 'account_balance', 'route' => 'settings.financial'],
            ['label' => 'Notifications', 'icon' => 'notifications', 'route' => 'settings.notifications'],
            ['label' => 'Integrations', 'icon' => 'extension', 'route' => 'settings.integrations'],
            ['label' => 'Security', 'icon' => 'security', 'route' => 'settings.security'],
            ['label' => 'User Defaults', 'icon' => 'manage_accounts', 'route' => 'settings.user-defaults'],
            ['label' => 'Backup', 'icon' => 'cloud_upload', 'route' => 'settings.backup'],
            ['label' => 'Users', 'icon' => 'group', 'route' => 'users.index'],
            ['label' => 'Roles & Permissions', 'icon' => 'admin_panel_settings', 'route' => 'roles.index'],
        ],
    ],
];
