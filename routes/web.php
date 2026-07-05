<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $countdownDate = \App\Models\PageContent::where('key', 'home_countdown_date')->value('value') ?? '2025-10-01T00:00';
    $countdownTitle = \App\Models\PageContent::where('key', 'home_countdown_title')->value('value') ?? 'MENUJU ROTASI OKTOBER 2025';
    return view('welcome', compact('countdownDate', 'countdownTitle'));
});

Route::get('/tentang', function () {
    return view('public.tentang');
});

Route::get('/tahapan', function () {
    $timelines = \App\Models\Timeline::orderBy('order')->get();
    return view('public.tahapan', compact('timelines'));
});

Route::get('/struktur', function () {
    $stakeholders = \App\Models\Stakeholder::orderBy('order')->get();
    $divisions = \App\Models\Division::with('members')->orderBy('order')->get();
    return view('public.struktur', compact('stakeholders', 'divisions'));
});

Route::get('/galeri', function () {
    $galleries = \App\Models\Gallery::orderBy('order')->get();
    $testimonials = \App\Models\Testimonial::orderBy('order')->get();
    return view('public.galeri', compact('galleries', 'testimonials'));
});

Route::get('/download', function () {
    $downloads = \App\Models\Download::orderBy('order')->get();
    return view('public.download', compact('downloads'));
});

