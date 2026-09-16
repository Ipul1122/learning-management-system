<?php

use App\Models\ClassEnrollment;
use App\Models\GraduationSubmission;
use App\Models\TrainingClass;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

beforeEach(function () {
    /** @var TestCase $this */
    $this->seed();
});

function createApiTestClass(User $trainer, string $suffix = ''): TrainingClass
{
    return TrainingClass::firstOrCreate([
        'slug' => 'kelas-api-test-'.$trainer->id.($suffix ? "-{$suffix}" : ''),
    ], [
        'branch_id' => $trainer->branch_id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas API Test '.($suffix ?: 'Reguler'),
        'type' => 'online',
        'online_capacity' => 50,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'ongoing',
    ]);
}

test('api trainer dapat mengambil antrean kelulusan', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $student = User::where('email', 'peserta1@lms.test')->first();

    $class = createApiTestClass($trainer);
    $enrollment = ClassEnrollment::firstOrCreate([
        'class_id' => $class->id,
        'user_id' => $student->id,
    ], [
        'status' => 'review_pending',
        'total_minutes_accumulated' => 900,
        'enrolled_at' => now(),
    ]);

    GraduationSubmission::create([
        'enrollment_id' => $enrollment->id,
        'trainer_id' => $trainer->id,
        'total_jp_earned' => 20.0,
        'avg_quiz_score' => 88.0,
        'status' => 'pending',
    ]);

    Sanctum::actingAs($trainer);

    $response = $this->getJson('/api/v1/trainer/graduations');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'status',
                'total_jp_earned',
                'avg_quiz_score',
                'student' => ['id', 'name', 'email'],
                'class' => ['id', 'title'],
            ],
        ],
    ]);
});

test('api trainer dapat menyetujui kelulusan peserta', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $student = User::where('email', 'peserta1@lms.test')->first();

    $class = createApiTestClass($trainer);
    $enrollment = ClassEnrollment::firstOrCreate([
        'class_id' => $class->id,
        'user_id' => $student->id,
    ], [
        'status' => 'review_pending',
        'total_minutes_accumulated' => 900,
        'enrolled_at' => now(),
    ]);

    $submission = GraduationSubmission::create([
        'enrollment_id' => $enrollment->id,
        'trainer_id' => $trainer->id,
        'total_jp_earned' => 20.0,
        'avg_quiz_score' => 90.0,
        'status' => 'pending',
    ]);

    Sanctum::actingAs($trainer);

    $response = $this->postJson("/api/v1/trainer/graduations/{$submission->id}/approve");

    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Kelulusan berhasil disetujui.',
    ]);

    $submission->refresh();
    expect($submission->status)->toBe('approved');
    expect($submission->certificate_number)->not->toBeNull();
});

test('api trainer dapat menolak kelulusan dengan feedback remedial', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $student = User::where('email', 'peserta1@lms.test')->first();

    $class = createApiTestClass($trainer);
    $enrollment = ClassEnrollment::firstOrCreate([
        'class_id' => $class->id,
        'user_id' => $student->id,
    ], [
        'status' => 'review_pending',
        'accumulated_minutes' => 900,
        'accumulated_jp' => 20.0,
        'enrolled_at' => now(),
    ]);

    $submission = GraduationSubmission::create([
        'enrollment_id' => $enrollment->id,
        'trainer_id' => $trainer->id,
        'total_jp_earned' => 20.0,
        'avg_quiz_score' => 60.0,
        'status' => 'pending',
    ]);

    Sanctum::actingAs($trainer);

    $feedback = 'Nilai kuis evaluasi belum mencapai batas minimal 70. Silakan remedial.';
    $response = $this->postJson("/api/v1/trainer/graduations/{$submission->id}/reject", [
        'trainer_feedback' => $feedback,
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Pengajuan kelulusan ditolak.',
    ]);

    $submission->refresh();
    expect($submission->status)->toBe('rejected');
    expect($submission->trainer_feedback)->toBe($feedback);
});

test('api peserta dapat melihat daftar sertifikat miliknya', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $student = User::where('email', 'peserta1@lms.test')->first();

    $class = createApiTestClass($trainer);
    $enrollment = ClassEnrollment::firstOrCreate([
        'class_id' => $class->id,
        'user_id' => $student->id,
    ], [
        'status' => 'graduated',
        'accumulated_minutes' => 900,
        'accumulated_jp' => 20.0,
        'enrolled_at' => now(),
    ]);

    GraduationSubmission::create([
        'enrollment_id' => $enrollment->id,
        'trainer_id' => $trainer->id,
        'total_jp_earned' => 20.0,
        'avg_quiz_score' => 95.0,
        'status' => 'approved',
        'certificate_number' => 'CERT-2026-API-00001',
        'reviewed_at' => now(),
    ]);

    Sanctum::actingAs($student);

    $response = $this->getJson('/api/v1/peserta/certificates');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'enrollment_id',
                'status',
                'certificate_number',
                'verification_url',
                'class' => ['id', 'title'],
            ],
        ],
    ]);
});
