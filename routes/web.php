<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Central Dashboard Dispatcher (Peserta Default)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

use App\Http\Controllers\SuperAdmin\ActivityLogController;
use App\Http\Controllers\SuperAdmin\AdminCabangController;
use App\Http\Controllers\SuperAdmin\BranchController;

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

use App\Http\Controllers\AdminCabang\ActivityLogController as CabangActivityLogController;
use App\Http\Controllers\AdminCabang\ClassController as CabangClassController;
use App\Http\Controllers\AdminCabang\ClassSessionController as CabangClassSessionController;
use App\Http\Controllers\AdminCabang\TrainerController as CabangTrainerController;

// Admin Cabang Area
Route::middleware(['auth', 'role:admin-cabang'])
    ->prefix('cabang')
    ->name('cabang.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'cabangDashboard'])->name('dashboard');

        // Master Trainer Cabang
        Route::patch('trainers/{trainer}/toggle-status', [CabangTrainerController::class, 'toggleStatus'])->name('trainers.toggle-status');
        Route::resource('trainers', CabangTrainerController::class)->except(['show']);

        // Kelas Pelatihan
        Route::patch('classes/{class}/update-status', [CabangClassController::class, 'updateStatus'])->name('classes.update-status');
        Route::resource('classes', CabangClassController::class);

        // Sesi Pertemuan Kelas & Zoom Meeting
        Route::post('classes/{class}/sessions', [CabangClassSessionController::class, 'store'])->name('sessions.store');
        Route::get('classes/{class}/sessions/{session}/edit', [CabangClassSessionController::class, 'edit'])->name('sessions.edit');
        Route::put('classes/{class}/sessions/{session}', [CabangClassSessionController::class, 'update'])->name('sessions.update');
        Route::delete('classes/{class}/sessions/{session}', [CabangClassSessionController::class, 'destroy'])->name('sessions.destroy');

        // Log Aktivitas Internal Cabang
        Route::get('logs', [CabangActivityLogController::class, 'index'])->name('logs.index');
        Route::get('logs/{log}', [CabangActivityLogController::class, 'show'])->name('logs.show');
    });

use App\Http\Controllers\Trainer\ClassScheduleController;
use App\Http\Controllers\Trainer\QuestionBankController;
use App\Http\Controllers\Trainer\QuizController;

// Trainer Area
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
    });

use App\Http\Controllers\Peserta\ClassCatalogController;
use App\Http\Controllers\Peserta\QuizAttemptController;
use App\Http\Controllers\Peserta\StudyRoomController;

// Peserta Area (Fase 4: PRD 4.1 - 4.6)
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

        // Paket Kuis Interaktif, Auto-Grading & Pembahasan
        Route::get('study/{class}/quizzes/{quiz}', [QuizAttemptController::class, 'show'])->name('quizzes.show');
        Route::post('study/{class}/quizzes/{quiz}/start', [QuizAttemptController::class, 'start'])->name('quizzes.start');
        Route::get('study/{class}/quizzes/{quiz}/take/{attempt}', [QuizAttemptController::class, 'take'])->name('quizzes.take');
        Route::post('study/{class}/quizzes/{quiz}/submit/{attempt}', [QuizAttemptController::class, 'submit'])->name('quizzes.submit');
        Route::get('study/{class}/quizzes/{quiz}/result/{attempt}', [QuizAttemptController::class, 'result'])->name('quizzes.result');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
