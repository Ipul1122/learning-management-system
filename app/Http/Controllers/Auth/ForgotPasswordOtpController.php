<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpMail;
use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ForgotPasswordOtpController extends Controller
{
    /**
     * Tampilkan formulir permintaan lupa kata sandi.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Validasi email pengguna dan kirimkan kode OTP 6 digit untuk reset kata sandi.
     */
    public function sendOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'Alamat email ini tidak terdaftar dalam sistem LMS kami.',
        ]);

        $user = User::where('email', $request->email)->first();

        $otp = EmailOtp::generate(
            email: $request->email,
            type: 'password_reset',
            metadata: null,
            expiryMinutes: 10
        );

        try {
            Mail::to($request->email)->send(new SendOtpMail(
                otpCode: $otp->otp_code,
                type: 'password_reset',
                userName: $user?->name,
                expiresInMinutes: 10
            ));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirimkan email OTP reset password: '.$e->getMessage());
        }

        session(['otp_reset_email' => $request->email]);

        return redirect()
            ->route('password.reset.otp')
            ->with('status', 'Kode OTP 6 digit telah dikirimkan ke email Anda. Silakan masukkan kode tersebut beserta kata sandi baru Anda.');
    }

    /**
     * Tampilkan formulir input OTP dan kata sandi baru.
     */
    public function showResetForm(Request $request): View
    {
        $email = session('otp_reset_email') ?? $request->query('email');

        return view('auth.reset-password-otp', compact('email'));
    }

    /**
     * Verifikasi kode OTP dan perbarui kata sandi pengguna.
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'otp_code' => ['required', 'string', 'size:6'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'email.exists' => 'Alamat email tidak valid.',
            'otp_code.size' => 'Kode OTP harus tepat berjumlah 6 digit angka.',
        ]);

        $otp = EmailOtp::verify($request->email, $request->otp_code, 'password_reset');

        if (! $otp) {
            return back()
                ->withErrors(['otp_code' => 'Kode OTP pemulihan kata sandi tidak valid atau telah kedaluwarsa. Silakan minta kode baru.'])
                ->withInput($request->only('email'));
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Hapus kode OTP yang sudah dipakai
        $otp->delete();
        session()->forget('otp_reset_email');

        return redirect()
            ->route('login')
            ->with('success', 'Kata sandi akun Anda berhasil diperbarui! Silakan masuk menggunakan kata sandi baru.');
    }

    /**
     * Kirim ulang kode OTP reset kata sandi dengan proteksi cooldown 60 detik.
     */
    public function resendOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $lastOtp = EmailOtp::where('email', $request->email)
            ->where('type', 'password_reset')
            ->latest()
            ->first();

        if ($lastOtp && $lastOtp->created_at->diffInSeconds(now()) < 60) {
            $remaining = 60 - $lastOtp->created_at->diffInSeconds(now());

            return back()->with('error', "Harap tunggu {$remaining} detik sebelum meminta kode OTP baru.");
        }

        $user = User::where('email', $request->email)->first();

        $newOtp = EmailOtp::generate(
            email: $request->email,
            type: 'password_reset',
            metadata: null,
            expiryMinutes: 10
        );

        try {
            Mail::to($request->email)->send(new SendOtpMail(
                otpCode: $newOtp->otp_code,
                type: 'password_reset',
                userName: $user?->name,
                expiresInMinutes: 10
            ));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirimkan ulang email OTP reset password: '.$e->getMessage());
        }

        return back()->with('status', 'Kode OTP pemulihan baru telah berhasil dikirimkan ke email Anda.');
    }
}