Route::get('/kontak', function () {
    return view('public.kontak');
});

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SubmissionController;

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    
    Route::post('/submissions', [SubmissionController::class, 'store'])->name('submissions.store');
    Route::post('/submissions/{submission}/evaluate', [SubmissionController::class, 'evaluate'])->name('submissions.evaluate');

    // Peserta Routes
    Route::get('/dashboard/tasks', [DashboardController::class, 'pesertaTasks'])->name('peserta.tasks');
    Route::get('/dashboard/submissions', [DashboardController::class, 'pesertaSubmissions'])->name('peserta.submissions');
    Route::get('/dashboard/downloads', [DashboardController::class, 'pesertaDocuments'])->name('peserta.downloads');

    // Peserta Profile Route
    Route::get('/profile', [ProfileController::class, 'edit'])->name('peserta.profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('peserta.profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('peserta.profile.destroy');
    
    // Mentor Routes
    Route::get('/accounts/divisi-mentor/dashboard', [DashboardController::class, 'mentorDashboard'])->name('dashboard.mentor');
    Route::get('/accounts/divisi-mentor/peserta', [DashboardController::class, 'mentorPeserta'])->name('mentor.peserta');
    Route::get('/accounts/divisi-mentor/approvals', [DashboardController::class, 'mentorApprovals'])->name('mentor.approvals');
    Route::get('/accounts/divisi-mentor/submissions', [DashboardController::class, 'mentorSubmissions'])->name('mentor.submissions');
    Route::get('/accounts/divisi-mentor/profile', [ProfileController::class, 'edit'])->name('mentor.profile.edit');
    Route::patch('/accounts/divisi-mentor/profile', [ProfileController::class, 'update'])->name('mentor.profile.update');
    Route::delete('/accounts/divisi-mentor/profile', [ProfileController::class, 'destroy'])->name('mentor.profile.destroy');
    Route::get('/accounts/divisi-mentor/exit-history', [App\Http\Controllers\ExitPermissionController::class, 'history'])->name('mentor.exit.history');
    
    Route::post('/accounts/divisi-mentor/approvals/{id}/approve', [\App\Http\Controllers\MentorApprovalController::class, 'approve'])->name('mentor.approvals.approve');
    Route::delete('/accounts/divisi-mentor/approvals/{id}/reject', [\App\Http\Controllers\MentorApprovalController::class, 'reject'])->name('mentor.approvals.reject');
    Route::get('/accounts/divisi-acara/dashboard', [DashboardController::class, 'acaraDashboard'])->name('dashboard.acara');
    Route::get('/accounts/divisi-acara/profile', [ProfileController::class, 'edit'])->name('acara.profile.edit');
    Route::patch('/accounts/divisi-acara/profile', [ProfileController::class, 'update'])->name('acara.profile.update');
    Route::delete('/accounts/divisi-acara/profile', [ProfileController::class, 'destroy'])->name('acara.profile.destroy');
    Route::get('/accounts/divisi-acara/exit-history', [App\Http\Controllers\ExitPermissionController::class, 'history'])->name('acara.exit.history');
    Route::get('/accounts/divisi-acara/tasks', [DashboardController::class, 'acaraTasks'])->name('acara.tasks');
    Route::get('/accounts/divisi-acara/submissions', [DashboardController::class, 'acaraSubmissions'])->name('acara.submissions');
    
    // Keamanan Routes
    Route::get('/accounts/divisi-keamanan/dashboard', [DashboardController::class, 'keamananDashboard'])->name('dashboard.keamanan');
    Route::get('/accounts/divisi-keamanan/profile', [ProfileController::class, 'edit'])->name('keamanan.profile.edit');
    Route::patch('/accounts/divisi-keamanan/profile', [ProfileController::class, 'update'])->name('keamanan.profile.update');
    Route::delete('/accounts/divisi-keamanan/profile', [ProfileController::class, 'destroy'])->name('keamanan.profile.destroy');
    Route::get('/accounts/divisi-keamanan/scanner', [App\Http\Controllers\ExitPermissionController::class, 'scanner'])->name('keamanan.exit.scanner');
    Route::get('/accounts/divisi-keamanan/user-info/{id}', [App\Http\Controllers\ExitPermissionController::class, 'getUserInfo'])->name('keamanan.user.info');
    Route::post('/accounts/divisi-keamanan/exit-permissions', [App\Http\Controllers\ExitPermissionController::class, 'store'])->name('keamanan.exit.store');
    Route::delete('/accounts/divisi-keamanan/exit-permissions/{id}', [App\Http\Controllers\ExitPermissionController::class, 'destroy'])->name('keamanan.exit.destroy');
    Route::get('/accounts/divisi-keamanan/exit-history', [App\Http\Controllers\ExitPermissionController::class, 'history'])->name('keamanan.exit.history');

    // Panitia Routes
    Route::get('/accounts/panitia/dashboard', [DashboardController::class, 'panitiaDashboard'])->name('dashboard.panitia');
    Route::get('/accounts/panitia/profile', [ProfileController::class, 'edit'])->name('panitia.profile.edit');
    Route::patch('/accounts/panitia/profile', [ProfileController::class, 'update'])->name('panitia.profile.update');
    Route::delete('/accounts/panitia/profile', [ProfileController::class, 'destroy'])->name('panitia.profile.destroy');
    Route::get('/accounts/panitia/exit-history', [App\Http\Controllers\ExitPermissionController::class, 'history'])->name('panitia.exit.history');
    
    // Admin Routes
    Route::prefix('accounts/admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        
        Route::get('/passwords', [\App\Http\Controllers\Admin\SectorPasswordController::class, 'index'])->name('passwords.index');
        Route::post('/passwords', [\App\Http\Controllers\Admin\SectorPasswordController::class, 'store'])->name('passwords.store');
        Route::put('/passwords/{id}', [\App\Http\Controllers\Admin\SectorPasswordController::class, 'update'])->name('passwords.update');
        Route::delete('/passwords/{id}', [\App\Http\Controllers\Admin\SectorPasswordController::class, 'destroy'])->name('passwords.destroy');

        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::delete('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/approvals', [\App\Http\Controllers\Admin\ApprovalController::class, 'index'])->name('approvals.index');
        Route::post('/approvals/{id}/approve', [\App\Http\Controllers\Admin\ApprovalController::class, 'approve'])->name('approvals.approve');
        Route::delete('/approvals/{id}/reject', [\App\Http\Controllers\Admin\ApprovalController::class, 'reject'])->name('approvals.reject');


        Route::prefix('cms')->name('cms.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\CMSController::class, 'index'])->name('index');
            Route::get('/downloads', [\App\Http\Controllers\Admin\CMSController::class, 'downloads'])->name('downloads.index');
            Route::post('/timeline', [\App\Http\Controllers\Admin\CMSController::class, 'storeTimeline'])->name('timeline.store');
        Route::delete('/timeline/{timeline}', [\App\Http\Controllers\Admin\CMSController::class, 'destroyTimeline'])->name('timeline.destroy');
        
        Route::post('/stakeholder', [\App\Http\Controllers\Admin\CMSController::class, 'storeStakeholder'])->name('stakeholder.store');
        Route::delete('/stakeholder/{stakeholder}', [\App\Http\Controllers\Admin\CMSController::class, 'destroyStakeholder'])->name('stakeholder.destroy');
        
        Route::post('/gallery', [\App\Http\Controllers\Admin\CMSController::class, 'storeGallery'])->name('gallery.store');
        Route::delete('/gallery/{gallery}', [\App\Http\Controllers\Admin\CMSController::class, 'destroyGallery'])->name('gallery.destroy');
        
        Route::post('/division', [\App\Http\Controllers\Admin\CMSController::class, 'storeDivision'])->name('division.store');
        Route::delete('/division/{division}', [\App\Http\Controllers\Admin\CMSController::class, 'destroyDivision'])->name('division.destroy');
        Route::post('/division/{division}/member', [\App\Http\Controllers\Admin\CMSController::class, 'storeDivisionMember'])->name('division.member.store');
        Route::delete('/division-member/{member}', [\App\Http\Controllers\Admin\CMSController::class, 'destroyDivisionMember'])->name('division.member.destroy');
        
        Route::post('/testimonial', [\App\Http\Controllers\Admin\CMSController::class, 'storeTestimonial'])->name('testimonial.store');
        Route::delete('/testimonial/{testimonial}', [\App\Http\Controllers\Admin\CMSController::class, 'destroyTestimonial'])->name('testimonial.destroy');
        
        Route::post('/download', [\App\Http\Controllers\Admin\CMSController::class, 'storeDownload'])->name('download.store');
        Route::delete('/download/{download}', [\App\Http\Controllers\Admin\CMSController::class, 'destroyDownload'])->name('download.destroy');
        
        Route::post('/pagecontent', [\App\Http\Controllers\Admin\CMSController::class, 'updatePageContent'])->name('pagecontent.update');
        });
    });
});

require __DIR__.'/auth.php';
