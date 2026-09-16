<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpMail;
use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisterOtpController extends Controller
{
    /**
     * Tampilkan formulir pendaftaran peserta baru.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Validasi pendaftaran dan kirimkan kode OTP 6 digit ke email pendaftar.
     */
    public function sendOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $otp = EmailOtp::generate(
            email: $request->email,
            type: 'register',
            metadata: [
                'name' => $request->name,
                'password' => Hash::make($request->password),
            ],
            expiryMinutes: 10
        );

        try {
            Mail::to($request->email)->send(new SendOtpMail(
                otpCode: $otp->otp_code,
                type: 'register',
                userName: $request->name,
                expiresInMinutes: 10
            ));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirimkan email OTP registrasi: '.$e->getMessage());
        }

        session(['otp_register_email' => $request->email]);

        return redirect()
            ->route('register.otp')
            ->with('status', 'Kode OTP verifikasi 6 digit telah dikirimkan ke email Anda. Silakan periksa kotak masuk atau folder spam.');
    }

    /**
     * Tampilkan formulir verifikasi OTP pendaftaran.
     */
    public function showVerifyForm(Request $request): View|RedirectResponse
    {
        $email = session('otp_register_email') ?? $request->query('email');

        if (! $email) {
            return redirect()->route('register');
        }

        return view('auth.verify-register-otp', compact('email'));
    }

    /**
     * Verifikasi kode OTP dan selesaikan pembuatan akun peserta.
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp_code' => ['required', 'string', 'size:6'],
        ]);

        $otp = EmailOtp::verify($request->email, $request->otp_code, 'register');

        if (! $otp) {
            return back()
                ->withErrors(['otp_code' => 'Kode OTP tidak valid atau telah kedaluwarsa. Silakan masukkan kode yang sesuai atau kirim ulang kode baru.'])
                ->withInput();
        }

        $metadata = $otp->metadata ?? [];

        // Buat akun resmi peserta
        $user = User::create([
            'name' => $metadata['name'] ?? 'Peserta Pelatihan',
            'email' => $request->email,
            'password' => $metadata['password'] ?? Hash::make(Str::random(16)),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Berikan role peserta secara default
        $role = Role::firstOrCreate(['name' => 'peserta', 'guard_name' => 'web']);
        $user->assignRole($role);

        // Hapus OTP setelah berhasil diverifikasi
        $otp->delete();
        session()->forget('otp_register_email');

        event(new Registered($user));

        Auth::login($user);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Pendaftaran dan verifikasi akun berhasil! Selamat datang di LMS Multi-Cabang.');
    }

    /**
     * Kirim ulang kode OTP pendaftaran dengan proteksi cooldown 60 detik.
     */
    public function resendOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $activeOtp = EmailOtp::where('email', $request->email)
            ->where('type', 'register')
            ->latest()
            ->first();

        if (! $activeOtp) {
            return redirect()->route('register')->with('error', 'Sesi pendaftaran Anda telah berakhir. Silakan isi kembali formulir pendaftaran.');
        }

        // Proteksi cooldown 60 detik
        if ($activeOtp->created_at->diffInSeconds(now()) < 60) {
            $remaining = 60 - $activeOtp->created_at->diffInSeconds(now());

            return back()->with('error', "Harap tunggu {$remaining} detik sebelum meminta kode OTP baru.");
        }

        $newOtp = EmailOtp::generate(
            email: $request->email,
            type: 'register',
            metadata: $activeOtp->metadata,
            expiryMinutes: 10
        );

        try {
            Mail::to($request->email)->send(new SendOtpMail(
                otpCode: $newOtp->otp_code,
                type: 'register',
                userName: $newOtp->metadata['name'] ?? null,
                expiresInMinutes: 10
            ));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirimkan ulang email OTP registrasi: '.$e->getMessage());
        }

        return back()->with('status', 'Kode OTP baru telah berhasil dikirimkan ke email Anda.');
    }
}
