<?php

use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

beforeEach(function () {
    /** @var TestCase $this */
    Mail::fake();
});

test('registration screen can be rendered', function () {
    /** @var TestCase $this */
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register as peserta using otp verification', function () {
    /** @var TestCase $this */
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect(route('register.otp'));

    $otp = EmailOtp::where('email', 'test@example.com')->where('type', 'register')->first();
    expect($otp)->not->toBeNull();

    $verifyResponse = $this->withSession(['otp_register_email' => 'test@example.com'])
        ->post(route('register.otp.verify'), [
            'email' => 'test@example.com',
            'otp_code' => $otp->otp_code,
        ]);

    $this->assertAuthenticated();
    $verifyResponse->assertRedirect(route('dashboard', absolute: false));

    $user = User::where('email', 'test@example.com')->first();
    expect($user)->not->toBeNull()
        ->and($user->hasRole('peserta'))->toBeTrue();
});
