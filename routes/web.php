<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenantSwitchController;
use App\Http\Controllers\Admin\BulkActionController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\ClassScheduleController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ExportController as AdminExportController;
use App\Http\Controllers\Admin\FoundationSkillController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\HolidayController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\StudentFeeController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PaymentReportController;
use App\Http\Controllers\Student\ProgressController;
use App\Http\Controllers\Public\ParentController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\ExportController as TeacherExportController;
use App\Http\Controllers\Teacher\ReportController as TeacherReportController;
use App\Http\Controllers\Teacher\SessionController as TeacherSessionController;
use App\Http\Controllers\Teacher\StudentController as TeacherStudentController;
use App\Http\Controllers\Teacher\MemorizationController as TeacherMemorizationController;
use App\Http\Controllers\Saas\TenantAdminController;
use App\Http\Controllers\Saas\TenantController as SaasTenantController;
use App\Http\Controllers\Saas\VisitorAnalyticsController;
use App\Http\Controllers\QuranController;
use App\Http\Controllers\QuranSearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->isSaasAdmin()) {
            return redirect()->route('saas.tenants.index');
        }

        $tenantRole = auth()->user()->tenants()->where('tenants.id', session('current_tenant_id'))->first()?->pivot?->role;
        return $tenantRole === 'tenant_admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('teacher.dashboard');
    }

    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1') // Limit: 5 attempts per minute
    ->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/p/{token}', [ParentController::class, 'show'])->name('parent.show');

