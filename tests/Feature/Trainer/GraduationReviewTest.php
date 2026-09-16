<?php

use App\Models\ClassEnrollment;
use App\Models\GraduationSubmission;
use App\Models\TrainingClass;
use App\Models\User;
use Tests\TestCase;

beforeEach(function () {
    /** @var TestCase $this */
    $this->seed();
});

function createTestClass(User $trainer, string $suffix = ''): TrainingClass
{
    return TrainingClass::firstOrCreate([
        'slug' => 'kelas-test-sertifikasi-'.$trainer->id.($suffix ? "-{$suffix}" : ''),
    ], [
        'branch_id' => $trainer->branch_id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas Sertifikasi '.($suffix ?: 'Utama'),
        'type' => 'online',
        'online_capacity' => 50,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'ongoing',
    ]);
}

test('trainer dapat melihat antrean verifikasi kelulusan kelasnya', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $student = User::where('email', 'peserta1@lms.test')->first();

    $class = createTestClass($trainer);
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
        'avg_quiz_score' => 85.5,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($trainer)->get(route('trainer.graduations.index'));

    $response->assertStatus(200);
    $response->assertSee('Verifikasi Kelulusan 20 JP');
    $response->assertSee($student->name);
    $response->assertSee($class->title);
});

test('trainer dapat melihat lembar detail audit kelulusan peserta', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $student = User::where('email', 'peserta1@lms.test')->first();

    $class = createTestClass($trainer);
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
        'avg_quiz_score' => 88.0,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($trainer)->get(route('trainer.graduations.show', $submission));

    $response->assertStatus(200);
    $response->assertSee('Audit Kelulusan:');
    $response->assertSee($student->name);
    $response->assertSee('Terbitkan E-Sertifikat');
    $response->assertSee('Tolak Kelulusan');
});

test('trainer dapat menyetujui kelulusan dan menerbitkan nomor sertifikat resmi', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $student = User::where('email', 'peserta1@lms.test')->first();

    $class = createTestClass($trainer);
    $enrollment = ClassEnrollment::firstOrCreate([
        'class_id' => $class->id,
        'user_id' => $student->id,
    ], [
        'status' => 'review_pending',
        'total_minutes_accumulated' => 945,
        'enrolled_at' => now(),
    ]);

    $submission = GraduationSubmission::create([
        'enrollment_id' => $enrollment->id,
        'trainer_id' => $trainer->id,
        'total_jp_earned' => 21.0,
        'avg_quiz_score' => 90.0,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($trainer)->post(route('trainer.graduations.approve', $submission));

    $response->assertRedirect(route('trainer.graduations.show', $submission));
    $response->assertSessionHas('success');

    $submission->refresh();
    $enrollment->refresh();

    expect($submission->status)->toBe('approved');
    expect($submission->certificate_number)->not->toBeNull();
    expect($submission->certificate_number)->toContain('CERT-');
    expect($submission->reviewed_at)->not->toBeNull();
    expect($enrollment->status)->toBe('graduated');
});

test('trainer dapat menolak pengajuan kelulusan dengan feedback remedial wajib', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $student = User::where('email', 'peserta1@lms.test')->first();

    $class = createTestClass($trainer);
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
        'avg_quiz_score' => 55.0,
        'status' => 'pending',
    ]);

    // Validation test: Empty feedback should fail
    $invalidResponse = $this->actingAs($trainer)->post(route('trainer.graduations.reject', $submission), [
        'trainer_feedback' => '',
    ]);
    $invalidResponse->assertSessionHasErrors('trainer_feedback');

    // Valid rejection with notes
    $feedback = 'Nilai kuis evaluasi 55 masih di bawah KKM 70. Mohon kerjakan remedial kuis sesi 3 dan 4.';
    $response = $this->actingAs($trainer)->post(route('trainer.graduations.reject', $submission), [
        'trainer_feedback' => $feedback,
    ]);

    $response->assertRedirect(route('trainer.graduations.show', $submission));
    $response->assertSessionHas('warning');

    $submission->refresh();
    $enrollment->refresh();

    expect($submission->status)->toBe('rejected');
    expect($submission->trainer_feedback)->toBe($feedback);
    expect($enrollment->status)->toBe('rejected');
});

test('trainer tidak dapat mengaudit pengajuan kelulusan dari kelas instruktur lain', function () {
    /** @var TestCase $this */
    $trainer1 = User::where('email', 'trainer1@lms.test')->first();
    $otherTrainer = User::role('trainer')->where('id', '!=', $trainer1->id)->first();

    $student = User::where('email', 'peserta1@lms.test')->first();
    $class = createTestClass($otherTrainer, 'other');

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
        'trainer_id' => $otherTrainer->id,
        'total_jp_earned' => 20.0,
        'avg_quiz_score' => 80.0,
        'status' => 'pending',
    ]);

    // trainer1 tries to access otherTrainer's submission
    $response = $this->actingAs($trainer1)->get(route('trainer.graduations.show', $submission));
    $response->assertStatus(403);
});
