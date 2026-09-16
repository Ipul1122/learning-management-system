<?php

use App\Http\Controllers\AdminCabang\ActivityLogController as CabangActivityLogController;
use App\Http\Controllers\AdminCabang\ClassController as CabangClassController;
use App\Http\Controllers\AdminCabang\ClassSessionController as CabangClassSessionController;
use App\Http\Controllers\AdminCabang\TrainerController as CabangTrainerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Peserta\ClassCatalogController;
use App\Http\Controllers\Peserta\GraduationController;
use App\Http\Controllers\Peserta\QuizAttemptController;
use App\Http\Controllers\Peserta\StudyRoomController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicCertificateController;
use App\Http\Controllers\SuperAdmin\ActivityLogController;
use App\Http\Controllers\SuperAdmin\AdminCabangController;
use App\Http\Controllers\SuperAdmin\BranchController;
use App\Http\Controllers\Trainer\ClassScheduleController;
use App\Http\Controllers\Trainer\GraduationReviewController;
use App\Http\Controllers\Trainer\QuestionBankController;
use App\Http\Controllers\Trainer\QuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Central Dashboard Dispatcher (Peserta Default)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Super Admin Area
Route::middleware(['auth', 'role:super-admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

        // Master Cabang
        Route::patch('/branches/{branch}/toggle-status', [BranchController::class, 'toggleStatus'])->name('branches.toggle-status');
        Route::resource('branches', BranchController::class)->except(['show']);

        // Admin Cabang
        Route::patch('/admins/{admin}/toggle-status', [AdminCabangController::class, 'toggleStatus'])->name('admins.toggle-status');
        Route::resource('admins', AdminCabangController::class)->except(['show']);

        // Log Aktivitas
        Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs.index');
        Route::get('/logs/{log}', [ActivityLogController::class, 'show'])->name('logs.show');
    });

// Admin Cabang Area
Route::middleware(['auth', 'role:admin-cabang'])
    ->prefix('cabang')
    ->name('cabang.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'cabangDashboard'])->name('dashboard');

        // Master Instruktur / Trainer Cabang
        Route::patch('trainers/{trainer}/toggle-status', [CabangTrainerController::class, 'toggleStatus'])->name('trainers.toggle-status');
        Route::resource('trainers', CabangTrainerController::class)->except(['show']);

        // Kelas Pelatihan Cabang
        Route::patch('classes/{class}/update-status', [CabangClassController::class, 'updateStatus'])->name('classes.update-status');
        Route::resource('classes', CabangClassController::class);

        // Sesi Pertemuan Kelas
        Route::resource('classes.sessions', CabangClassSessionController::class)->except(['show']);

        // Log Aktivitas Internal Cabang
        Route::get('logs', [CabangActivityLogController::class, 'index'])->name('logs.index');
        Route::get('logs/{log}', [CabangActivityLogController::class, 'show'])->name('logs.show');
    });

// Trainer / Instruktur Area (PRD 3.1, 3.2, 3.5, 3.6)
Route::middleware(['auth', 'role:trainer'])
    ->prefix('trainer')
    ->name('trainer.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'trainerDashboard'])->name('dashboard');

        // Bank Soal Setara (PRD 3.1)
        Route::resource('questions', QuestionBankController::class)->except(['show']);

        // Kelola Paket Kuis (PRD 3.2)
        Route::resource('quizzes', QuizController::class);

        // Kelas & Sesi Pembelajaran + Quick Zoom (PRD 3.6)
        Route::get('classes', [ClassScheduleController::class, 'index'])->name('classes.index');
        Route::get('classes/{class}', [ClassScheduleController::class, 'show'])->name('classes.show');
        Route::patch('classes/{class}/sessions/{session}/zoom', [ClassScheduleController::class, 'updateZoom'])->name('classes.sessions.zoom');

        // Verifikasi Kelulusan 20 JP & Penerbitan Sertifikat (Fase 5: PRD 3.5)
        Route::get('graduations', [GraduationReviewController::class, 'index'])->name('graduations.index');
        Route::get('graduations/{submission}', [GraduationReviewController::class, 'show'])->name('graduations.show');
        Route::post('graduations/{submission}/approve', [GraduationReviewController::class, 'approve'])->name('graduations.approve');
        Route::post('graduations/{submission}/reject', [GraduationReviewController::class, 'reject'])->name('graduations.reject');
    });

// Peserta Area (Fase 4 & 5: PRD 4.1 - 4.6)
Route::middleware(['auth', 'role:peserta'])
    ->prefix('peserta')
    ->name('peserta.')
    ->group(function () {
        // Katalog Kelas Pelatihan & Pendaftaran Concurrency
        Route::get('catalog', [ClassCatalogController::class, 'index'])->name('catalog.index');
        Route::get('catalog/{class}', [ClassCatalogController::class, 'show'])->name('catalog.show');
        Route::post('catalog/{class}/enroll', [ClassCatalogController::class, 'enroll'])->name('catalog.enroll');

        // Ruang Belajar, Silabus Sesi & 1-Klik Masuk Zoom
        Route::get('study', [StudyRoomController::class, 'index'])->name('study.index');
        Route::get('study/{class}', [StudyRoomController::class, 'show'])->name('study.show');
        Route::post('study/{class}/sessions/{session}/zoom', [StudyRoomController::class, 'joinZoom'])->name('study.zoom');
        Route::post('study/{class}/request-review', [GraduationController::class, 'requestReview'])->name('study.request-review');

        // Paket Kuis Interaktif, Auto-Grading & Pembahasan
        Route::get('study/{class}/quizzes/{quiz}', [QuizAttemptController::class, 'show'])->name('quizzes.show');
        Route::post('study/{class}/quizzes/{quiz}/start', [QuizAttemptController::class, 'start'])->name('quizzes.start');
        Route::get('study/{class}/quizzes/{quiz}/take/{attempt}', [QuizAttemptController::class, 'take'])->name('quizzes.take');
        Route::post('study/{class}/quizzes/{quiz}/submit/{attempt}', [QuizAttemptController::class, 'submit'])->name('quizzes.submit');
        Route::get('study/{class}/quizzes/{quiz}/result/{attempt}', [QuizAttemptController::class, 'result'])->name('quizzes.result');

        // Hasil Kelulusan 20 JP & Unduh E-Sertifikat PDF (Fase 5: PRD 4.5)
        Route::get('certificates', [GraduationController::class, 'index'])->name('certificates.index');
        Route::get('certificates/{submission}', [GraduationController::class, 'show'])->name('certificates.show');
        Route::get('certificates/{submission}/download', [GraduationController::class, 'download'])->name('certificates.download');
        Route::get('certificates/{submission}/preview', [GraduationController::class, 'preview'])->name('certificates.preview');
        Route::post('certificates/{class}/request-review', [GraduationController::class, 'requestReview'])->name('certificates.requestReview');
    });

// Verifikasi Publik Keaslian E-Sertifikat QR Code (Terbuka Tanpa Otentikasi)
Route::get('/certificates/verify/{certificate_number}', [PublicCertificateController::class, 'verify'])
    ->name('certificates.verify');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