Route::middleware(['auth'])->group(function () {
    Route::post('/tenant/switch', [TenantSwitchController::class, 'switch'])->name('tenant.switch');

    // Profile Management (available to all authenticated users)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->middleware('throttle:5,1') // Limit: 5 password changes per minute
        ->name('profile.password');

    Route::middleware(['role:saas_admin'])->prefix('saas')->name('saas.')->group(function () {
        Route::resource('tenants', SaasTenantController::class)->except(['show']);
        Route::post('tenants/{tenant}/toggle-status', [SaasTenantController::class, 'toggleStatus'])->name('tenants.toggle-status');

        Route::resource('tenants.admins', TenantAdminController::class)
            ->except(['show'])
            ->names('tenant_admins');
        Route::post('tenants/{tenant}/admins/{admin}/toggle-status', [TenantAdminController::class, 'toggleStatus'])->name('tenant_admins.toggle-status');

        Route::get('analytics', [VisitorAnalyticsController::class, 'index'])->name('analytics');
        Route::get('analytics/visitor/{ip}', [VisitorAnalyticsController::class, 'visitorDetail'])->name('analytics.visitor');
    });

    Route::middleware(['role:tenant_admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('teachers', TeacherController::class)->except(['show']);
        Route::get('teachers-workload', [TeacherController::class, 'workload'])->name('teachers.workload');
        Route::post('teachers/{teacher}/toggle-status', [TeacherController::class, 'toggleStatus'])->name('teachers.toggle-status');
        Route::resource('classes', ClassController::class)->except(['show']);
        Route::post('classes/{class}/toggle-status', [ClassController::class, 'toggleStatus'])->name('classes.toggle-status');
        Route::resource('students', StudentController::class)->except(['show']);
        Route::post('students/{student}/regenerate-token', [StudentController::class, 'regenerateToken'])->name('students.regenerate-token');
        Route::post('students/{student}/update-mastery', [StudentController::class, 'updateMastery'])->name('students.update-mastery');
        Route::post('students/{student}/toggle-status', [StudentController::class, 'toggleStatus'])->name('students.toggle-status');
        Route::post('students/{student}/graduate', [StudentController::class, 'graduate'])->name('students.graduate');
        Route::get('students/{student}/transfer', [StudentController::class, 'showTransferForm'])->name('students.transfer.form');
        Route::post('students/{student}/transfer', [StudentController::class, 'transfer'])->name('students.transfer.process');
        Route::get('students/{student}/transfer-history', [StudentController::class, 'transferHistory'])->name('students.transfer.history');

        Route::resource('foundation-skills', FoundationSkillController::class)->except(['show']);

        // Schedule routes - specific routes MUST come before dynamic {schedule} route
        Route::get('schedules/calendar', [ClassScheduleController::class, 'calendar'])->name('schedules.calendar');
        Route::resource('schedules', ClassScheduleController::class)->except(['show']);
        Route::get('schedules/{schedule}', [ClassScheduleController::class, 'show'])->name('schedules.show');

        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/inactive-students', [ReportController::class, 'inactiveStudents'])->name('reports.inactive-students');
        Route::get('reports/teacher/{teacherId}', [ReportController::class, 'teacher'])->name('reports.teacher');

        // Export routes
        Route::get('export/students', [AdminExportController::class, 'students'])->name('export.students');
        Route::get('export/sessions', [AdminExportController::class, 'sessions'])->name('export.sessions');

        // Bulk action routes
        Route::post('bulk/students/status', [BulkActionController::class, 'updateStudentsStatus'])->name('bulk.students.status');
        Route::post('bulk/students/assign-class', [BulkActionController::class, 'assignStudentsToClass'])->name('bulk.students.assign-class');
        Route::delete('bulk/students', [BulkActionController::class, 'deleteStudents'])->name('bulk.students.delete');
        Route::post('bulk/teachers/status', [BulkActionController::class, 'updateTeachersStatus'])->name('bulk.teachers.status');
        Route::delete('bulk/teachers', [BulkActionController::class, 'deleteTeachers'])->name('bulk.teachers.delete');
        Route::post('bulk/classes/status', [BulkActionController::class, 'updateClassesStatus'])->name('bulk.classes.status');
        Route::delete('bulk/classes', [BulkActionController::class, 'deleteClasses'])->name('bulk.classes.delete');

        // Import routes
        Route::get('import/students', [ImportController::class, 'showStudentsImport'])->name('import.students');
        Route::post('import/students', [ImportController::class, 'importStudents'])->name('import.students.process');
        Route::get('import/students/template', [ImportController::class, 'downloadStudentsTemplate'])->name('import.students.template');

        // Progress map
        Route::get('students/{student}/progress', [ProgressController::class, 'show'])->name('students.progress');

        // Holidays
        Route::get('holidays/calendar', [HolidayController::class, 'calendar'])->name('holidays.calendar');
        Route::resource('holidays', HolidayController::class)->except(['show']);

        // Announcements
        Route::resource('announcements', AnnouncementController::class)->except(['show']);

        // Activity Logs
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

        // Visitor Analytics
        Route::get('analytics', [VisitorAnalyticsController::class, 'index'])->name('analytics');
        Route::get('analytics/visitor/{ip}', [VisitorAnalyticsController::class, 'visitorDetail'])->name('analytics.visitor');

        // Payment System - الرسوم والمدفوعات
        Route::resource('student-fees', StudentFeeController::class);
        Route::post('student-fees/bulk', [StudentFeeController::class, 'bulkSet'])->name('student-fees.bulk');

        Route::resource('payments', PaymentController::class);
        Route::post('payments/generate-monthly', [PaymentController::class, 'generateMonthly'])->name('payments.generate-monthly');

        Route::get('reports/payments/overdue', [PaymentReportController::class, 'overdueReport'])->name('reports.payments.overdue');
        Route::get('reports/payments/monthly', [PaymentReportController::class, 'monthlyReport'])->name('reports.payments.monthly');
    });

    Route::middleware(['role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
        Route::get('/schedule', [TeacherDashboardController::class, 'schedule'])->name('schedule');
        Route::get('/students', [TeacherStudentController::class, 'index'])->name('students.index');

        Route::get('/reports', [TeacherReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/student/{student}', [TeacherReportController::class, 'student'])->name('reports.student');

        Route::resource('sessions', TeacherSessionController::class)->except(['show', 'destroy']);

        // Export routes
        Route::get('export/sessions', [TeacherExportController::class, 'sessions'])->name('export.sessions');

        // Progress map
        Route::get('students/{student}/progress', [ProgressController::class, 'show'])->name('students.progress');

        // Memorization tracking
        Route::prefix('memorization')->name('memorization.')->group(function () {
            Route::get('students/{student}', [TeacherMemorizationController::class, 'show'])->name('show');
            Route::get('students/{student}/quran-page', [TeacherMemorizationController::class, 'openQuranPage'])->name('open-quran');
            Route::post('students/{student}/assignments', [TeacherMemorizationController::class, 'storeAssignment'])->name('assignments.store');
            Route::post('students/{student}/tests', [TeacherMemorizationController::class, 'storeTest'])->name('tests.store');
            Route::post('students/{student}/confirm', [TeacherMemorizationController::class, 'confirmMemorization'])->name('confirm');
        });
    });

});

// المصحف الكريم (متاح للجميع بدون تسجيل دخول)
Route::prefix('quran')->name('quran.')->group(function () {
    Route::get('/', [QuranController::class, 'index'])->name('index');
    Route::get('/page/{pageNumber}', [QuranController::class, 'showPage'])->name('page');
    Route::get('/surah/{surahId}', [QuranController::class, 'showSurah'])->name('surah');
    Route::get('/juz/{juzNumber}', [QuranController::class, 'showJuz'])->name('juz');

    // البحث
    Route::get('/search', [QuranSearchController::class, 'index'])->name('search.index');
    Route::post('/search', [QuranSearchController::class, 'search'])->name('search');

    // API Routes للاستخدام الداخلي (JSON)
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/page/{pageNumber}', [QuranController::class, 'apiGetPage'])->name('page');
        Route::get('/verse/{surahId}/{verseNumber}', [QuranController::class, 'apiGetVerse'])->name('verse');
        Route::post('/search', [QuranSearchController::class, 'apiSearch'])->name('search');
        Route::get('/suggestions', [QuranSearchController::class, 'apiSuggestions'])->name('suggestions');
    });
});
