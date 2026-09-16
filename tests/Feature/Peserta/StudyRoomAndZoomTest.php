<?php

use App\Models\Branch;
use App\Models\ClassEnrollment;
use App\Models\ClassSession;
use App\Models\SessionAttendance;
use App\Models\TrainingClass;
use App\Models\User;
use Tests\TestCase;

beforeEach(function () {
    /** @var TestCase $this */
    $this->seed();
});

test('peserta dapat melihat ruang belajar dan metrik 20 JP global', function () {
    /** @var TestCase $this */
    $peserta = User::where('email', 'peserta1@lms.test')->first();
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $branch = Branch::first();

    $class = TrainingClass::create([
        'branch_id' => $branch->id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas Progres 20 JP',
        'slug' => 'kelas-progres-20-jp',
        'type' => 'online',
        'online_capacity' => 100,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'ongoing',
    ]);

    ClassEnrollment::create([
        'class_id' => $class->id,
        'user_id' => $peserta->id,
        'attendance_mode' => 'online',
        'accumulated_minutes' => 450, // 10 JP
        'accumulated_jp' => 10.00,
        'status' => 'enrolled',
        'enrolled_at' => now(),
    ]);

    $response = $this->actingAs($peserta)->get(route('peserta.study.index'));
    $response->assertStatus(200);
    $response->assertSee('Tracking 20 Jam Pelajaran');
    $response->assertSee('10 / 20.0 JP');
    $response->assertSee('450 dari 900 Menit (50%)');
});

test('peserta dapat membuka ruang belajar kelas yang diikutinya', function () {
    /** @var TestCase $this */
    $peserta = User::where('email', 'peserta1@lms.test')->first();
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $branch = Branch::first();

    $class = TrainingClass::create([
        'branch_id' => $branch->id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas Ruang Belajar Detail',
        'slug' => 'kelas-ruang-belajar-detail',
        'type' => 'online',
        'online_capacity' => 100,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'ongoing',
    ]);

    ClassEnrollment::create([
        'class_id' => $class->id,
        'user_id' => $peserta->id,
        'attendance_mode' => 'online',
        'accumulated_minutes' => 90,
        'accumulated_jp' => 2.00,
        'status' => 'enrolled',
        'enrolled_at' => now(),
    ]);

    $response = $this->actingAs($peserta)->get(route('peserta.study.show', $class));
    $response->assertStatus(200);
    $response->assertSee('Kelas Ruang Belajar Detail');
    $response->assertSee('Akumulasi JP');
});

test('pengguna yang belum terdaftar dialihkan ke katalog ketika mengakses ruang belajar kelas', function () {
    /** @var TestCase $this */
    $peserta = User::where('email', 'peserta1@lms.test')->first();
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $branch = Branch::first();

    $class = TrainingClass::create([
        'branch_id' => $branch->id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas Akses Terbatas',
        'slug' => 'kelas-akses-terbatas',
        'type' => 'online',
        'online_capacity' => 100,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'open',
    ]);

    $response = $this->actingAs($peserta)->get(route('peserta.study.show', $class));
    $response->assertRedirect(route('peserta.catalog.show', $class));
    $response->assertSessionHas('info');
});

test('1-klik masuk Zoom mencatat presensi sesi dan menambah akumulasi menit belajar', function () {
    /** @var TestCase $this */
    $peserta = User::where('email', 'peserta1@lms.test')->first();
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $branch = Branch::first();

    $class = TrainingClass::create([
        'branch_id' => $branch->id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas Live Zoom 1-Klik',
        'slug' => 'kelas-live-zoom-1-klik',
        'type' => 'online',
        'online_capacity' => 100,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'ongoing',
    ]);

    $enrollment = ClassEnrollment::create([
        'class_id' => $class->id,
        'user_id' => $peserta->id,
        'attendance_mode' => 'online',
        'accumulated_minutes' => 0,
        'accumulated_jp' => 0.00,
        'status' => 'enrolled',
        'enrolled_at' => now(),
    ]);

    $session = ClassSession::create([
        'class_id' => $class->id,
        'session_order' => 1,
        'title' => 'Sesi 1 Live Zoom',
        'jp_duration' => 2,
        'minute_duration' => 90, // 2 JP
        'session_date' => now()->addDay(),
        'zoom_url' => 'https://zoom.us/j/1234567890',
        'created_by_user_id' => $peserta->id,
    ]);

    $response = $this->actingAs($peserta)->post(route('peserta.study.zoom', [$class, $session]));

    $response->assertRedirect('https://zoom.us/j/1234567890');

    // Presensi tercatat
    $this->assertDatabaseHas('session_attendances', [
        'enrollment_id' => $enrollment->id,
        'session_id' => $session->id,
        'minutes_earned' => 90,
    ]);

    // Menit & JP bertambah (90 menit = 2.0 JP)
    $enrollment->refresh();
    expect($enrollment->accumulated_minutes)->toBe(90);
    expect((float) $enrollment->accumulated_jp)->toBe(2.0);
});

test('klik Zoom kedua kali pada sesi yang sama tidak melipatgandakan menit kehadiran', function () {
    /** @var TestCase $this */
    $peserta = User::where('email', 'peserta1@lms.test')->first();
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $branch = Branch::first();

    $class = TrainingClass::create([
        'branch_id' => $branch->id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas Idempotent Zoom',
        'slug' => 'kelas-idempotent-zoom',
        'type' => 'online',
        'online_capacity' => 100,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'ongoing',
    ]);

    $enrollment = ClassEnrollment::create([
        'class_id' => $class->id,
        'user_id' => $peserta->id,
        'attendance_mode' => 'online',
        'accumulated_minutes' => 90,
        'accumulated_jp' => 2.00,
        'status' => 'enrolled',
        'enrolled_at' => now(),
    ]);

    $session = ClassSession::create([
        'class_id' => $class->id,
        'session_order' => 1,
        'title' => 'Sesi Zoom Berulang',
        'jp_duration' => 2,
        'minute_duration' => 90,
        'session_date' => now()->addDay(),
        'zoom_url' => 'https://zoom.us/j/999888777',
        'created_by_user_id' => $peserta->id,
    ]);

    // Sudah pernah hadir
    SessionAttendance::create([
        'enrollment_id' => $enrollment->id,
        'session_id' => $session->id,
        'joined_zoom_at' => now()->subHour(),
        'minutes_earned' => 90,
        'is_verified' => false,
    ]);

    $response = $this->actingAs($peserta)->post(route('peserta.study.zoom', [$class, $session]));
    $response->assertRedirect('https://zoom.us/j/999888777');

    $enrollment->refresh();
    // Menit tidak boleh bertambah lagi
    expect($enrollment->accumulated_minutes)->toBe(90);
    expect((float) $enrollment->accumulated_jp)->toBe(2.0);
});
