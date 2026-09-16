<?php

use App\Models\ClassSession;
use App\Models\TrainingClass;
use App\Models\User;
use Tests\TestCase;

beforeEach(function () {
    /** @var TestCase $this */
    $this->seed();
});

test('trainer dapat melihat daftar kelas yang diampunya', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();

    $response = $this->actingAs($trainer)->get(route('trainer.classes.index'));
    $response->assertStatus(200);
    $response->assertSee('Kelas Pelatihan');
    $response->assertSee('Jadwal Pengajaran');
});

test('trainer dapat memperbarui tautan Zoom sesi pertemuan pada kelasnya', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();

    $class = TrainingClass::where('branch_id', $trainer->branch_id)->where('trainer_id', $trainer->id)->first();
    if (! $class) {
        $class = TrainingClass::create([
            'branch_id' => $trainer->branch_id,
            'trainer_id' => $trainer->id,
            'title' => 'Kelas Zoom Test',
            'slug' => 'kelas-zoom-test',
            'type' => 'online',
            'online_capacity' => 50,
            'required_jp' => 20,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(14)->toDateString(),
            'status' => 'ongoing',
        ]);
    }

    $session = ClassSession::create([
        'class_id' => $class->id,
        'session_order' => 1,
        'title' => 'Pengenalan Fundamental',
        'jp_duration' => 2,
        'minute_duration' => 90,
        'session_date' => now()->addDay(),
        'created_by_user_id' => $trainer->id,
    ]);

    $payload = [
        'zoom_url' => 'https://zoom.us/j/9876543210?pwd=secretpassword',
        'zoom_meeting_id' => '987 654 3210',
        'zoom_passcode' => 'secret123',
    ];

    $response = $this->actingAs($trainer)->patch(
        route('trainer.classes.sessions.zoom', [$class, $session]),
        $payload
    );

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $session->refresh();
    expect($session->zoom_url)->toBe('https://zoom.us/j/9876543210?pwd=secretpassword');
    expect($session->zoom_meeting_id)->toBe('987 654 3210');
    expect($session->zoom_passcode)->toBe('secret123');
});

test('trainer tidak dapat memperbarui tautan Zoom sesi pada kelas milik trainer lain', function () {
    /** @var TestCase $this */
    $trainer1 = User::where('email', 'trainer1@lms.test')->first();
    $trainer2 = User::where('email', 'trainer2@lms.test')->first();

    $classTrainer2 = TrainingClass::create([
        'branch_id' => $trainer2->branch_id,
        'trainer_id' => $trainer2->id,
        'title' => 'Kelas Trainer 2 Zoom Test',
        'slug' => 'kelas-trainer-2-zoom-test',
        'type' => 'online',
        'online_capacity' => 50,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'ongoing',
    ]);

    $session = ClassSession::create([
        'class_id' => $classTrainer2->id,
        'session_order' => 1,
        'title' => 'Sesi Rahasia',
        'jp_duration' => 2,
        'minute_duration' => 90,
        'session_date' => now()->addDay(),
        'created_by_user_id' => $trainer2->id,
    ]);

    $payload = [
        'zoom_url' => 'https://zoom.us/j/111222333',
    ];

    // Trainer 1 mencoba mengubah session kelas Trainer 2
    $response = $this->actingAs($trainer1)->patch(
        route('trainer.classes.sessions.zoom', [$classTrainer2, $session]),
        $payload
    );

    $response->assertStatus(403);
});
