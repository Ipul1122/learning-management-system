<?php

use App\Models\Branch;
use App\Models\ClassEnrollment;
use App\Models\TrainingClass;
use App\Models\User;
use Tests\TestCase;

beforeEach(function () {
    /** @var TestCase $this */
    $this->seed();
});

test('peserta dapat melihat katalog kelas yang berstatus open atau ongoing', function () {
    /** @var TestCase $this */
    $peserta = User::where('email', 'peserta1@lms.test')->first();
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $branch = Branch::first();

    $openClass = TrainingClass::create([
        'branch_id' => $branch->id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas Katalog Terbuka',
        'slug' => 'kelas-katalog-terbuka',
        'type' => 'offline',
        'offline_capacity' => 40,
        'enrolled_offline' => 5,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'open',
    ]);

    $response = $this->actingAs($peserta)->get(route('peserta.catalog.index'));
    $response->assertStatus(200);
    $response->assertSee('Katalog Kelas Pelatihan');
    $response->assertSee('Kelas Katalog Terbuka');
});

test('peserta dapat melihat detail kelas pada katalog', function () {
    /** @var TestCase $this */
    $peserta = User::where('email', 'peserta1@lms.test')->first();
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $branch = Branch::first();

    $class = TrainingClass::create([
        'branch_id' => $branch->id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas Detail Pelatihan',
        'slug' => 'kelas-detail-pelatihan',
        'type' => 'offline',
        'offline_capacity' => 40,
        'enrolled_offline' => 10,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'open',
    ]);

    $response = $this->actingAs($peserta)->get(route('peserta.catalog.show', $class));
    $response->assertStatus(200);
    $response->assertSee('Kelas Detail Pelatihan');
    $response->assertSee('30 / 40 Tersisa');
});

test('peserta dapat mendaftar kelas offline dengan batas maksimal 40 kursi', function () {
    /** @var TestCase $this */
    $peserta = User::where('email', 'peserta1@lms.test')->first();
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $branch = Branch::first();

    $class = TrainingClass::create([
        'branch_id' => $branch->id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas Offline Kuota Tersedia',
        'slug' => 'kelas-offline-kuota-tersedia',
        'type' => 'offline',
        'offline_capacity' => 40,
        'enrolled_offline' => 39,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'open',
    ]);

    $response = $this->actingAs($peserta)->post(route('peserta.catalog.enroll', $class), [
        'attendance_mode' => 'offline',
    ]);

    $response->assertRedirect(route('peserta.study.show', $class));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('class_enrollments', [
        'class_id' => $class->id,
        'user_id' => $peserta->id,
        'attendance_mode' => 'offline',
        'status' => 'enrolled',
    ]);

    // Counter offline bertambah
    expect($class->fresh()->enrolled_offline)->toBe(40);
});

test('pendaftaran kelas offline ditolak jika kapasitas 40 kursi sudah penuh', function () {
    /** @var TestCase $this */
    $peserta = User::where('email', 'peserta1@lms.test')->first();
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $branch = Branch::first();

    $class = TrainingClass::create([
        'branch_id' => $branch->id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas Offline Penuh',
        'slug' => 'kelas-offline-penuh',
        'type' => 'offline',
        'offline_capacity' => 40,
        'enrolled_offline' => 40,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'open',
    ]);

    $response = $this->actingAs($peserta)->post(route('peserta.catalog.enroll', $class), [
        'attendance_mode' => 'offline',
    ]);

    $response->assertSessionHasErrors('attendance_mode');
    $this->assertDatabaseMissing('class_enrollments', [
        'class_id' => $class->id,
        'user_id' => $peserta->id,
    ]);
});

test('peserta tidak dapat mendaftar dua kali pada kelas yang sama', function () {
    /** @var TestCase $this */
    $peserta = User::where('email', 'peserta1@lms.test')->first();
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $branch = Branch::first();

    $class = TrainingClass::create([
        'branch_id' => $branch->id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas Registrasi Ganda',
        'slug' => 'kelas-registrasi-ganda',
        'type' => 'online',
        'online_capacity' => 100,
        'enrolled_online' => 1,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'open',
    ]);

    ClassEnrollment::create([
        'class_id' => $class->id,
        'user_id' => $peserta->id,
        'attendance_mode' => 'online',
        'accumulated_minutes' => 0,
        'accumulated_jp' => 0.00,
        'status' => 'enrolled',
        'enrolled_at' => now(),
    ]);

    $response = $this->actingAs($peserta)->post(route('peserta.catalog.enroll', $class), [
        'attendance_mode' => 'online',
    ]);

    $response->assertSessionHas('info');
    expect(ClassEnrollment::where('class_id', $class->id)->where('user_id', $peserta->id)->count())->toBe(1);
});
