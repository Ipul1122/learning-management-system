<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\ForgotPasswordOtpController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisterOtpController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Registrasi Peserta dengan Verifikasi OTP Email (Google App Password)
    Route::get('register', [RegisterOtpController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisterOtpController::class, 'sendOtp']);

    Route::get('register/verify-otp', [RegisterOtpController::class, 'showVerifyForm'])
        ->name('register.otp');

    Route::post('register/verify-otp', [RegisterOtpController::class, 'verifyOtp'])
        ->name('register.otp.verify');

    Route::post('register/resend-otp', [RegisterOtpController::class, 'resendOtp'])
        ->name('register.otp.resend');

    // Login
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Lupa Password & Reset Menggunakan Kode OTP Email
    Route::get('forgot-password', [ForgotPasswordOtpController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [ForgotPasswordOtpController::class, 'sendOtp'])
        ->name('password.email');

    Route::get('reset-password-otp', [ForgotPasswordOtpController::class, 'showResetForm'])
        ->name('password.reset.otp');

    Route::post('reset-password-otp', [ForgotPasswordOtpController::class, 'resetPassword'])
        ->name('password.reset.otp.update');

    Route::post('forgot-password/resend-otp', [ForgotPasswordOtpController::class, 'resendOtp'])
        ->name('password.reset.otp.resend');

    // Route legacy Breeze token (kompatibilitas link lama)
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
