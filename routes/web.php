<?php

use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\BatchController;
use App\Http\Controllers\Admin\CampusController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\FeeCategoryController;
use App\Http\Controllers\Admin\FeeInstallmentController;
use App\Http\Controllers\Admin\FeePaymentController;
use App\Http\Controllers\Admin\FeePlanController;
use App\Http\Controllers\Admin\FeeReceiptController;
use App\Http\Controllers\Admin\FeeStructureController;
use App\Http\Controllers\Admin\GuardianController;
use App\Http\Controllers\Admin\PendingFeeController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\ScholarshipApplicationController;
use App\Http\Controllers\Admin\ScholarshipAssignmentController;
use App\Http\Controllers\Admin\ScholarshipController;
use App\Http\Controllers\Admin\StudentFeeAssignmentController;
use App\Http\Controllers\Admin\StudentFeeLedgerController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SchoolClassController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\SemesterController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\StudentDocumentController;
use App\Http\Controllers\Admin\StudentPromotionController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherAssignmentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\TimetableController;
use App\Http\Controllers\Admin\TimetableSlotController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Middleware\AuditWebActions;
use App\Http\Middleware\EnsureNotInMaintenance;
use App\Http\Middleware\RestrictIpAccess;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

// Returns a fresh CSRF token for the current session. Used right before logout so
// a long-open confirmation modal never submits a stale token (avoids 419 errors).
Route::get('/csrf-token', fn () => response()->json(['token' => csrf_token()]))->name('csrf.token');

