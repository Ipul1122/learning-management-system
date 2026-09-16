<?php

use App\Mail\SendOtpMail;
use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

beforeEach(function () {
    /** @var TestCase $this */
    Mail::fake();
});

test('reset password screen can be rendered', function () {
    /** @var TestCase $this */
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
});

test('reset password otp can be requested', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();

    $response = $this->post('/forgot-password', ['email' => $user->email]);

    $response->assertRedirect(route('password.reset.otp'));

    Mail::assertSent(SendOtpMail::class, function (SendOtpMail $mail) use ($user) {
        return $mail->hasTo($user->email)
            && $mail->type === 'password_reset'
            && strlen($mail->otpCode) === 6;
    });
});

test('password can be reset with valid otp', function () {
    /** @var TestCase $this */
    $user = User::factory()->create([
        'password' => Hash::make('old-password'),
    ]);

    $this->post('/forgot-password', ['email' => $user->email]);

    $otp = EmailOtp::where('email', $user->email)->where('type', 'password_reset')->first();
    expect($otp)->not->toBeNull();

    $response = $this->post(route('password.reset.otp.update'), [
        'email' => $user->email,
        'otp_code' => $otp->otp_code,
        'password' => 'new-password123',
        'password_confirmation' => 'new-password123',
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('login'));

    $user->refresh();
    expect(Hash::check('new-password123', $user->password))->toBeTrue();
});
