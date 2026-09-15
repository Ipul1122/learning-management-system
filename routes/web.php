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

// Admin Cabang Area
Route::middleware(['auth', 'role:admin-cabang'])
    ->prefix('cabang')
    ->name('cabang.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'cabangDashboard'])->name('dashboard');
    });

// Trainer Area
Route::middleware(['auth', 'role:trainer'])
    ->prefix('trainer')
    ->name('trainer.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'trainerDashboard'])->name('dashboard');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
