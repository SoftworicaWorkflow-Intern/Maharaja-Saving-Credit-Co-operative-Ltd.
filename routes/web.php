<?php

use App\Http\Controllers\Admin\AnnualReportController as AdminAnnualReportController;
use App\Http\Controllers\Admin\ContactInquiryController as AdminContactInquiryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\LeadershipProfileController as AdminLeadershipProfileController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\MembershipApplicationController as AdminMembershipApplicationController;
use App\Http\Controllers\Admin\NoticeController as AdminNoticeController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ReportStatisticController as AdminReportStatisticController;
use App\Http\Controllers\Admin\ReportTaxonomyController as AdminReportTaxonomyController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\StaffController as AdminStaffController;
use App\Http\Controllers\AnnualReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactInquiryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\MemberDirectoryController;
use App\Http\Controllers\MembershipApplicationStatusController;
use App\Http\Controllers\ReportController as PublicReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/leadership/{leader}/portrait', [FrontendController::class, 'leadershipPortrait'])
    ->whereIn('leader', ['chairperson', 'secretary'])
    ->name('leadership.portrait');
Route::get('/leadership/{leader}', [FrontendController::class, 'showLeadershipMessage'])
    ->whereIn('leader', ['chairperson', 'secretary'])
    ->name('leadership.show');
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/about/vision-mission', [FrontendController::class, 'visionMission'])->name('about.vision-mission');
Route::get('/about/board-of-directors', [FrontendController::class, 'boardOfDirectors'])->name('about.board-of-directors');
Route::get('/about/other-committees', [FrontendController::class, 'otherCommittees'])->name('about.other-committees');
Route::get('/about/employees', [FrontendController::class, 'employees'])->name('about.employees');
Route::get('/services', [FrontendController::class, 'services'])->name('services');
Route::get('/services/{service:slug}', [FrontendController::class, 'showService'])->name('services.show');
Route::get('/savings', [FrontendController::class, 'savings'])->name('savings');
Route::get('/loans', [FrontendController::class, 'loans'])->name('loans');
Route::get('/notices', [FrontendController::class, 'notices'])->name('notices');
Route::get('/notices/{notice}', [FrontendController::class, 'showNotice'])->name('notices.show');
Route::get('/members', MemberDirectoryController::class)->name('members.index');
Route::get('/members/{member}/number', [MemberDirectoryController::class, 'showNumber'])->name('members.number');
Route::get('/reports', [PublicReportController::class, 'index'])->name('reports.index');
Route::get('/annual-reports', [AnnualReportController::class, 'index'])->name('annual-reports.index');
Route::get('/financial-reports', [PublicReportController::class, 'category'])
    ->defaults('slug', 'financial-reports')
    ->name('reports.financial');
Route::get('/audit-reports', [PublicReportController::class, 'category'])
    ->defaults('slug', 'audit-reports')
    ->name('reports.audit');
Route::get('/agm-reports', [PublicReportController::class, 'category'])
    ->defaults('slug', 'agm-reports')
    ->name('reports.agm');
Route::get('/progress-reports', [PublicReportController::class, 'category'])
    ->defaults('slug', 'progress-reports')
    ->name('reports.progress');
Route::get('/policies-guidelines', [PublicReportController::class, 'category'])
    ->defaults('slug', 'policies-guidelines')
    ->name('reports.policies');
Route::get('/meeting-decisions', [PublicReportController::class, 'category'])
    ->defaults('slug', 'meeting-decisions')
    ->name('reports.decisions');
Route::get('/other-publications', [PublicReportController::class, 'category'])
    ->defaults('slug', 'other-publications')
    ->name('reports.other');
Route::get('/reports/category/{slug}', [PublicReportController::class, 'category'])
    ->name('reports.category.custom');
Route::get('/annual-reports/{report:slug}/chapters/{chapter}/image', [AnnualReportController::class, 'chapterImage'])
    ->name('annual-reports.chapters.image');
Route::get('/annual-reports/{report:slug}', [AnnualReportController::class, 'show'])->name('annual-reports.show');
Route::get('/reports/{report:slug}/document', [PublicReportController::class, 'document'])->name('reports.document');
Route::get('/reports/{report:slug}/download', [PublicReportController::class, 'download'])->name('reports.download');
Route::get('/reports/{report:slug}/image', [PublicReportController::class, 'image'])->name('reports.image');
Route::get('/reports/{report:slug}/attachments/{attachment}', [PublicReportController::class, 'attachment'])->name('reports.attachments.download');
Route::get('/reports/{report:slug}', [PublicReportController::class, 'show'])->name('reports.show');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactInquiryController::class, 'store'])->middleware('throttle:5,1')->name('contact.submit');

