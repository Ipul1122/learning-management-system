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

function createCertificateTestClass(User $trainer, string $suffix = ''): TrainingClass
{
    return TrainingClass::firstOrCreate([
        'slug' => 'kelas-test-cert-'.$trainer->id.($suffix ? "-{$suffix}" : ''),
    ], [
        'branch_id' => $trainer->branch_id,
        'trainer_id' => $trainer->id,
        'title' => 'Kelas Sertifikasi Peserta '.($suffix ?: 'Reguler'),
        'type' => 'online',
        'online_capacity' => 50,
        'required_jp' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(14)->toDateString(),
        'status' => 'ongoing',
    ]);
}

test('peserta yang mencapai 20 JP otomatis tercatat pengajuan kelulusannya', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $student = User::where('email', 'peserta1@lms.test')->first();

    $class = createCertificateTestClass($trainer);
    $enrollment = ClassEnrollment::firstOrCreate([
        'class_id' => $class->id,
        'user_id' => $student->id,
    ], [
        'status' => 'in_progress',
        'accumulated_minutes' => 850,
        'accumulated_jp' => 18.9,
        'enrolled_at' => now(),
    ]);

    // Student attends 60 more minutes, passing 900 minutes (20 JP)
    $enrollment->addMinutes(60);

    $enrollment->refresh();

    expect($enrollment->total_minutes_accumulated)->toBe(910);
    expect($enrollment->status)->toBe('review_pending');

    $submission = $enrollment->graduationSubmission;
    expect($submission)->not->toBeNull();
    expect($submission->status)->toBe('pending');
    expect($submission->trainer_id)->toBe($trainer->id);
    expect($submission->total_jp_earned)->toBe(20.2);
});

test('peserta dapat melihat daftar sertifikat dan status kelulusannya', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $student = User::where('email', 'peserta1@lms.test')->first();

    $class = createCertificateTestClass($trainer);
    $enrollment = ClassEnrollment::firstOrCreate([
        'class_id' => $class->id,
        'user_id' => $student->id,
    ], [
        'status' => 'review_pending',
        'total_minutes_accumulated' => 900,
        'enrolled_at' => now(),
    ]);

    $response = $this->actingAs($student)->get(route('peserta.certificates.index'));

    $response->assertStatus(200);
    $response->assertSee('Kelulusan Saya');
    $response->assertSee($class->title);
});

test('peserta dapat mengunduh E-Sertifikat Digital PDF resmi jika sudah disetujui', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $student = User::where('email', 'peserta1@lms.test')->first();

    $class = createCertificateTestClass($trainer);
    $enrollment = ClassEnrollment::firstOrCreate([
        'class_id' => $class->id,
        'user_id' => $student->id,
    ], [
        'status' => 'graduated',
        'total_minutes_accumulated' => 900,
        'enrolled_at' => now(),
    ]);

    $submission = GraduationSubmission::create([
        'enrollment_id' => $enrollment->id,
        'trainer_id' => $trainer->id,
        'total_jp_earned' => 20.0,
        'avg_quiz_score' => 95.0,
        'status' => 'approved',
        'certificate_number' => 'CERT-2026-BDG-00001',
        'reviewed_at' => now(),
    ]);

    $response = $this->actingAs($student)->get(route('peserta.certificates.download', $submission));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});

test('peserta tidak dapat mengunduh sertifikat jika status pengajuan masih pending atau ditolak', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $student = User::where('email', 'peserta1@lms.test')->first();

    $class = createCertificateTestClass($trainer);
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
        'avg_quiz_score' => 70.0,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($student)->get(route('peserta.certificates.download', $submission));
    $response->assertStatus(403);
});

test('halaman verifikasi publik dapat diakses tanpa login dan memverifikasi sertifikat asli', function () {
    /** @var TestCase $this */
    $trainer = User::where('email', 'trainer1@lms.test')->first();
    $student = User::where('email', 'peserta1@lms.test')->first();

    $class = createCertificateTestClass($trainer);
    $enrollment = ClassEnrollment::firstOrCreate([
        'class_id' => $class->id,
        'user_id' => $student->id,
    ], [
        'status' => 'graduated',
        'total_minutes_accumulated' => 900,
        'enrolled_at' => now(),
    ]);

    $certNumber = 'CERT-2026-PST-99999';

    GraduationSubmission::create([
        'enrollment_id' => $enrollment->id,
        'trainer_id' => $trainer->id,
        'total_jp_earned' => 20.0,
        'avg_quiz_score' => 92.0,
        'status' => 'approved',
        'certificate_number' => $certNumber,
        'reviewed_at' => now(),
    ]);

    // Public request without authentication
    $response = $this->get(route('certificates.verify', $certNumber));

    $response->assertStatus(200);
    $response->assertSee('DOKUMEN ASLI');
    $response->assertSee($certNumber);
    $response->assertSee($student->name);
    $response->assertSee($class->title);
});

test('halaman verifikasi publik merespon 404 untuk nomor sertifikat fiktif', function () {
    /** @var TestCase $this */
    $response = $this->get(route('certificates.verify', 'CERT-FAKE-999999'));
    $response->assertStatus(404);
});
