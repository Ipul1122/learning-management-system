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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
