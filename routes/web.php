<?php

use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AllocationPlanController;
use App\Http\Controllers\BookNameController;
use App\Http\Controllers\CompanyContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuotaController;
use App\Http\Controllers\ResourceLookupController;
use App\Http\Controllers\SchoolSupplyController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplyDetailController;
use App\Http\Controllers\TeacherGuideController;
use App\Http\Controllers\TeacherGuideDistributionController;
use App\Http\Controllers\TeacherGuideIssueController;
use App\Http\Controllers\TeacherGuideSummaryController;
use App\Http\Controllers\TextbookController;
use App\Http\Controllers\TownshipController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::put('/profile', 'update')->name('profile.update');

        Route::get('/password/change', 'passwordEdit')
            ->name('password.edit');

        Route::put('/password/change', 'passwordUpdate')
            ->name('password.update');
    });

    Route::resource('admin-users', AdminUserController::class)
        ->middlewareFor(
            ['create', 'store', 'edit', 'update', 'destroy'],
            'role:super'
        );

    Route::resource('townships', TownshipController::class)
        ->middlewareFor('destroy', 'role:super');

    Route::resource('academic-years', AcademicYearController::class)
        ->middlewareFor('destroy', 'role:super');
    Route::post('/academic-years/rollover', [AcademicYearController::class, 'rollover'])
        ->name('academic-years.rollover')
        ->middleware('role:super');

    Route::resource('grades', GradeController::class)
        ->middlewareFor('destroy', 'role:super');
    Route::get('/grades/{grade}/subjects', [GradeController::class, 'getSubjects'])
        ->name('grades.subjects');

    Route::prefix('lookups')->name('lookups.')->group(function () {
        Route::get('/categories', [ResourceLookupController::class, 'categories'])
            ->name('categories');
        Route::get('/allocation-for-textbook', [ResourceLookupController::class, 'allocationForTextbook'])
            ->name('allocation-for-textbook');
        Route::get('/previous-year-balance', [ResourceLookupController::class, 'previousYearBalance'])
            ->name('previous-year-balance');
        Route::get('/school-count', [ResourceLookupController::class, 'schoolCount'])
            ->name('school-count');
        Route::get('/school-supply-quantity', [ResourceLookupController::class, 'schoolSupplyQuantity'])
            ->name('school-supply-quantity');
        Route::get('/teacher-guide-receipt', [ResourceLookupController::class, 'teacherGuideReceipt'])
            ->name('teacher-guide-receipt');
    });

    Route::resource('book-names', BookNameController::class)
        ->middlewareFor('destroy', 'role:super');

    Route::resource('textbook', TextbookController::class)
        ->middlewareFor('destroy', 'role:super');

    Route::resource('stocks', StockController::class)
        ->middlewareFor('destroy', 'role:super');

    Route::resource('quota', QuotaController::class)
        ->middlewareFor('destroy', 'role:super');

    Route::resource('teacher-guides', TeacherGuideController::class)
        ->middlewareFor('destroy', 'role:super');

    Route::resource('company-contacts', CompanyContactController::class)
        ->middlewareFor('destroy', 'role:super');

    Route::resource('school-supplies', SchoolSupplyController::class)
        ->middlewareFor('destroy', 'role:super');

    Route::resource('supply-details', SupplyDetailController::class)
        ->middlewareFor('destroy', 'role:super');

    Route::resource(
        'teacher-guide-distributions',
        TeacherGuideDistributionController::class
    )
        ->except(['show'])
        ->middlewareFor('destroy', 'role:super');

    Route::resource(
        'teacher-guide-issues',
        TeacherGuideIssueController::class
    )
        ->middlewareFor('destroy', 'role:super');

    Route::resource(
        'teacher-guide-summaries',
        TeacherGuideSummaryController::class
    )
        ->except(['show'])
        ->middlewareFor('destroy', 'role:super');

    Route::resource(
        'allocation-plans',
        AllocationPlanController::class
    )
        ->middlewareFor('destroy', 'role:super');
});
