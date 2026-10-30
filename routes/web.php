<?php

use App\Http\Controllers\Admin\ContactInquiryController as AdminContactInquiryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LeadershipProfileController as AdminLeadershipProfileController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\MembershipApplicationController as AdminMembershipApplicationController;
use App\Http\Controllers\Admin\NoticeController as AdminNoticeController;
use App\Http\Controllers\Admin\StaffController as AdminStaffController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactInquiryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\MembershipApplicationStatusController;
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
Route::get('/savings', [FrontendController::class, 'savings'])->name('savings');
Route::get('/loans', [FrontendController::class, 'loans'])->name('loans');
Route::get('/notices', [FrontendController::class, 'notices'])->name('notices');
Route::get('/notices/{notice}', [FrontendController::class, 'showNotice'])->name('notices.show');
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

    Route::resource('notices', AdminNoticeController::class)
        ->except('show')
        ->middleware('permission:notices.manage');

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
