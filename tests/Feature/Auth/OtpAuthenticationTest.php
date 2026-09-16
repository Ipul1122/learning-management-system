<?php

use App\Mail\SendOtpMail;
use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

beforeEach(function () {
    /** @var TestCase $this */
    $this->seed();
    Mail::fake();
});

test('halaman pendaftaran dapat diakses oleh pengunjung', function () {
    /** @var TestCase $this */
    $response = $this->get(route('register'));

    $response->assertStatus(200);
    $response->assertSee('Pendaftaran Peserta Baru');
    $response->assertSee('Kirim Kode OTP Verifikasi');
});

test('pendaftaran form membangkitkan kode OTP 6 digit dan mengirim email', function () {
    /** @var TestCase $this */
    $payload = [
        'name' => 'Budi Santoso',
        'email' => 'budi.santoso@example.com',
        'password' => 'RahasiaBudi123!',
        'password_confirmation' => 'RahasiaBudi123!',
    ];

    $response = $this->post(route('register'), $payload);

    $response->assertRedirect(route('register.otp'));
    $response->assertSessionHas('otp_register_email', 'budi.santoso@example.com');

    // Belum dibuat di tabel users sebelum OTP diverifikasi
    $this->assertDatabaseMissing('users', [
        'email' => 'budi.santoso@example.com',
    ]);

    // Record OTP dibuat di email_otps
    $this->assertDatabaseHas('email_otps', [
        'email' => 'budi.santoso@example.com',
        'type' => 'register',
    ]);

    // Email dikirimkan dengan mailable SendOtpMail
    Mail::assertSent(SendOtpMail::class, function (SendOtpMail $mail) {
        return $mail->hasTo('budi.santoso@example.com')
            && $mail->type === 'register'
            && strlen($mail->otpCode) === 6;
    });
});

test('verifikasi kode OTP yang salah ditolak dengan pesan error', function () {
    /** @var TestCase $this */
    $otp = EmailOtp::generate('salah.otp@example.com', 'register', [
        'name' => 'Salah OTP',
        'password' => Hash::make('password123'),
    ]);

    $response = $this->withSession(['otp_register_email' => 'salah.otp@example.com'])
        ->post(route('register.otp.verify'), [
            'email' => 'salah.otp@example.com',
            'otp_code' => '000000', // salah
        ]);

    $response->assertSessionHasErrors('otp_code');

    $this->assertDatabaseMissing('users', [
        'email' => 'salah.otp@example.com',
    ]);
});

test('verifikasi kode OTP yang benar menyelesaikan pendaftaran dan mengaktifkan akun', function () {
    /** @var TestCase $this */
    $otp = EmailOtp::generate('sukses.otp@example.com', 'register', [
        'name' => 'Peserta Sukses OTP',
        'password' => Hash::make('SandiKuat123!'),
    ]);

    $response = $this->withSession(['otp_register_email' => 'sukses.otp@example.com'])
        ->post(route('register.otp.verify'), [
            'email' => 'sukses.otp@example.com',
            'otp_code' => $otp->otp_code,
        ]);

    $response->assertRedirect(route('dashboard'));

    // Akun dibuat di database
    $this->assertDatabaseHas('users', [
        'email' => 'sukses.otp@example.com',
        'name' => 'Peserta Sukses OTP',
        'status' => 'active',
    ]);

    $user = User::where('email', 'sukses.otp@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->hasRole('peserta'))->toBeTrue();
    expect($user->email_verified_at)->not->toBeNull();

    // User otomatis terotentikasi login
    $this->assertAuthenticatedAs($user);

    // Record OTP dibersihkan
    $this->assertDatabaseMissing('email_otps', [
        'id' => $otp->id,
    ]);
});

test('kode OTP yang telah kedaluwarsa ditolak saat verifikasi', function () {
    /** @var TestCase $this */
    $otp = EmailOtp::create([
        'email' => 'expired@example.com',
        'otp_code' => '123456',
        'type' => 'register',
        'metadata' => [
            'name' => 'Expired User',
            'password' => Hash::make('password123'),
        ],
        'expires_at' => now()->subMinutes(5), // sudah lewat 5 menit
    ]);

    $response = $this->post(route('register.otp.verify'), [
        'email' => 'expired@example.com',
        'otp_code' => '123456',
    ]);

    $response->assertSessionHasErrors('otp_code');
    $this->assertDatabaseMissing('users', ['email' => 'expired@example.com']);
});

test('lupa password mengirimkan kode OTP reset ke email pengguna terdaftar', function () {
    /** @var TestCase $this */
    $user = User::where('email', 'peserta1@lms.test')->first();

    $response = $this->post(route('password.email'), [
        'email' => $user->email,
    ]);

    $response->assertRedirect(route('password.reset.otp'));
    $response->assertSessionHas('otp_reset_email', $user->email);

    $this->assertDatabaseHas('email_otps', [
        'email' => $user->email,
        'type' => 'password_reset',
    ]);

    Mail::assertSent(SendOtpMail::class, function (SendOtpMail $mail) use ($user) {
        return $mail->hasTo($user->email)
            && $mail->type === 'password_reset'
            && strlen($mail->otpCode) === 6;
    });
});

test('lupa password menolak alamat email yang tidak terdaftar', function () {
    /** @var TestCase $this */
    $response = $this->post(route('password.email'), [
        'email' => 'tidak.terdaftar@lms.test',
    ]);

    $response->assertSessionHasErrors('email');
    Mail::assertNothingSent();
});

test('reset password dengan kode OTP yang valid berhasil memperbarui kata sandi', function () {
    /** @var TestCase $this */
    $user = User::where('email', 'peserta1@lms.test')->first();

    $otp = EmailOtp::generate($user->email, 'password_reset', null, 10);

    $payload = [
        'email' => $user->email,
        'otp_code' => $otp->otp_code,
        'password' => 'KataSandiBaru2026!',
        'password_confirmation' => 'KataSandiBaru2026!',
    ];

    $response = $this->post(route('password.reset.otp.update'), $payload);

    $response->assertRedirect(route('login'));
    $response->assertSessionHas('success');

    // Pastikan kata sandi di database berubah
    $user->refresh();
    expect(Hash::check('KataSandiBaru2026!', $user->password))->toBeTrue();

    // Record OTP dibersihkan
    $this->assertDatabaseMissing('email_otps', [
        'id' => $otp->id,
    ]);

    // Pengguna bisa login dengan password baru
    $loginResponse = $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'KataSandiBaru2026!',
    ]);

    $loginResponse->assertRedirect(route('dashboard'));
    $this->assertAuthenticatedAs($user);
});