/*
|--------------------------------------------------------------------------
| Guest (unauthenticated) routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/forgot-password', [PasswordResetController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Authenticated admin panel
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', RestrictIpAccess::class, EnsureNotInMaintenance::class, AuditWebActions::class])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Academic structure (each action is permission-guarded in the controller).
    Route::resource('campuses', CampusController::class)->except('show');
    Route::post('academic-years/{academicYear}/activate', [AcademicYearController::class, 'activate'])->name('academic-years.activate');
    Route::resource('academic-years', AcademicYearController::class)
        ->except('show')
        ->parameters(['academic-years' => 'academicYear']);
    Route::resource('departments', DepartmentController::class)->except('show');
    Route::resource('programs', ProgramController::class)->except('show');
    Route::resource('courses', CourseController::class)->except('show');
    Route::resource('subjects', SubjectController::class)->except('show');
    Route::resource('classes', SchoolClassController::class)->except('show');
    Route::resource('sections', SectionController::class)->except('show');
    Route::resource('semesters', SemesterController::class)->except('show');
    Route::resource('batches', BatchController::class)->except('show');

    // Admission & student management.
    Route::get('student-promotions', [StudentPromotionController::class, 'index'])->name('student-promotions.index');
    Route::post('student-promotions/promote', [StudentPromotionController::class, 'promote'])->name('student-promotions.promote');
    Route::resource('student-documents', StudentDocumentController::class)->except('show');
    Route::resource('guardians', GuardianController::class)->except('show');
    Route::resource('students', StudentController::class);

    // Faculty & staff.
    Route::resource('teacher-assignments', TeacherAssignmentController::class)
        ->except('show')
        ->parameters(['teacher-assignments' => 'teacherAssignment']);
    Route::resource('teachers', TeacherController::class);
    Route::resource('staff', StaffController::class)->except('show')->parameters(['staff' => 'staff']);

    // Academic operations — timetable & attendance.
    Route::resource('timetables', TimetableController::class);
    Route::post('timetables/{timetable}/slots', [TimetableSlotController::class, 'store'])->name('timetable-slots.store');
    Route::get('timetable-slots/{slot}/edit', [TimetableSlotController::class, 'edit'])->name('timetable-slots.edit');
    Route::put('timetable-slots/{slot}', [TimetableSlotController::class, 'update'])->name('timetable-slots.update');
    Route::delete('timetable-slots/{slot}', [TimetableSlotController::class, 'destroy'])->name('timetable-slots.destroy');
    Route::resource('attendances', AttendanceController::class)->only(['index', 'create', 'store', 'destroy']);

    // Fee & financial system (core ERP engine).
    Route::resource('fee-categories', FeeCategoryController::class)->except('show');
    Route::resource('fee-structures', FeeStructureController::class)->except('show');
    Route::resource('fee-plans', FeePlanController::class)->except('show');
    Route::resource('student-fee-assignments', StudentFeeAssignmentController::class)->except('show');
    Route::resource('fee-installments', FeeInstallmentController::class)->except('show');
    Route::resource('fee-payments', FeePaymentController::class)->only(['index', 'create', 'store']);
    Route::resource('fee-receipts', FeeReceiptController::class)->only(['index', 'show', 'destroy']);
    Route::get('pending-fees', [PendingFeeController::class, 'index'])->name('pending-fees.index');
    Route::get('student-fee-ledger', [StudentFeeLedgerController::class, 'index'])->name('student-fee-ledger.index');
    Route::get('student-fee-ledger/{student}', [StudentFeeLedgerController::class, 'show'])->name('student-fee-ledger.show');

    // Scholarship & financial aid.
    Route::resource('scholarships', ScholarshipController::class)->except('show');
    Route::get('scholarship-assignments/create', [ScholarshipAssignmentController::class, 'create'])->name('scholarship-assignments.create');
    Route::post('scholarship-assignments', [ScholarshipAssignmentController::class, 'store'])->name('scholarship-assignments.store');
    Route::delete('scholarship-assignments/{scholarshipAssignment}', [ScholarshipAssignmentController::class, 'destroy'])->name('scholarship-assignments.destroy');
    Route::post('scholarship-applications/{scholarshipApplication}/decide', [ScholarshipApplicationController::class, 'decide'])->name('scholarship-applications.decide');
    Route::resource('scholarship-applications', ScholarshipApplicationController::class)->except('edit', 'update');

    // User & access management (each action is permission-guarded in the controller).
    Route::resource('users', UserController::class)->except('show');
    Route::resource('roles', RoleController::class)->except('show');

    /*
    | System Settings — one page per module. View/update guarded in the controller.
    */
    Route::prefix('settings')->name('settings.')->controller(SettingsController::class)->group(function () {
        Route::get('/', 'index')->name('home');

        Route::get('institute', 'institute')->name('institute');
        Route::put('institute', 'updateInstitute')->name('institute.update');

        Route::get('general', 'general')->name('general');
        Route::put('general', 'updateGeneral')->name('general.update');

        Route::get('localization', 'localization')->name('localization');
        Route::put('localization', 'updateLocalization')->name('localization.update');

        Route::get('seo', 'seo')->name('seo');
        Route::put('seo', 'updateSeo')->name('seo.update');

        Route::get('academic', 'academic')->name('academic');
        Route::put('academic', 'updateAcademic')->name('academic.update');

        Route::get('financial', 'financial')->name('financial');
        Route::put('financial', 'updateFinancial')->name('financial.update');

        Route::get('notifications', 'notifications')->name('notifications');
        Route::put('notifications', 'updateNotifications')->name('notifications.update');

        Route::get('integrations', 'integrations')->name('integrations');
        Route::put('integrations', 'updateIntegrations')->name('integrations.update');

        Route::get('security', 'security')->name('security');
        Route::put('security', 'updateSecurity')->name('security.update');

        Route::get('user-defaults', 'userDefaults')->name('user-defaults');
        Route::put('user-defaults', 'updateUserDefaults')->name('user-defaults.update');

        // Backup & restore
        Route::get('backup', 'backup')->name('backup');
        Route::post('backup', 'createBackup')->name('backup.create');
        Route::get('backup/{file}/download', 'downloadBackup')->name('backup.download')->where('file', '[A-Za-z0-9._-]+');
        Route::delete('backup/{file}', 'destroyBackup')->name('backup.destroy')->where('file', '[A-Za-z0-9._-]+');
    });
});