Route::post('/language/{locale}', function (Request $request, string $locale) {
    abort_unless(in_array($locale, ['en', 'ne'], true), 404);

    $request->session()->put('locale', $locale);

    return back();
})->name('language.switch');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.submit');
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');
    Route::get('/annual-reports', [AdminAnnualReportController::class, 'index'])
        ->middleware('permission:reports.manage')
        ->name('annual-reports.index');
    Route::get('/annual-reports/create', [AdminAnnualReportController::class, 'create'])
        ->middleware('permission:reports.manage')
        ->name('annual-reports.create');

    Route::resource('notices', AdminNoticeController::class)
        ->except('show')
        ->middleware('permission:notices.manage');

    Route::resource('reports', AdminReportController::class)
        ->except('show')
        ->middleware('permission:reports.manage');

    Route::middleware('permission:reports.manage')->group(function () {
        Route::get('/report-statistics', [AdminReportStatisticController::class, 'index'])->name('report-statistics.index');
        Route::post('/report-statistics', [AdminReportStatisticController::class, 'store'])->name('report-statistics.store');
        Route::put('/report-statistics/{reportStatistic}', [AdminReportStatisticController::class, 'update'])->name('report-statistics.update');
        Route::delete('/report-statistics/{reportStatistic}', [AdminReportStatisticController::class, 'destroy'])->name('report-statistics.destroy');
        Route::get('/report-taxonomy', [AdminReportTaxonomyController::class, 'index'])->name('report-taxonomy.index');
        Route::post('/report-categories', [AdminReportTaxonomyController::class, 'storeCategory'])->name('report-categories.store');
        Route::put('/report-categories/{reportCategory}', [AdminReportTaxonomyController::class, 'updateCategory'])->name('report-categories.update');
        Route::delete('/report-categories/{reportCategory}', [AdminReportTaxonomyController::class, 'destroyCategory'])->name('report-categories.destroy');
        Route::post('/fiscal-years', [AdminReportTaxonomyController::class, 'storeFiscalYear'])->name('fiscal-years.store');
        Route::put('/fiscal-years/{fiscalYear}', [AdminReportTaxonomyController::class, 'updateFiscalYear'])->name('fiscal-years.update');
        Route::delete('/fiscal-years/{fiscalYear}', [AdminReportTaxonomyController::class, 'destroyFiscalYear'])->name('fiscal-years.destroy');
    });

    Route::resource('services', AdminServiceController::class)
        ->except('show')
        ->middleware('permission:website.manage');

    Route::resource('faqs', AdminFaqController::class)
        ->except('show')
        ->middleware('permission:website.manage');

    Route::middleware('permission:applications.view')->group(function () {
        Route::get('/applications', [AdminMembershipApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/{membershipApplication}', [AdminMembershipApplicationController::class, 'show'])->name('applications.show');
        Route::get('/application-documents/{document}', [AdminMembershipApplicationController::class, 'downloadDocument'])->name('applications.documents.download');
    });

    Route::post('/applications/{membershipApplication}/review/{decision}', [AdminMembershipApplicationController::class, 'review'])
        ->middleware('permission:applications.review')
        ->whereIn('decision', ['start_review', 'request_documents', 'approve', 'reject'])
        ->name('applications.review');

    Route::middleware('permission:members.view')->group(function () {
        Route::get('/members', [AdminMemberController::class, 'index'])->name('members.index');
        Route::get('/members/{member}', [AdminMemberController::class, 'show'])->name('members.show');
    });

    Route::put('/members/{member}', [AdminMemberController::class, 'update'])
        ->middleware('permission:members.update')
        ->name('members.update');
    Route::patch('/members/{member}/status', [AdminMemberController::class, 'updateStatus'])
        ->middleware('permission:members.update')
        ->name('members.status');

    Route::middleware('permission:staff.manage')->group(function () {
        Route::get('/staff', [AdminStaffController::class, 'index'])->name('staff.index');
        Route::get('/leadership', [AdminLeadershipProfileController::class, 'index'])->name('leadership.index');
        Route::put('/leadership/{leader}', [AdminLeadershipProfileController::class, 'update'])
            ->whereIn('leader', ['chairperson', 'secretary'])
            ->name('leadership.update');
        Route::post('/staff/roles', [AdminStaffController::class, 'assignRole'])->name('staff.roles.assign');
        Route::put('/staff/roles/{role}/permissions', [AdminStaffController::class, 'updatePermissions'])->name('staff.roles.permissions');
    });

    Route::middleware('permission:contact_inquiries.manage')->group(function () {
        Route::get('/contact-inquiries', [AdminContactInquiryController::class, 'index'])->name('contact-inquiries.index');
        Route::get('/contact-inquiries/{contactInquiry}', [AdminContactInquiryController::class, 'show'])->name('contact-inquiries.show');
        Route::patch('/contact-inquiries/{contactInquiry}/reviewed', [AdminContactInquiryController::class, 'markReviewed'])->name('contact-inquiries.reviewed');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/membership/application', MembershipApplicationStatusController::class)->name('membership.application');
    Route::post('/membership/application/documents', [AdminMembershipApplicationController::class, 'storeAdditionalDocuments'])
        ->name('membership.application.documents.store');
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('member.active')->name('dashboard');
    Route::get('/dashboard/settings', [DashboardController::class, 'settings'])->middleware('member.active')->name('dashboard.settings');
});
